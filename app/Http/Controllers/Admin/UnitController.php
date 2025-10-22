<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Unit;
use DataTables;
use Illuminate\Support\Str;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $units = Unit::select(['id','name','slug','status','created_at'])->orderByDesc('id');
            return DataTables::of($units)
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
                            <li><button class="dropdown-item deleteBtn" data-delete-url="'.route('units.delete',$row->id).'" data-method="DELETE" data-table="#unitTable"><i class="ri-delete-bin-fill me-2"></i>Delete</button></li>
                          </ul>
                        </div>';
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }

        return view('admin.unit.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:units,name',
        ]);

        $unit = new Unit();
        $unit->name = $request->name;
        $unit->slug = Str::slug($request->name); // auto-generate slug
        $unit->status = 1;
        $unit->created_by = auth()->id();
        $unit->save();

        return response()->json(['message'=>'Unit created successfully.']);
    }

    public function edit($id)
    {
        $unit = Unit::findOrFail($id);
        return response()->json($unit);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:units,id',
            'name' => 'required|unique:units,name,'.$request->id,
        ]);

        $unit = Unit::findOrFail($request->id);
        $unit->name = $request->name;
        $unit->slug = Str::slug($request->name); // auto-generate slug
        $unit->updated_by = auth()->id();
        $unit->save();

        return response()->json(['message'=>'Unit updated successfully.']);
    }

    public function destroy($id)
    {
        $unit = Unit::find($id);
        if(!$unit) return response()->json(['message'=>'Not found'],404);
        $unit->delete();
        return response()->json(['message'=>'Unit deleted successfully.']);
    }

    public function toggleStatus(Request $request)
    {
        $unit = Unit::find($request->id);
        if(!$unit) return response()->json(['message'=>'Not found'],404);
        $unit->status = $request->status;
        $unit->save();
        return response()->json(['message'=>'Status updated successfully.']);
    }
}