<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Partner;
use DataTables;
use Illuminate\Support\Str;
use Image;

class PartnerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $partners = Partner::select(['id','name','image','slug','status','created_at'])->orderByDesc('id');
            return DataTables::of($partners)
                ->addIndexColumn()
                ->addColumn('image', fn($row) => $row->image ? '<img src="'.asset('images/partners/'.$row->image).'" style="width:50px;height:50px;" class="img-thumbnail">' : '')
                ->addColumn('status', fn($row) => '<div class="form-check form-switch"><input class="form-check-input toggle-status" data-id="'.$row->id.'" type="checkbox" '.($row->status ? 'checked' : '').'></div>')
                ->addColumn('action', function($row){
                    return '
                        <div class="dropdown">
                          <button class="btn btn-soft-secondary btn-sm" data-bs-toggle="dropdown"><i class="ri-more-fill"></i></button>
                          <ul class="dropdown-menu dropdown-menu-end">
                            <li><button class="dropdown-item EditBtn" data-id="'.$row->id.'"><i class="ri-pencil-fill me-2"></i>Edit</button></li>
                            <li class="dropdown-divider"></li>
                            <li><button class="dropdown-item deleteBtn" data-delete-url="'.route('partners.delete',$row->id).'" data-method="DELETE" data-table="#partnerTable"><i class="ri-delete-bin-fill me-2"></i>Delete</button></li>
                          </ul>
                        </div>';
                })
                ->rawColumns(['image','status','action'])
                ->make(true);
        }

        return view('admin.partners.index');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:partners,name']);

        $partner = new Partner();
        $partner->name = $request->name;
        $partner->slug = Str::slug($request->name);
        $partner->status = $request->status ?? 1;
        $partner->created_by = auth()->id();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = mt_rand(10000000,99999999).'.webp';
            $dest = public_path('images/partners/');
            if (!file_exists($dest)) mkdir($dest,0755,true);
            Image::make($file)->resize(800, null, fn($c)=> $c->aspectRatio())->encode('webp',60)->save($dest.$name);
            $partner->image = $name;
        }

        $partner->save();
        return response()->json(['message'=>'Brand created successfully.', 'partner'=>$partner], 201);
    }

    public function edit($id)
    {
        $partner = Partner::findOrFail($id);
        return response()->json($partner);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id'=>'required|exists:partners,id',
            'name'=>'required|unique:partners,name,'.$request->id
        ]);

        $partner = Partner::findOrFail($request->id);
        $partner->name = $request->name;
        $partner->slug = Str::slug($request->name);
        $partner->updated_by = auth()->id();

        if ($request->hasFile('image')) {
            if ($partner->image && file_exists(public_path('images/partners/'.$partner->image))) {
                @unlink(public_path('images/partners/'.$partner->image));
            }
            $file = $request->file('image');
            $name = mt_rand(10000000,99999999).'.webp';
            $dest = public_path('images/partners/');
            if (!file_exists($dest)) mkdir($dest,0755,true);
            Image::make($file)->resize(800, null, fn($c)=> $c->aspectRatio())->encode('webp',60)->save($dest.$name);
            $partner->image = $name;
        }

        $partner->save();
        return response()->json(['message'=>'Brand updated successfully.']);
    }

    public function destroy($id)
    {
        $partner = Partner::find($id);
        if (!$partner) return response()->json(['message'=>'Not found'],404);

        if ($partner->image && file_exists(public_path('images/partners/'.$partner->image))) {
            @unlink(public_path('images/partners/'.$partner->image));
        }

        $partner->delete();
        return response()->json(['message'=>'Brand deleted successfully.']);
    }

    public function toggleStatus(Request $request)
    {
        $partner = Partner::find($request->id);
        if (!$partner) return response()->json(['message'=>'Not found'],404);
        $partner->status = $request->status;
        $partner->save();
        return response()->json(['message'=>'Status updated.']);
    }
}