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
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::where('product_source', 1)
                ->latest()
                ->select('id', 'name', 'code', 'price', 'feature_image', 'company', 'status', 'created_at');

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('feature_image', function ($row) {
                    return $row->feature_image
                        ? '<img src="'.asset('images/products/'.$row->feature_image).'" class="img-thumbnail">'
                        : '';
                })
                ->editColumn('status', function ($row) {
                    return $row->status == 1
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                })
                ->editColumn('created_at', function ($row) {
                  return optional($row->created_at)->format('d M, Y');
                })
                ->addColumn('prices', function($row) {
                    $url = route('product_prices.index') . '?product_id=' . $row->id;
                    return '<a href="'.$url.'" class="btn btn-sm btn-primary"><i class="ri-money-dollar-circle-line"></i></a>';
                })
                ->addColumn('action', function($row){
                    return '
                    <div class="dropdown">
                        <button class="btn btn-soft-secondary btn-sm" data-bs-toggle="dropdown"><i class="ri-more-fill"></i></button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="'.route('products.edit', $row->id).'"><i class="ri-pencil-fill me-2"></i>Edit</a></li>
                            <li class="dropdown-divider"></li>
                            <li><button class="dropdown-item deleteBtn" data-method="DELETE" data-table="#productTable"><i class="ri-delete-bin-fill me-2"></i>Delete</button></li>
                        </ul>
                    </div>';
                })
                ->rawColumns(['feature_image', 'status', 'prices', 'action'])
                ->make(true);
        }

        return view('admin.product.index');
    }

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

    public function edit($id)
    {
        $product = Product::with(['categories', 'subCategories', 'subSubCategories', 'tags', 'productImages'])->findOrFail($id);
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

        return view('admin.product.edit', compact( 'product', 'categories', 'subCategories', 'subSubCategories', 'brands', 'models', 'groups', 'units', 'sizes', 'colors', 'types', 'tags'
        ));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:products,code,' . $id,
            'category' => 'required|array|min:1',
            'category.*' => 'required|integer|exists:categories,id',
            'subcategory' => 'nullable|array',
            'subcategory.*' => 'nullable|integer|exists:sub_categories,id',
            'subsubcategory' => 'nullable|array',
            'subsubcategory.*' => 'nullable|integer|exists:sub_sub_categories,id',
            'feature_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'gallery_images.*.file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'meta_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'small_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'colour_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'sm_colour_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'tags' => 'nullable|array',
            'tags.*' => 'nullable|integer|exists:tags,id',
        ]);

        $product = DB::transaction(function () use ($request, $id) {
            $product = Product::findOrFail($id);

            // Update basic fields
            $fields = [
                'name', 'code', 'short_description', 'long_description', 'brand_id', 'unit_id', 'group_id',
                'price', 'discount_percent', 'meta_title', 'meta_description', 'meta_keywords', 'company',
                'category_api', 'product_code_api', 'product_name_api', 'full_description', 'short_code',
                'tariff_no', 'ean', 'wash_degrees', 'gender', 'gsm', 'composition', 'specifications',
                'colour_code', 'colour_name_api', 'pantone', 'hex_code', 'size_name_api', 'price_single',
                'qty_single', 'price_pack', 'pack_qty', 'price_caton', 'carton_qty', 'price_1k', 'quantity_api',
                'my_price', 'video_link', 'packaging', 'country_of_origin', 'gross_weight', 'net_weight', 'tax_code'
            ];

            foreach ($fields as $field) {
                if ($request->has($field)) {
                    $product->$field = $request->$field;
                }
            }

            // Handle checkbox fields
            $checkboxFields = [
                'is_featured', 'is_trending', 'is_new_arrival', 'is_top_rated', 'is_popular', 'is_recent'
            ];

            foreach ($checkboxFields as $field) {
                $product->$field = $request->has($field) ? 1 : 0;
            }

            // Handle feature image
            if ($request->hasFile('feature_image')) {
                // Delete old feature image
                if ($product->feature_image && file_exists(public_path('images/products/' . $product->feature_image))) {
                    unlink(public_path('images/products/' . $product->feature_image));
                }

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
            } elseif ($request->has('remove_feature_image')) {
                // Remove feature image if checkbox is checked
                if ($product->feature_image && file_exists(public_path('images/products/' . $product->feature_image))) {
                    unlink(public_path('images/products/' . $product->feature_image));
                }
                $product->feature_image = null;
            }

            // Handle meta image
            if ($request->hasFile('meta_image')) {
                // Delete old meta image
                if ($product->meta_image && file_exists(public_path('images/products/' . $product->meta_image))) {
                    unlink(public_path('images/products/' . $product->meta_image));
                }

                $uploadedFile = $request->file('meta_image');
                $randomName = 'meta_' . mt_rand(10000000, 99999999) . '.webp';
                $destinationPath = public_path('images/products/');
                if (!file_exists($destinationPath)) mkdir($destinationPath, 0755, true);

                Image::make($uploadedFile)
                    ->resize(800, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                    ->encode('webp', 50)
                    ->save($destinationPath . $randomName);

                $product->meta_image = $randomName;
            } elseif ($request->has('remove_meta_image')) {
                // Remove meta image if checkbox is checked
                if ($product->meta_image && file_exists(public_path('images/products/' . $product->meta_image))) {
                    unlink(public_path('images/products/' . $product->meta_image));
                }
                $product->meta_image = null;
            }

            // Handle other images
            $imageFields = ['image', 'small_image', 'colour_image', 'sm_colour_image'];
            foreach ($imageFields as $imageField) {
                if ($request->hasFile($imageField)) {
                    // Delete old image if exists
                    if ($product->$imageField && file_exists(public_path('images/products/' . $product->$imageField))) {
                        unlink(public_path('images/products/' . $product->$imageField));
                    }

                    $uploadedFile = $request->file($imageField);
                    $randomName = $imageField . '_' . mt_rand(10000000, 99999999) . '.webp';
                    $destinationPath = public_path('images/products/');
                    if (!file_exists($destinationPath)) mkdir($destinationPath, 0755, true);

                    Image::make($uploadedFile)
                        ->resize(800, null, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        })
                        ->encode('webp', 50)
                        ->save($destinationPath . $randomName);

                    $product->$imageField = $randomName;
                }
            }

            // Generate slug if name changed
            if ($product->isDirty('name')) {
                $baseSlug = Str::slug($product->name);
                $slug = $baseSlug;
                $counter = 1;

                while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }

                $product->slug = $slug;
            }

            $product->updated_by = auth()->id();
            $product->save();

            // Handle tags
            if ($request->has('tags')) {
                $product->tags()->sync($request->tags);
            } else {
                $product->tags()->detach();
            }

            // Handle existing gallery images - update colors and remove selected images
            if ($request->has('existing_image_colors')) {
                foreach ($request->existing_image_colors as $imageId => $colorId) {
                    $productImage = ProductImage::where('id', $imageId)
                        ->where('product_id', $product->id)
                        ->first();
                    
                    if ($productImage) {
                        $productImage->color_id = $colorId ?: null;
                        $productImage->updated_by = auth()->id();
                        $productImage->save();
                    }
                }
            }

            // Remove selected gallery images
            if ($request->has('remove_images')) {
                foreach ($request->remove_images as $imageId) {
                    $productImage = ProductImage::where('id', $imageId)
                        ->where('product_id', $product->id)
                        ->first();
                    
                    if ($productImage) {
                        // Delete physical file
                        if ($productImage->image && file_exists(public_path('images/products/' . $productImage->image))) {
                            unlink(public_path('images/products/' . $productImage->image));
                        }
                        $productImage->delete();
                    }
                }
            }

            // Handle new gallery images
            if ($request->has('gallery_images')) {
                $destinationPath = public_path('images/products/');
                if (!file_exists($destinationPath)) mkdir($destinationPath, 0755, true);

                foreach ($request->gallery_images as $img) {
                    if (isset($img['file']) && $img['file'] instanceof \Illuminate\Http\UploadedFile) {
                        $imageFile = $img['file'];
                        $color = $img['color'] ?? null;

                        $imageName = 'gallery_' . mt_rand(10000000, 99999999) . '.webp';
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
            }

            // Handle categories - first remove all existing category relationships
            DB::table('category_products')
                ->where('product_id', $product->id)
                ->delete();

            // Add new category relationships
            $categories = $request->category ?? [];
            $subcategories = $request->subcategory ?? [];
            $subsubcategories = $request->subsubcategory ?? [];

            for ($i = 0; $i < count($categories); $i++) {
                // Only insert if category is provided (it's required)
                if (!empty($categories[$i])) {
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
            }

            return $product;
        });

        return response()->json([
            'message' => 'Product updated successfully',
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
            $products = Product::where('product_source', 2)->with('company', 'category');

            return DataTables::of($products)
                ->addIndexColumn()
                ->addColumn('code', fn($row) => $row->product_code)
                ->addColumn('name', fn($row) => $row->name)
                ->addColumn('price', fn($row) => $row->price)
                ->addColumn('category', fn($row) => $row->category?->name ?? '')
                ->addColumn('company', fn($row) => $row->company?->name ?? '')
                ->addColumn('image', fn($row) => $row->feature_image ? '<img src="'.$row->feature_image.'" class="img-thumbnail" width="50">' : '')
                ->addColumn('more', fn($row) => '<a href="'.route('product.details', $row->id).'" class="btn btn-sm btn-info">View</a>')
                ->rawColumns(['image', 'more'])
                ->make(true);
        }

        return view('admin.product.api_index');
    }

    public function productDetails(Product $product)
    {
        $product->load('company', 'category', 'variants.color', 'variants.size', 'images');
        return view('admin.product.show', compact('product'));
    }

    public function updateImageType(Request $request)
    {
        $request->validate([
            'image_id' => 'required|exists:product_images,id',
            'image_type' => 'required|in:front,back,right,left',
        ]);

        $image = ProductImage::findOrFail($request->image_id);

        $exists = ProductImage::where('product_id', $image->product_id)
            ->where('image_type', $request->image_type)
            ->where('id', '!=', $image->id)
            ->exists();

        if ($exists) {
            return response()->json(['errors' => ['image_type' => ['This image type already exists for this product.']]], 422);
        }

        $image->update([
            'image_type' => $request->image_type
        ]);

        return response()->json(['success' => 'Image type updated successfully.']);
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
}