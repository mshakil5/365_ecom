<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;
use DataTables;
use Illuminate\Support\Str;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $groups = Group::select(['id','name','slug','status','created_at'])->orderByDesc('id');
            return DataTables::of($groups)
                ->addIndexColumn()
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
                            <li><button class="dropdown-item deleteBtn" data-delete-url="'.route('groups.delete',$row->id).'" data-method="DELETE" data-table="#groupTable"><i class="ri-delete-bin-fill me-2"></i>Delete</button></li>
                          </ul>
                        </div>';
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }

        return view('admin.group.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:groups,name',
        ], [
            'name.required' => 'Group name is required.',
            'name.unique' => 'This group already exists.',
        ]);

        $group = new Group();
        $group->name = $request->name;
        $group->slug = Str::slug($request->name);
        $group->status = 1;
        $group->created_by = auth()->id();

        $group->save();
        return response()->json(['message' => 'Group created successfully.', 'group' => $group]);
    }

    public function edit($id)
    {
        $group = Group::findOrFail($id);
        return response()->json($group);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:groups,id',
            'name' => 'required|unique:groups,name,'.$request->id,
        ], [
            'name.required' => 'Group name is required.',
            'name.unique' => 'This group already exists.',
        ]);

        $group = Group::findOrFail($request->id);
        $group->name = $request->name;
        $group->slug = Str::slug($request->name);
        $group->updated_by = auth()->id();
        $group->save();

        return response()->json(['message' => 'Group updated successfully.']);
    }

    public function destroy($id)
    {
        $group = Group::find($id);
        if (!$group) return response()->json(['message' => 'Not found.'], 404);
        $group->delete();
        return response()->json(['message' => 'Group deleted successfully.']);
    }

    public function toggleStatus(Request $request)
    {
        $group = Group::find($request->id);
        if (!$group) return response()->json(['message' => 'Not found.'], 404);
        $group->status = $request->status;
        $group->save();
        return response()->json(['message' => 'Status updated successfully.']);
    }
}
