<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubCategory;
use App\Models\SubSubCategory;
use DataTables;
use Illuminate\Support\Str;
use Image;

class SubSubCategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $subsubcategories = SubSubCategory::with(['subCategory', 'subCategory.category'])->orderBy('serial');
            return DataTables::of($subsubcategories)
                ->addIndexColumn()
                ->addColumn('sub_category', fn($row) => $row->subCategory->name ?? '')
                ->addColumn('category', fn($row) => $row->subCategory->category->name ?? '')
                ->addColumn('image', fn($row) => $row->image ? '<img src="'.asset('images/subsubcategory/'.$row->image).'" class="img-thumbnail" style="width:50px;height:50px;">' : '')
                ->addColumn('status', function ($row) {
                    $checked = $row->status == 1 ? 'checked' : '';
                    return '<div class="form-check form-switch">
                        <input type="checkbox" class="form-check-input toggle-status" data-id="'.$row->id.'" '.$checked.'>
                        <label class="form-check-label"></label>
                    </div>';
                })
                ->addColumn('serial', fn($row) => '<span class="serial-text">'.$row->serial.'</span>')
                ->addColumn('action', function ($row) {
                    return '
                        <div class="dropdown">
                            <button class="btn btn-soft-secondary btn-sm dropdown" data-bs-toggle="dropdown"><i class="ri-more-fill"></i></button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <button class="dropdown-item EditBtn" data-id="'.$row->id.'"><i class="ri-pencil-fill me-2"></i>Edit</button>
                                </li>
                                <li class="dropdown-divider"></li>
                                <li>
                                    <button class="dropdown-item deleteBtn" data-delete-url="'.route('subsubcategories.delete',$row->id).'" data-method="DELETE" data-table="#subSubCategoryTable"><i class="ri-delete-bin-fill me-2"></i>Delete</button>
                                </li>
                            </ul>
                        </div>';
                })
                ->rawColumns(['image','status','serial','action'])
                ->make(true);
        }

        $subcategories = SubCategory::with('subSubCategories')->orderBy('serial')->get();
        return view('admin.subsubcategory.index', compact('subcategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sub_category_id' => 'required',
            'name' => 'required|unique:sub_sub_categories,name',
        ]);

        $subsub = new SubSubCategory();
        $subsub->sub_category_id = $request->sub_category_id;
        $subsub->name = $request->name;
        $subsub->slug = Str::slug($request->name);
        $subsub->description = $request->description;
        $subsub->serial = SubSubCategory::max('serial') + 1;
        $subsub->created_by = auth()->id();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = mt_rand(10000000,99999999).'.webp';
            if(!file_exists(public_path('images/subsubcategory'))) mkdir(public_path('images/subsubcategory'),0755,true);
            Image::make($file)->resize(800,null,function($c){$c->aspectRatio();})->encode('webp',50)->save(public_path('images/subsubcategory/'.$name));
            $subsub->image = $name;
        }

        $subsub->save();
        return response()->json(['message'=>'Sub-Sub-Category created successfully']);
    }

    public function edit($id)
    {
        $subsub = SubSubCategory::findOrFail($id);
        return response()->json($subsub);
    }

    public function update(Request $request)
    {
        $request->validate([
            'sub_category_id' => 'required',
            'name' => 'required|unique:sub_sub_categories,name,'.$request->id,
        ]);

        $subsub = SubSubCategory::findOrFail($request->id);
        $subsub->sub_category_id = $request->sub_category_id;
        $subsub->name = $request->name;
        $subsub->slug = Str::slug($request->name);
        $subsub->description = $request->description;
        $subsub->updated_by = auth()->id();

        if ($request->hasFile('image')) {
            if($subsub->image && file_exists(public_path('images/subsubcategory/'.$subsub->image))) @unlink(public_path('images/subsubcategory/'.$subsub->image));
            $file = $request->file('image');
            $name = mt_rand(10000000,99999999).'.webp';
            Image::make($file)->resize(800,null,function($c){$c->aspectRatio();})->encode('webp',50)->save(public_path('images/subsubcategory/'.$name));
            $subsub->image = $name;
        }

        $subsub->save();
        return response()->json(['message'=>'Sub-Sub-Category updated successfully']);
    }

    public function destroy($id)
    {
        $subsub = SubSubCategory::findOrFail($id);
        if($subsub->image && file_exists(public_path('images/subsubcategory/'.$subsub->image))) @unlink(public_path('images/subsubcategory/'.$subsub->image));
        $subsub->delete();
        return response()->json(['message'=>'Sub-Sub-Category deleted successfully']);
    }

    public function toggleStatus(Request $request)
    {
        $subsub = SubSubCategory::findOrFail($request->id);
        $subsub->status = $request->status;
        $subsub->save();
        return response()->json(['message'=>'Status updated successfully']);
    }


    public function updateSerial(Request $request)
    {
        $subCategoryId = $request->sub_category_id;
        foreach ($request->order as $index => $subId) {
            SubSubCategory::where('id', $subId)
                ->where('sub_category_id', $subCategoryId)
                ->update(['serial' => $index + 1]);
        }
        return response()->json(['message' => 'Sub-Sub-Category order updated successfully']);
    }
}