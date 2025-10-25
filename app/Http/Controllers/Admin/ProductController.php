<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ApiLog;
use DataTables;
use Http;
use DB;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductModel;
use App\Models\Group;
use App\Models\Unit;
use App\Models\Size;
use App\Models\Color;
use App\Models\Type;
use App\Models\Tag;
use App\Models\SubCategory;
use App\Models\SubSubCategory;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use App\Models\ProductImage;

class ProductController extends Controller
{
    public function create()
    {
        $categories = Category::where('status', 1)->orderBy('serial', 'asc')->get();
        $subCategories = SubCategory::where('status', 1)->orderBy('serial', 'asc')->get();
        $subSubCategories = SubSubCategory::where('status', 1)->orderBy('serial', 'asc')->get();
        $brands = Brand::where('status', 1)->latest()->get();
        $models = ProductModel::where('status', 1)->latest()->get();
        $groups = Group::where('status', 1)->latest()->get();
        $units = Unit::where('status', 1)->latest()->get();
        $sizes = Size::where('status', 1)->latest()->get();
        $colors = Color::where('status', 1)->latest()->get();
        $types = Type::where('status', 1)->latest()->get();
        $tags = Tag::where('status', 1)->latest()->get();

        return view('admin.product.create', compact('categories','brands','models','groups','units','sizes','colors','tags','types'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'category' => 'required|array|min:1',
            'category.*' => 'required|integer|exists:categories,id', // each must exist
            'subcategory' => 'nullable|array',
            'subcategory.*' => 'nullable|integer|exists:sub_categories,id',
            'subsubcategory' => 'nullable|array',
            'subsubcategory.*' => 'nullable|integer|exists:sub_sub_categories,id',
            'feature_image' => 'nullable|image|mimes:jpeg,png,jpg,webp',
            'gallery_images' => 'nullable|array',
            'gallery_images.*.file' => 'nullable|image|mimes:jpeg,png,jpg,webp',
            'tags' => 'nullable|array',
        ]);

        $product = DB::transaction(function () use ($request) {

            $product = new Product();
            $fields = [
                'name','code','short_description','long_description','brand_id','unit_id','group_id',
                'price','discount_percent','meta_title','meta_description','meta_keywords','company',
                'category_api','product_code_api','product_name_api','full_description','short_code',
                'tariff_no','ean','wash_degrees','gender','gsm','composition','specifications',
                'colour_code','colour_name_api','pantone','hex_code','size_name_api','price_single',
                'qty_single','price_pack','pack_qty','price_caton','carton_qty','price_1k','quantity_api',
                'my_price','small_image','colour_image','sm_colour_image','video_link','packaging',
                'country_of_origin','gross_weight','net_weight','tax_code','is_featured','is_recent',
                'is_popular','is_trending','is_new_arrival','is_top_rated'
            ];

            foreach ($fields as $field) {
                if ($request->has($field)) $product->$field = $request->$field;
            }

            if ($request->hasFile('feature_image')) {
                $uploadedFile = $request->file('feature_image');
                $randomName = mt_rand(10000000, 99999999) . '.webp';
                $destinationPath = public_path('images/products/');
                if (!file_exists($destinationPath)) mkdir($destinationPath, 0755, true);

                Image::make($uploadedFile)
                    ->resize(1200, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                    ->encode('webp', 50)
                    ->save($destinationPath . $randomName);

                $product->feature_image = $randomName;
            }

            $baseSlug = Str::slug($product->name);
            $slug = $baseSlug;
            $counter = 1;

            while (Product::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }

            $product->slug = $slug;
            $product->created_by = auth()->id();
            $product->save();

            if ($request->has('tags')) $product->tags()->sync($request->tags);

            if ($request->has('gallery_images')) {
                $destinationPath = public_path('images/products/');
                if (!file_exists($destinationPath)) mkdir($destinationPath, 0755, true);

                foreach ($request->gallery_images as $img) {
                    $imageFile = $img['file'];
                    $color = $img['color'] ?? null;

                    $imageName = mt_rand(10000000, 99999999) . '.webp';
                    Image::make($imageFile)
                        ->resize(800, null, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        })
                        ->encode('webp', 50)
                        ->save($destinationPath . $imageName);

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => $imageName,
                        'color_id' => $color,
                        'created_by' => auth()->id(),
                    ]);
                }
            }

            $categories = $request->category ?? [];
            $subcategories = $request->subcategory ?? [];
            $subsubcategories = $request->subsubcategory ?? [];

            for ($i = 0; $i < count($categories); $i++) {
                DB::table('category_products')->insert([
                    'product_id' => $product->id,
                    'category_id' => $categories[$i] ?? null,
                    'sub_category_id' => $subcategories[$i] ?? null,
                    'sub_sub_category_id' => $subsubcategories[$i] ?? null,
                    'created_by' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return $product;
        });

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product
        ]);
    }

    public function getData(Request $request)
    {
        $type = $request->type;
        $id = $request->id;

        if ($type === 'category') {
            return response()->json(Category::where('status', 1)->orderBy('serial', 'asc')->get());
        }

        if ($type === 'subcategory') {
            return response()->json(SubCategory::where('category_id', $id)->where('status', 1)->orderBy('serial', 'asc')->get());
        }

        if ($type === 'subsubcategory') {
            return response()->json(SubSubCategory::where('sub_category_id', $id)->where('status', 1)->orderBy('serial', 'asc')->get());
        }

        return response()->json([]);
    }

    public function getApiProducts(Request $request)
    {
        if ($request->ajax()) {
            $products = Product::where('product_source', 2);

            return DataTables::of($products)
                ->addIndexColumn()
                ->addColumn('image', fn($row) => $row->image ? '<img src="'.$row->image.'" class="img-thumbnail" width="50">' : '')
                ->rawColumns(['image'])
                ->make(true);
        }

        return view('admin.product.api_index');
    }

    public function groupByProductCode($code)
    {
        $products = DB::table('products')
            ->where('product_code_api', $code)
            ->get();

        $count = $products->count();

        return response()->json([
            'product_code' => $code,
            'count' => $count,
            'products' => $products
        ]);
    }

    public function syncProducts()
    {
        $log = ApiLog::create([
            'endpoint' => 'https://dev-vsp-uks-new-website-nav-odata.azurewebsites.net/productdata/all',
            'job_name' => 'Product Sync',
            'started_at' => now(),
            'status' => 'pending',
            'records_processed' => 0,
            'records_created' => 0,
        ]);

        try {
            $response = Http::timeout(300)->get($log->endpoint);
            $decoded = json_decode($response->body(), true);
            $total = count($decoded);
            $log->update(['records_processed' => $total]);

            session(['api_import_data_'.$log->id => $decoded, 'api_import_index_'.$log->id => 0]);

            return response()->json(['success' => true, 'log_id' => $log->id, 'total' => $total]);
        } catch (\Exception $e) {
            $log->update(['status' => 'failed', 'error_message' => $e->getMessage(), 'completed_at' => now()]);
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function importChunk($id)
    {
        $log = ApiLog::findOrFail($id);
        $data = session('api_import_data_'.$id, []);
        $index = session('api_import_index_'.$id, 0);
        $chunkSize = 50;

        if($index >= count($data)){
            $log->update(['status' => 'success', 'completed_at' => now(), 'records_created' => $log->records_created]);
            return response()->json(['done'=>true]);
        }

        $chunk = array_slice($data, $index, $chunkSize);
        $insertData = [];
        foreach($chunk as $item){
            $insertData[] = [
                'api_log_id' => $log->id,
                'company' => $item['Company'] ?? null,
                'category_api' => $item['Category'] ?? null,
                'product_code_api' => $item['ProductCode'] ?? null,
                'product_name_api' => $item['ProductName'] ?? null,
                'full_description' => $item['FullDescription'] ?? null,
                'short_code' => $item['ShortCode'] ?? null,
                'tariff_no' => $item['TariffNo'] ?? null,
                'ean' => $item['EAN'] ?? null,
                'wash_degrees' => $item['WashDegrees'] ?? null,
                'gender' => $item['Gender'] ?? null,
                'gsm' => $item['GSM'] ?? null,
                'composition' => $item['Composition'] ?? null,
                'specifications' => $item['Specifications'] ?? null,
                'colour_code' => $item['ColourCode'] ?? null,
                'colour_name_api' => $item['Colour'] ?? null,
                'pantone' => $item['Pantone'] ?? null,
                'hex_code' => $item['Hex'] ?? null,
                'size_name_api' => $item['Size'] ?? null,
                'price_single' => $item['PriceSingle'] ?? null,
                'qty_single' => $item['QtySingle'] ?? null,
                'price_pack' => $item['PricePack'] ?? null,
                'pack_qty' => $item['PackQty'] ?? null,
                'price_caton' => $item['PriceCaton'] ?? null,
                'carton_qty' => $item['CartonQty'] ?? null,
                'price_1k' => $item['Price1K'] ?? null,
                'quantity_api' => $item['Quantity'] ?? null,
                'my_price' => $item['MyPrice'] ?? null,
                'image' => $item['Image'] ?? null,
                'small_image' => $item['SmallImage'] ?? null,
                'colour_image' => $item['ColourImage'] ?? null,
                'sm_colour_image' => $item['SMColourImage'] ?? null,
                'video_link' => $item['VideoLink'] ?? null,
                'packaging' => $item['Packaging'] ?? null,
                'country_of_origin' => $item['CountryOfOrigin'] ?? null,
                'gross_weight' => $item['GrossWeight'] ?? null,
                'net_weight' => $item['NetWeight'] ?? null,
                'tax_code' => $item['TaxCode'] ?? null,
                'product_source' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('products')->insert($insertData);
        $created = $log->records_created + count($insertData);
        $log->update(['records_created' => $created]);

        session(['api_import_index_'.$id => $index + $chunkSize]);

        return response()->json([
            'done'=>false,
            'total'=>$log->records_processed,
            'synced'=>$created,
            'chunk_count'=>count($insertData)
        ]);
    }
}