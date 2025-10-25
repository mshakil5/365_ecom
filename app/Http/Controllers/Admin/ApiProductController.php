<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ApiProduct;
use DataTables;

class ApiProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ApiProduct::select(['id', 'company', 'url', 'description', 'status', 'created_at'])
                ->orderByDesc('id');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    $checked = $row->status ? 'checked' : '';
                    return '<div class="form-check form-switch">
                                <input class="form-check-input toggle-status" data-id="'.$row->id.'" type="checkbox" '.$checked.'>
                            </div>';
                })
                ->addColumn('action', function ($row) {
                    return '
                        <div class="dropdown">
                            <button class="btn btn-soft-secondary btn-sm" data-bs-toggle="dropdown">
                                <i class="ri-more-fill"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><button class="dropdown-item EditBtn" data-id="'.$row->id.'">
                                    <i class="ri-pencil-fill me-2"></i>Edit
                                </button></li>
                                <li class="dropdown-divider"></li>
                                <li><button class="dropdown-item deleteBtn" data-delete-url="'.route('api_products.delete', $row->id).'" data-method="DELETE">
                                    <i class="ri-delete-bin-fill me-2"></i>Delete
                                </button></li>
                            </ul>
                        </div>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.api_product.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'company' => 'required|unique:api_products,company',
            'url' => 'required|url',
        ]);

        ApiProduct::create([
            'company' => $request->company,
            'url' => $request->url,
            'description' => $request->description,
            'status' => 1,
            'created_by' => auth()->id(),
        ]);

        return response()->json(['message' => 'API Source created successfully.']);
    }

    public function edit($id)
    {
        return response()->json(ApiProduct::findOrFail($id));
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:api_products,id',
            'company' => 'required|unique:api_products,company,'.$request->id,
            'url' => 'required|url',
        ]);

        $data = ApiProduct::findOrFail($request->id);
        $data->update([
            'company' => $request->company,
            'url' => $request->url,
            'description' => $request->description,
            'updated_by' => auth()->id(),
        ]);

        return response()->json(['message' => 'API Source updated successfully.']);
    }

    public function destroy($id)
    {
        $data = ApiProduct::find($id);
        if (!$data) return response()->json(['message' => 'Not found.'], 404);
        $data->delete();
        return response()->json(['message' => 'Deleted successfully.']);
    }

    public function toggleStatus(Request $request)
    {
        $data = ApiProduct::find($request->id);
        if (!$data) return response()->json(['message' => 'Not found.'], 404);
        $data->status = $request->status;
        $data->save();
        return response()->json(['message' => 'Status updated successfully.']);
    }
}