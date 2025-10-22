<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Size;
use DataTables;

class SizeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $sizes = Size::select(['id','name','status','created_at'])->orderByDesc('id');
            return DataTables::of($sizes)
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
                                <li><button class="dropdown-item EditBtn" data-id="'.$row->id.'">
                                    <i class="ri-pencil-fill me-2"></i>Edit
                                </button></li>
                                <li class="dropdown-divider"></li>
                                <li><button class="dropdown-item deleteBtn" data-delete-url="'.route('sizes.delete',$row->id).'" data-method="DELETE">
                                    <i class="ri-delete-bin-fill me-2"></i>Delete
                                </button></li>
                            </ul>
                        </div>';
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }

        return view('admin.size.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:sizes,name',
        ], [
            'name.required' => 'Size name is required.',
            'name.unique' => 'This size already exists.',
        ]);

        $size = new Size();
        $size->name = $request->name;
        $size->status = 1;
        $size->created_by = auth()->id();
        $size->save();

        return response()->json(['message' => 'Size created successfully.']);
    }

    public function edit($id)
    {
        $size = Size::findOrFail($id);
        return response()->json($size);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:sizes,id',
            'name' => 'required|unique:sizes,name,'.$request->id,
        ], [
            'name.required' => 'Size name is required.',
            'name.unique' => 'This size already exists.',
        ]);

        $size = Size::findOrFail($request->id);
        $size->name = $request->name;
        $size->updated_by = auth()->id();
        $size->save();

        return response()->json(['message' => 'Size updated successfully.']);
    }

    public function destroy($id)
    {
        $size = Size::find($id);
        if (!$size) return response()->json(['message' => 'Not found.'], 404);
        $size->delete();
        return response()->json(['message' => 'Size deleted successfully.']);
    }

    public function toggleStatus(Request $request)
    {
        $size = Size::find($request->id);
        if (!$size) return response()->json(['message' => 'Not found.'], 404);
        $size->status = $request->status;
        $size->save();
        return response()->json(['message' => 'Status updated successfully.']);
    }
}