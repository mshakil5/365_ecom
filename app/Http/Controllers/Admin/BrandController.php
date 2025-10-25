<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use DataTables;
use Illuminate\Support\Str;
use Image;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $brands = Brand::select(['id','name','image','slug','status','created_at'])->orderByDesc('id');
            return DataTables::of($brands)
                ->addIndexColumn()
                ->addColumn('image', fn($row) => $row->image ? '<img src="'.asset('images/brand/'.$row->image).'" style="width:50px;height:50px;" class="img-thumbnail">' : '')
                ->addColumn('status', function($row){
                    $checked = $row->status ? 'checked' : '';
                    return '<div class="form-check form-switch"><input class="form-check-input toggle-status" data-id="'.$row->id.'" type="checkbox" '.$checked.'></div>';
                })
                ->addColumn('action', function($row){
                    return '
                        <div class="dropdown">
                          <button class="btn btn-soft-secondary btn-sm" data-bs-toggle="dropdown"><i class="ri-more-fill"></i></button>
                          <ul class="dropdown-menu dropdown-menu-end">
                            <li><button class="dropdown-item EditBtn" data-id="'.$row->id.'"><i class="ri-pencil-fill me-2"></i>Edit</button></li>
                            <li class="dropdown-divider"></li>
                            <li><button class="dropdown-item deleteBtn" data-delete-url="'.route('brands.delete',$row->id).'" data-method="DELETE" data-table="#brandTable"><i class="ri-delete-bin-fill me-2"></i>Delete</button></li>
                          </ul>
                        </div>';
                })
                ->rawColumns(['image','status','action'])
                ->make(true);
        }

        return view('admin.brand.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:brands,name',
        ], [
            'name.required' => 'Brand name is required.',
            'name.unique' => 'This brand already exists.',
        ]);

        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);
        $brand->status = $request->status ?? 1;
        $brand->created_by = auth()->id();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');
            $name = mt_rand(10000000,99999999).'.webp';
            $dest = public_path('images/brand/');
            if (!file_exists($dest)) mkdir($dest, 0755, true);
            Image::make($file)->resize(800, null, fn($c) => $c->aspectRatio())->encode('webp', 60)->save($dest.$name);
            $brand->image = $name;
        }

        if ($brand->save()) {
            return response()->json(['message' => 'Brand created successfully.','brand' => $brand ], 201);
        }

        return response()->json(['message' => 'Failed to create brand.'], 500);
    }

    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        return response()->json($brand);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:brands,id',
            'name' => 'required|unique:brands,name,'.$request->id,
        ], [
            'name.required' => 'Brand name is required.',
            'name.unique' => 'This brand already exists.',
        ]);

        $brand = Brand::findOrFail($request->id);
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);
        $brand->updated_by = auth()->id();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            // delete old
            if ($brand->image && file_exists(public_path('images/brand/'.$brand->image))) {
                @unlink(public_path('images/brand/'.$brand->image));
            }
            $file = $request->file('image');
            $name = mt_rand(10000000,99999999).'.webp';
            $dest = public_path('images/brand/');
            if (!file_exists($dest)) mkdir($dest,0755,true);
            Image::make($file)->resize(800, null, fn($c)=> $c->aspectRatio())->encode('webp',60)->save($dest.$name);
            $brand->image = $name;
        }

        if ($brand->save()) {
            return response()->json(['message' => 'Brand updated successfully.'], 200);
        }

        return response()->json(['message' => 'Failed to update brand.'], 500);
    }

    public function destroy($id)
    {
        $brand = Brand::find($id);
        if (!$brand) return response()->json(['message' => 'Not found.'], 404);

        if ($brand->image && file_exists(public_path('images/brand/'.$brand->image))) {
            @unlink(public_path('images/brand/'.$brand->image));
        }

        if ($brand->delete()) {
            return response()->json(['message' => 'Brand deleted successfully.'], 200);
        }

        return response()->json(['message' => 'Failed to delete.'], 500);
    }

    public function toggleStatus(Request $request)
    {
        $brand = Brand::find($request->id);
        if (!$brand) return response()->json(['message' => 'Not found.'], 404);
        $brand->status = $request->status;
        if ($brand->save()) return response()->json(['message' => 'Status updated.'], 200);
        return response()->json(['message' => 'Failed to update status.'], 500);
    }
}