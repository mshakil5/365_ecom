<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductModel;
use DataTables;
use Illuminate\Support\Str;

class ProductModelController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $models = ProductModel::select(['id','name','slug','status','created_at'])->orderByDesc('id');
            return DataTables::of($models)
                ->addIndexColumn()
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
                                <li><button class="dropdown-item deleteBtn" data-delete-url="'.route('productmodel.delete',$row->id).'" data-method="DELETE" data-table="#productModelTable"><i class="ri-delete-bin-fill me-2"></i>Delete</button></li>
                            </ul>
                        </div>';
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }

        return view('admin.product-model.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:product_models,name',
        ]);

        ProductModel::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'created_by' => auth()->id()
        ]);

        return response()->json(['message' => 'Product Model created successfully.']);
    }

    public function edit($id)
    {
        return response()->json(ProductModel::findOrFail($id));
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:product_models,id',
            'name' => 'required|unique:product_models,name,'.$request->id,
        ]);

        $model = ProductModel::findOrFail($request->id);
        $model->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'updated_by' => auth()->id()
        ]);

        return response()->json(['message' => 'Product Model updated successfully.']);
    }

    public function destroy($id)
    {
        ProductModel::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted successfully.']);
    }

    public function toggleStatus(Request $request)
    {
        $model = ProductModel::findOrFail($request->id);
        $model->update(['status' => $request->status]);
        return response()->json(['message' => 'Status updated successfully.']);
    }
}