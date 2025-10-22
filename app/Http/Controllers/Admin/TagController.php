<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;
use DataTables;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $tags = Tag::select(['id','name','status','created_at'])->orderByDesc('id');
            return DataTables::of($tags)
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
                            <li><button class="dropdown-item deleteBtn" data-delete-url="'.route('tags.delete',$row->id).'" data-method="DELETE" data-table="#tagTable"><i class="ri-delete-bin-fill me-2"></i>Delete</button></li>
                          </ul>
                        </div>';
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }

        return view('admin.tags.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:tags,name',
        ]);

        $tag = new Tag();
        $tag->name = $request->name;
        $tag->slug = Str::slug($request->name); // auto-generate slug
        $tag->status = 1;
        $tag->created_by = auth()->id();
        $tag->save();

        return response()->json(['message'=>'Tag created successfully.']);
    }

    public function edit($id)
    {
        $tag = Tag::findOrFail($id);
        return response()->json($tag);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:tags,id',
            'name' => 'required|unique:tags,name,'.$request->id,
        ]);

        $tag = Tag::findOrFail($request->id);
        $tag->name = $request->name;
        $tag->slug = Str::slug($request->name); // auto-generate slug
        $tag->updated_by = auth()->id();
        $tag->save();

        return response()->json(['message'=>'Tag updated successfully.']);
    }

    public function destroy($id)
    {
        $tag = Tag::find($id);
        if(!$tag) return response()->json(['message'=>'Not found'],404);
        $tag->delete();
        return response()->json(['message'=>'Tag deleted successfully.']);
    }

    public function toggleStatus(Request $request)
    {
        $tag = Tag::find($request->id);
        if(!$tag) return response()->json(['message'=>'Not found'],404);
        $tag->status = $request->status;
        $tag->save();
        return response()->json(['message'=>'Status updated successfully.']);
    }
}