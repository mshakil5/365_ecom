<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use DataTables;
use Intervention\Image\Facades\Image;

class CategoryController extends Controller
{
    public function getCategory(Request $request)
    {

        if ($request->ajax()) {
            $categories = Category::select(['id', 'name', 'image', 'serial', 'status'])->orderBy('serial');
            
            return DataTables::of($categories)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    return $row->image
                        ? '<img src="'.asset('images/category/'.$row->image).'" class="img-thumbnail" style="width:50px;height:50px;">'
                        : '';
                })
                ->addColumn('status', function ($row) {
                    $checked = $row->status == 1 ? 'checked' : '';
                    return '<div class="form-check form-switch" dir="ltr">
                                <input type="checkbox" class="form-check-input toggle-status" 
                                      id="customSwitchStatus'.$row->id.'" data-id="'.$row->id.'" '.$checked.'>
                                <label class="form-check-label" for="customSwitchStatus'.$row->id.'"></label>
                            </div>';
                })
                ->addColumn('serial', function ($row) {
                    return '<span class="serial-text">'.$row->serial.'</span>';
                })
                ->addColumn('action', function ($row) {
                    return '
                        <div class="dropdown">
                            <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ri-more-fill align-middle"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <button class="dropdown-item" id="EditBtn" rid="'.$row->id.'">
                                        <i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit
                                    </button>
                                </li>
                                <li class="dropdown-divider"></li>
                                <li>
                                    <button class="dropdown-item deleteBtn" 
                                            data-delete-url="' . route('category.delete', $row->id) . '" 
                                            data-method="DELETE" 
                                            data-table="#categoryTable">
                                        <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete
                                    </button>
                                </li>
                            </ul>
                        </div>
                    ';
                })
                ->rawColumns(['image', 'status', 'serial', 'action'])
                ->make(true);
        }

        $categories = Category::orderBy('serial')->get();

        return view('admin.category.index', compact('categories'));
    }

    public function categoryStore(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories,name',
        ], [
            'name.required' => 'Category name is required.',
            'name.unique' => 'This category already exists.',
        ]);
        
        $data = new Category;
        $data->name = $request->name;
        $data->description = $request->description;
        $data->meta_title = $request->meta_title;
        $data->meta_description = $request->meta_description;
        $data->meta_keywords = $request->meta_keywords;
        $data->slug = Str::slug($request->name);
        $data->created_by = auth()->id(); 

        $lastSerial = Category::max('serial');

        $data->serial = $lastSerial ? $lastSerial + 1 : 1;

        if ($request->hasFile('image')) {
            $uploadedFile = $request->file('image');
            $randomName = mt_rand(10000000, 99999999) . '.webp';
            $destinationPath = public_path('images/category/');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            Image::make($uploadedFile)
                ->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->encode('webp', 50)
                ->save($destinationPath . $randomName);

            $data->image = $randomName;
        }
        
        if ($data->save()) {
            return response()->json([
                'message' => 'Category created successfully!',
                'category' => $data 
            ], 200);
        }

        return response()->json([
            'message' => 'Server error while creating category.'
        ], 500);

    }

    public function categoryEdit($id)
    {
        $where = [
            'id'=>$id
        ];
        $info = Category::where($where)->get()->first();
        return response()->json($info);
    }

    public function categoryUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories,name,' . $request->codeid,
        ], [
            'name.required' => 'Category name is required.',
            'name.unique' => 'This category already exists.',
        ]);

        $data = Category::findOrFail($request->codeid);
        $data->name = $request->name;
        $data->meta_title = $request->meta_title;
        $data->meta_description = $request->meta_description;
        $data->meta_keywords = $request->meta_keywords;
        $data->description = $request->description;
        $data->slug = Str::slug($request->name);
        $data->updated_by = auth()->id();

        if ($request->hasFile('image')) {
            $uploadedFile = $request->file('image');

            if ($data->image && file_exists(public_path('images/category/' . $data->image))) {
                @unlink(public_path('images/category/' . $data->image));
            }

            $randomName = mt_rand(10000000, 99999999) . '.webp';
            $destinationPath = public_path('images/category/');
            if (!file_exists($destinationPath)) mkdir($destinationPath, 0755, true);

            Image::make($uploadedFile)
                ->resize(800, null, fn($c) => $c->aspectRatio())
                ->encode('webp', 50)
                ->save($destinationPath . $randomName)
                ->destroy();

            $data->image = $randomName;
        }

        if ($data->save()) {
            return response()->json([
                'message' => 'Category updated successfully!'
            ], 200);
        }

        return response()->json([
            'message' => 'Failed to update category. Please try again.'
        ], 500);
    }

    public function categoryDelete($id)
    {
        $data = Category::find($id);
        
        if (!$data) {
            return response()->json([
                'message' => 'Category not found.'
            ], 404);
        }

        if ($data->image && file_exists(public_path('images/category/' . $data->image))) {
            @unlink(public_path('images/category/' . $data->image));
        }

        if ($data->delete()) {
            return response()->json([
                'message' => 'Category deleted successfully.'
            ], 200);
        }

        return response()->json([
            'message' => 'Failed to delete category.'
        ], 500);
    }

    public function toggleStatus(Request $request)
    {
        $category = Category::find($request->category_id);

        if (!$category) {
            return response()->json([
                'message' => 'Category not found'
            ], 404);
        }

        $category->status = $request->status;

        if ($category->save()) {
            return response()->json([
                'message' => 'Category status updated successfully'
            ], 200);
        }

        return response()->json([
            'message' => 'Failed to update category status'
        ], 500);
    }

    public function updateOrder(Request $request)
    {
        $order = $request->order;

        if (!is_array($order) || empty($order)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid data for sorting.'
            ], 400);
        }

        foreach ($order as $index => $id) {
            Category::where('id', $id)->update(['serial' => $index + 1]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Category order updated successfully!'
        ]);
    }

}