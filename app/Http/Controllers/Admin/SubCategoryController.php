<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubCategory;
use App\Models\Category;
use DataTables;
use Illuminate\Support\Str;
use Image;

class SubCategoryController extends Controller
{
    public function getSubCategory(Request $request)
    {
        if ($request->ajax()) {
            $subcategories = SubCategory::with('category')->orderBy('serial');

            return DataTables::of($subcategories)
                ->addIndexColumn()
                ->addColumn('category', fn($row) => $row->category->name ?? '')
                ->addColumn('image', function ($row) {
                    return $row->image ? '<img src="'.asset('images/subcategory/'.$row->image).'" class="img-thumbnail" style="width:50px;height:50px;">' : '';
                })
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
                                    <button class="dropdown-item deleteBtn" data-delete-url="'.route('subcategories.delete',$row->id).'" data-method="DELETE" data-table="#subCategoryTable"><i class="ri-delete-bin-fill me-2"></i>Delete</button>
                                </li>
                            </ul>
                        </div>';
                })
                ->rawColumns(['image','status','serial','action'])
                ->make(true);
        }

        $categories = Category::orderBy('serial')->get();
        return view('admin.subcategory.index', compact('categories'));
    }

    public function subCategoryStore(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:sub_categories,name',
            'category_id' => 'required',
        ]);

        $sub = new SubCategory();
        $sub->name = $request->name;
        $sub->slug = Str::slug($request->name);
        $sub->category_id = $request->category_id;
        $sub->description = $request->description;
        $sub->meta_title = $request->meta_title;
        $sub->meta_description = $request->meta_description;
        $sub->meta_keywords = $request->meta_keywords;
        $sub->created_by = auth()->id();
        $sub->serial = SubCategory::max('serial') + 1;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = mt_rand(10000000, 99999999).'.webp';
            if(!file_exists(public_path('images/subcategory'))) mkdir(public_path('images/subcategory'),0755,true);
            Image::make($file)->resize(800,null,function($c){$c->aspectRatio();})->encode('webp',50)->save(public_path('images/subcategory/'.$name));
            $sub->image = $name;
        }

        $sub->save();
        return response()->json(['message'=>'SubCategory created successfully']);
    }

    public function subCategoryEdit($id)
    {
        $sub = SubCategory::findOrFail($id);
        return response()->json($sub);
    }

    public function subCategoryUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:sub_categories,name,'.$request->id,
            'category_id' => 'required',
        ]);

        $sub = SubCategory::findOrFail($request->id);
        $sub->name = $request->name;
        $sub->slug = Str::slug($request->name);
        $sub->category_id = $request->category_id;
        $sub->description = $request->description;
        $sub->meta_title = $request->meta_title;
        $sub->meta_description = $request->meta_description;
        $sub->meta_keywords = $request->meta_keywords;
        $sub->updated_by = auth()->id();

        if ($request->hasFile('image')) {
            if ($sub->image && file_exists(public_path('images/subcategory/'.$sub->image))) @unlink(public_path('images/subcategory/'.$sub->image));
            $file = $request->file('image');
            $name = mt_rand(10000000, 99999999).'.webp';
            Image::make($file)->resize(800,null,function($c){$c->aspectRatio();})->encode('webp',50)->save(public_path('images/subcategory/'.$name));
            $sub->image = $name;
        }

        $sub->save();
        return response()->json(['message'=>'SubCategory updated successfully']);
    }

    public function subCategoryDelete($id)
    {
        $sub = SubCategory::findOrFail($id);
        if ($sub->image && file_exists(public_path('images/subcategory/'.$sub->image))) @unlink(public_path('images/subcategory/'.$sub->image));
        $sub->delete();
        return response()->json(['message'=>'SubCategory deleted successfully']);
    }

    public function toggleStatus(Request $request)
    {
        $sub = SubCategory::findOrFail($request->id);
        $sub->status = $request->status;
        $sub->save();
        return response()->json(['message'=>'Status updated successfully']);
    }

    public function updateSerial(Request $request)
    {
        $categoryId = $request->category_id;
        $order = $request->order;

        foreach ($order as $index => $subId) {
            SubCategory::where('id', $subId)
                ->where('category_id', $categoryId)
                ->update(['serial' => $index + 1]);
        }

        return response()->json(['message' => 'SubCategory order updated successfully']);
    }

}