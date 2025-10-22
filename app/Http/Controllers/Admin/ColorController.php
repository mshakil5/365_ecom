<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Color;
use DataTables;

class ColorController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $colors = Color::select(['id','name','code','status','created_at'])->orderByDesc('id');
            return DataTables::of($colors)
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
                            <li><button class="dropdown-item deleteBtn" data-delete-url="'.route('colors.delete',$row->id).'"><i class="ri-delete-bin-fill me-2"></i>Delete</button></li>
                          </ul>
                        </div>';
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }

        return view('admin.color.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:colors,name',
            'code' => 'nullable|string|max:20',
        ]);

        $color = new Color();
        $color->name = $request->name;
        $color->code = $request->code;
        $color->status = 1;
        $color->created_by = auth()->id();
        $color->save();

        return response()->json(['message' => 'Color created successfully.']);
    }

    public function edit($id)
    {
        $color = Color::findOrFail($id);
        return response()->json($color);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:colors,id',
            'name' => 'required|unique:colors,name,'.$request->id,
            'code' => 'nullable|string|max:20',
        ]);

        $color = Color::findOrFail($request->id);
        $color->name = $request->name;
        $color->code = $request->code;
        $color->updated_by = auth()->id();
        $color->save();

        return response()->json(['message' => 'Color updated successfully.']);
    }

    public function destroy($id)
    {
        $color = Color::find($id);
        if (!$color) return response()->json(['message'=>'Not found'],404);
        $color->delete();
        return response()->json(['message'=>'Color deleted successfully']);
    }

    public function toggleStatus(Request $request)
    {
        $color = Color::find($request->id);
        if (!$color) return response()->json(['message'=>'Not found'],404);
        $color->status = $request->status;
        $color->save();
        return response()->json(['message'=>'Status updated successfully']);
    }
}