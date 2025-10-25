<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductPrice;
use App\Models\Product;
use DataTables;

class ProductPriceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $prices = ProductPrice::with('product')->orderByDesc('id');
            if ($request->has('product_id') && $request->product_id != '') {
                $prices->where('product_id', $request->product_id);
            }
            return DataTables::of($prices)
                ->addIndexColumn()
                ->addColumn('product', fn($row) => $row->product->name ?? 'N/A')
                ->addColumn('category', fn($row) => $row->category)
                ->addColumn('min_max_qty', fn($row) => $row->min_max_qty)
                ->addColumn('discount_percent', fn($row) => $row->discount_percent.'%')
                ->addColumn('status', function($row){
                    $checked = $row->status ? 'checked' : '';
                    return '<div class="form-check form-switch">
                                <input class="form-check-input toggle-status" data-id="'.$row->id.'" type="checkbox" '.$checked.'>
                            </div>';
                })
                ->addColumn('action', function($row){
                    return '
                        <div class="dropdown">
                          <button class="btn btn-soft-secondary btn-sm" data-bs-toggle="dropdown"><i class="ri-more-fill"></i></button>
                          <ul class="dropdown-menu dropdown-menu-end">
                            <li><button class="dropdown-item EditBtn" data-id="'.$row->id.'"><i class="ri-pencil-fill me-2"></i>Edit</button></li>
                            <li class="dropdown-divider"></li>
                            <li><button class="dropdown-item deleteBtn" data-delete-url="'.route('product_prices.destroy',$row->id).'" data-method="DELETE" data-table="#priceTable"><i class="ri-delete-bin-fill me-2"></i>Delete</button></li>
                          </ul>
                        </div>';
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }

        $products = Product::where('status', 1)->get();

        $selectedProduct = null;
        if ($request->has('product_id') && $request->product_id != '') {
            $selectedProduct = Product::select('id', 'name')->find($request->product_id);
        }

        return view('admin.product_prices.index', compact('products', 'selectedProduct'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'category' => 'required|in:Blank pricing,Print,Embroidery,High stitch count',
            'min_max_qty' => 'nullable|string',
            'discount_percent' => 'nullable|integer|min:0|max:100',
        ]);

        $price = new ProductPrice();
        $price->product_id = $request->product_id;
        $price->category = $request->category;
        $price->min_max_qty = $request->min_max_qty;
        $price->discount_percent = $request->discount_percent;
        $price->status = $request->status ?? 1;
        $price->created_by = auth()->id();
        $price->save();

        return response()->json(['message' => 'Product price added.', 'price' => $price], 201);
    }

    public function edit($id)
    {
        $price = ProductPrice::findOrFail($id);
        return response()->json($price);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:product_prices,id',
            'product_id' => 'required|exists:products,id',
            'category' => 'required|in:Blank pricing,Print,Embroidery,High stitch count',
            'min_max_qty' => 'nullable|string',
            'discount_percent' => 'nullable|integer|min:0|max:100',
        ]);

        $price = ProductPrice::findOrFail($request->id);
        $price->product_id = $request->product_id;
        $price->category = $request->category;
        $price->min_max_qty = $request->min_max_qty;
        $price->discount_percent = $request->discount_percent;
        $price->updated_by = auth()->id();
        $price->save();

        return response()->json(['message' => 'Product price updated.'], 200);
    }

    public function destroy($id)
    {
        $price = ProductPrice::find($id);
        if (!$price) return response()->json(['message'=>'Not found'], 404);

        $price->delete();
        return response()->json(['message'=>'Deleted successfully'], 200);
    }

    public function toggleStatus(Request $request)
    {
        $price = ProductPrice::find($request->id);
        if (!$price) return response()->json(['message'=>'Not found'], 404);

        $price->status = $request->status;
        $price->save();
        return response()->json(['message'=>'Status updated'], 200);
    }
}