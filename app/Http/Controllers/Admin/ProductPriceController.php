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
            $data = ProductPrice::with('product')
                ->select('product_prices.*')
                ->orderBy('product_id')
                ->orderBy('category');

            if ($request->has('product_id') && $request->product_id != '') {
                $data->where('product_id', $request->product_id);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('product', fn($r) => $r->product->name ?? 'N/A')
                ->addColumn('discount_percent', fn($r) => $r->discount_percent.'%')
                ->make(true);
        }

        $products = Product::where('status',1)->get();
        $selectedProduct = null;

        if ($request->has('product_id') && $request->product_id != '') {
            $selectedProduct = Product::find($request->product_id);
        }

        return view('admin.product_prices.index', compact('products', 'selectedProduct'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'category' => 'required|array|min:1',
            'category.*' => 'in:Blank pricing,Print,Embroidery,High stitch count',
            'min_qty.*' => 'nullable|integer|min:0',
            'max_qty.*' => 'nullable|integer|min:1',
            'discount_percent.*' => 'nullable|integer|min:0|max:100',
        ]);

        foreach ($request->category as $i => $cat) {
            $min = $request->min_qty[$i];
            $max = $request->max_qty[$i];
            if ($min !== null && $max !== null && $min >= $max) continue;

            ProductPrice::updateOrCreate(
                ['product_id'=>$request->product_id, 'category'=>$cat],
                [
                    'min_qty'=>$min,
                    'max_qty'=>$max,
                    'discount_percent'=>$request->discount_percent[$i],
                    'status'=>1,
                    'updated_by'=>auth()->id()
                ]
            );
        }

        return response()->json(['message'=>'Product prices saved successfully.']);
    }

    public function getByProduct($id)
    {
        return response()->json(ProductPrice::where('product_id',$id)->get());
    }
}