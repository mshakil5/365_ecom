<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Type;
use DataTables;

class TypeController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()){
            $types = Type::select(['id','name','status','created_at'])->orderByDesc('id');
            return DataTables::of($types)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    $checked = $row->status ? 'checked' : '';
                    return '<div class="form-check form-switch"><input class="form-check-input toggle-status" data-id="'.$row->id.'" type="checkbox" '.$checked.'></div>';
                })
                ->addColumn('action', function($row){
                    return '
                        <div class="dropdown">
                          <button class="btn btn-soft-secondary btn-sm" data-bs-toggle="dropdown">...</button>
                          <ul class="dropdown-menu dropdown-menu-end">
                            <li><button class="dropdown-item EditBtn" data-id="'.$row->id.'"><i class="ri-pencil-fill me-2"></i>Edit</button></li>
                            <li class="dropdown-divider"></li>
                            <li><button class="dropdown-item deleteBtn" data-delete-url="'.route('types.delete',$row->id).'"> <i class="ri-delete-bin-fill me-2"></i>Delete</button></li>
                          </ul>
                        </div>';
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }
        return view('admin.type.index');
    }

    public function store(Request $request)
    {
        $request->validate(['name'=>'required|unique:types,name']);
        $type = new Type();
        $type->name = $request->name;
        $type->status = 1;
        $type->created_by = auth()->id();
        $type->save();
        return response()->json(['message'=>'Type created successfully.']);
    }

    public function edit($id){
        return response()->json(Type::findOrFail($id));
    }

    public function update(Request $request){
        $request->validate([
            'id'=>'required|exists:types,id',
            'name'=>'required|unique:types,name,'.$request->id
        ]);
        $type = Type::findOrFail($request->id);
        $type->name = $request->name;
        $type->updated_by = auth()->id();
        $type->save();
        return response()->json(['message'=>'Type updated successfully.']);
    }

    public function destroy($id){
        $type = Type::find($id);
        if(!$type) return response()->json(['message'=>'Not found.'],404);
        $type->delete();
        return response()->json(['message'=>'Type deleted successfully.']);
    }

    public function toggleStatus(Request $request){
        $type = Type::find($request->id);
        if(!$type) return response()->json(['message'=>'Not found.'],404);
        $type->status = $request->status;
        $type->save();
        return response()->json(['message'=>'Status updated successfully.']);
    }
}