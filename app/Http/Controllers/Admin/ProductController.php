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
            $products = Product::select([
                'id', 'product_code_api', 'product_name_api', 'company',
                'category_api', 'ean', 'price_single', 'quantity_api',
                'image', 'country_of_origin',
            ]);

            return DataTables::of($products)
                ->addIndexColumn()
                ->addColumn('image', fn($row) => $row->image ? '<img src="'.$row->image.'" class="img-thumbnail" width="50">' : '')
                ->rawColumns(['image'])
                ->make(true);
        }

        return view('admin.product.api_index');
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