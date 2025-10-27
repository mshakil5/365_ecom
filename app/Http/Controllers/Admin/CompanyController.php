<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use DataTables;
use Illuminate\Support\Str;
use Image;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $companies = Company::select(['id','name','image','slug','status','created_at'])->orderByDesc('id');
            return DataTables::of($companies)
                ->addIndexColumn()
                ->addColumn('image', fn($row) => $row->image ? '<img src="'.asset('images/companies/'.$row->image).'" style="width:50px;height:50px;" class="img-thumbnail">' : '')
                ->addColumn('status', fn($row) => '<div class="form-check form-switch"><input class="form-check-input toggle-status" data-id="'.$row->id.'" type="checkbox" '.($row->status ? 'checked' : '').'></div>')
                ->addColumn('action', function($row){
                    return '
                        <div class="dropdown">
                          <button class="btn btn-soft-secondary btn-sm" data-bs-toggle="dropdown"><i class="ri-more-fill"></i></button>
                          <ul class="dropdown-menu dropdown-menu-end">
                            <li><button class="dropdown-item EditBtn" data-id="'.$row->id.'"><i class="ri-pencil-fill me-2"></i>Edit</button></li>
                            <li class="dropdown-divider"></li>
                            <li><button class="dropdown-item deleteBtn" data-delete-url="'.route('companies.delete',$row->id).'" data-method="DELETE" data-table="#companyTable"><i class="ri-delete-bin-fill me-2"></i>Delete</button></li>
                          </ul>
                        </div>';
                })
                ->rawColumns(['image','status','action'])
                ->make(true);
        }

        return view('admin.companies.index');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:companies,name']);

        $company = new Company();
        $company->name = $request->name;
        $company->slug = Str::slug($request->name);
        $company->status = $request->status ?? 1;
        $company->created_by = auth()->id();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = mt_rand(10000000,99999999).'.webp';
            $dest = public_path('images/companies/');
            if (!file_exists($dest)) mkdir($dest,0755,true);
            Image::make($file)->resize(800, null, fn($c)=> $c->aspectRatio())->encode('webp',60)->save($dest.$name);
            $company->image = $name;
        }

        $company->save();
        return response()->json(['message'=>'Company created successfully.', 'company'=>$company], 201);
    }

    public function edit($id)
    {
        $company = Company::findOrFail($id);
        return response()->json($company);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id'=>'required|exists:companies,id',
            'name'=>'required|unique:companies,name,'.$request->id
        ]);

        $company = Company::findOrFail($request->id);
        $company->name = $request->name;
        $company->slug = Str::slug($request->name);
        $company->updated_by = auth()->id();

        if ($request->hasFile('image')) {
            if ($company->image && file_exists(public_path('images/companies/'.$company->image))) {
                @unlink(public_path('images/companies/'.$company->image));
            }
            $file = $request->file('image');
            $name = mt_rand(10000000,99999999).'.webp';
            $dest = public_path('images/companies/');
            if (!file_exists($dest)) mkdir($dest,0755,true);
            Image::make($file)->resize(800, null, fn($c)=> $c->aspectRatio())->encode('webp',60)->save($dest.$name);
            $company->image = $name;
        }

        $company->save();
        return response()->json(['message'=>'Company updated successfully.']);
    }

    public function destroy($id)
    {
        $company = Company::find($id);
        if (!$company) return response()->json(['message'=>'Not found'],404);

        if ($company->image && file_exists(public_path('images/companies/'.$company->image))) {
            @unlink(public_path('images/companies/'.$company->image));
        }

        $company->delete();
        return response()->json(['message'=>'Company deleted successfully.']);
    }

    public function toggleStatus(Request $request)
    {
        $company = Company::find($request->id);
        if (!$company) return response()->json(['message'=>'Not found'],404);
        $company->status = $request->status;
        $company->save();
        return response()->json(['message'=>'Status updated.']);
    }
}