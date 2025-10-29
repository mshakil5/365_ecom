<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ApiProduct;
use App\Models\ApiLog;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Color;
use App\Models\Size;
use App\Models\Company;
use App\Models\Category;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Str;
use Exception;
use Log;
use DataTables;

class ApiProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ApiProduct::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    $checked = $row->status ? 'checked' : '';
                    return '<div class="form-check form-switch">
                                <input class="form-check-input toggle-status" data-id="'.$row->id.'" type="checkbox" '.$checked.'>
                            </div>';
                })
                ->addColumn('last_sync', fn($row) => $row->last_sync_at ? \Carbon\Carbon::parse($row->last_sync_at)->format('M j, Y g:i A') : 'Never')
                ->addColumn('action', function ($row) {
                    return '
                        <div class="dropdown">
                            <button class="btn btn-soft-secondary btn-sm" data-bs-toggle="dropdown">
                                <i class="ri-more-fill"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <button class="dropdown-item importBtn" 
                                            data-id="'.$row->id.'" 
                                            data-company="'.$row->company.'">
                                        <i class="ri-download-2-fill me-2"></i> Import Products
                                    </button>
                                </li>
                                <li><button class="dropdown-item EditBtn" data-id="'.$row->id.'">
                                    <i class="ri-pencil-fill me-2"></i>Edit
                                </button></li>
                                <li class="dropdown-divider"></li>
                                <li><button class="dropdown-item deleteBtn" data-delete-url="'.route('api_products.delete', $row->id).'" data-method="DELETE">
                                    <i class="ri-delete-bin-fill me-2"></i>Delete
                                </button></li>
                            </ul>
                        </div>';
                })
                ->rawColumns(['status', 'last_sync', 'action'])
                ->make(true);
        }

        return view('admin.api_product.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'company' => 'required|unique:api_products,company',
            'url' => 'required|url',
        ]);

        ApiProduct::create([
            'company' => $request->company,
            'url' => $request->url,
            'description' => $request->description,
            'status' => 1,
            'created_by' => auth()->id(),
        ]);

        return response()->json(['message' => 'API Source created successfully.']);
    }

    public function edit($id)
    {
        return response()->json(ApiProduct::findOrFail($id));
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:api_products,id',
            'company' => 'required|unique:api_products,company,'.$request->id,
            'url' => 'required|url',
        ]);

        $data = ApiProduct::findOrFail($request->id);
        $data->update([
            'company' => $request->company,
            'url' => $request->url,
            'description' => $request->description,
            'updated_by' => auth()->id(),
        ]);

        return response()->json(['message' => 'API Source updated successfully.']);
    }

    public function destroy($id)
    {
        $data = ApiProduct::find($id);
        if (!$data) return response()->json(['message' => 'Not found.'], 404);
        $data->delete();
        return response()->json(['message' => 'Deleted successfully.']);
    }

    public function toggleStatus(Request $request)
    {
        $data = ApiProduct::find($request->id);
        if (!$data) return response()->json(['message' => 'Not found.'], 404);
        $data->status = $request->status;
        $data->save();
        return response()->json(['message' => 'Status updated successfully.']);
    }

    public function syncProducts(Request $request)
    {
        $api = ApiProduct::findOrFail($request->query('id'));
        $apiUrl = $api->url;

        ini_set('memory_limit', '1G');

        try {
            $response = Http::timeout(3000)->get($apiUrl);

            if (!$response->successful()) {
                return response()->json(['error' => 'API request failed'], 500);
            }

            $decoded = json_decode($response->body(), true);
            $productsData = $decoded['products'] ?? $decoded;

            $log = ApiLog::create([
                'api_product_id' => $api->id,
                'created_at' => now()
            ]);

            DB::beginTransaction();

            foreach ($productsData as $item) {
                $productCode = $item['ProductCode'] ?? null;
                if (!$productCode) continue;

                // Company
                $companyName = $item['Company'] ?? null;
                $companyId = null;
                if ($companyName) {
                    $company = Company::firstOrCreate(
                        ['name' => $companyName],
                        [
                            'slug' => \Str::slug($companyName),
                            'status' => true,
                        ]
                    );
                    $companyId = $company->id;
                }

                // Category
                $categoryName = $item['Category'] ?? null;
                $categoryId = null;
                if ($categoryName) {
                    $category = Category::firstOrCreate(
                        ['name' => $categoryName],
                        [
                            'slug' => \Str::slug($categoryName),
                            'status' => true,
                        ]
                    );
                    $categoryId = $category->id;
                }

                $baseSlug = \Str::slug($item['ProductName'] ?? $productCode);
                $slug = $baseSlug;
                $counter = 1;
                while (Product::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }

                // Product
                $product = Product::updateOrCreate(
                    ['product_code' => $productCode],
                    [
                        'company_id' => $companyId,
                        'category_id' => $categoryId,
                        'name' => $item['ProductName'] ?? $productCode,
                        'slug' => $slug,
                        'full_description' => $item['FullDescription'] ?? null,
                        'composition' => $item['Composition'] ?? null,
                        'specifications' => $item['Specifications'] ?? null,
                        'wash_degrees' => $item['WashDegrees'] ?? null,
                        'gender' => $item['Gender'] ?? null,
                        'gsm' => $item['GSM'] ?? null,
                        'country_of_origin' => $item['CountryOfOrigin'] ?? null,
                        'tariff_no' => $item['TariffNo'] ?? null,
                        'video_link' => $item['VideoLink'] ?? null,
                        'packaging' => $item['Packaging'] ?? null,
                        'tax_code' => $item['TaxCode'] ?? null,
                        'feature_image' => $item['Image'] ?? null,
                        'small_image' => $item['SmallImage'] ?? null,
                        'price' => $item['PriceSingle'] ?? 0,
                        'status' => true,
                        'api_log_id' => $log->id ?? null,
                        'product_source' => 2,
                    ]
                );

                // Color
                $color = null;
                if (!empty($item['ColourCode']) && !empty($item['Colour'])) {
                    $color = Color::firstOrCreate(
                        ['code' => $item['ColourCode']],
                        [
                            'name' => $item['Colour'],
                            'pantone' => $item['Pantone'] ?? null,
                            'hex' => $item['Hex'] ?? null,
                            'status' => true,
                        ]
                    );
                }

                // Size
                $size = null;
                if (!empty($item['Size'])) {
                    $size = Size::firstOrCreate(
                        ['name' => $item['Size']],
                    );
                }

                // Variant
                ProductVariant::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'color_id' => $color?->id,
                        'size_id' => $size?->id,
                    ],
                    [
                        'variant_short_code' => $item['ShortCode'] ?? null,
                        'short_code' => $item['ShortCode'] ?? null,
                        'ean' => $item['EAN'] ?? null,
                        'price_single' => $item['PriceSingle'] ?? null,
                        'qty_single' => $item['QtySingle'] ?? null,
                        'price_pack' => $item['PricePack'] ?? null,
                        'pack_qty' => $item['PackQty'] ?? null,
                        'price_carton' => $item['PriceCaton'] ?? null,
                        'carton_qty' => $item['CartonQty'] ?? null,
                        'price_1k' => $item['Price1K'] ?? null,
                        'quantity' => $item['Quantity'] ?? null,
                        'my_price' => $item['MyPrice'] ?? null,
                        'color_image' => $item['ColourImage'] ?? null,
                        'sm_color_image' => $item['SMColourImage'] ?? null,
                        'stock_quantity' => $item['Quantity'] ?? 0,
                        'is_active' => true
                    ]
                );

                // Images
                $images = [];
                if (!empty($item['Image'])) {
                    $images[] = [
                        'product_id' => $product->id,
                        'color_id' => null,
                        'image_path' => $item['Image'],
                        'image_type' => 'general',
                        'is_primary' => true,
                        'sort_order' => 1,
                        'created_at' => now(),
                    ];
                }
                if (!empty($item['SmallImage'])) {
                    $images[] = [
                        'product_id' => $product->id,
                        'color_id' => null,
                        'image_path' => $item['SmallImage'],
                        'image_type' => 'general',
                        'is_primary' => false,
                        'sort_order' => 2,
                        'created_at' => now(),
                    ];
                }
                if (!empty($item['ColourImage']) && $color) {
                    $images[] = [
                        'product_id' => $product->id,
                        'color_id' => $color->id,
                        'image_path' => $item['ColourImage'],
                        'image_type' => 'general',
                        'is_primary' => true,
                        'sort_order' => 3,
                        'created_at' => now(),
                    ];
                }
                if (!empty($item['SMColourImage']) && $color) {
                    $images[] = [
                        'product_id' => $product->id,
                        'color_id' => $color->id,
                        'image_path' => $item['SMColourImage'],
                        'image_type' => 'general',
                        'is_primary' => false,
                        'sort_order' => 4,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }

                foreach ($images as $img) {
                    DB::table('product_images')->updateOrInsert(
                        [
                            'product_id' => $img['product_id'],
                            'color_id' => $img['color_id'],
                            'image_path' => $img['image_path']
                        ],
                        $img
                    );
                }
            }

            $api->update(['last_sync_at' => now()]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'API data imported successfully']);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}