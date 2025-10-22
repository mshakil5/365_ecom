<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Warranty;
use DataTables;

class WarrantyController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $warranties = Warranty::select(['id','warranty_duration','price_increase_percent','status','created_at'])->orderByDesc('id');
            return DataTables::of($warranties)
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
                            <li><button class="dropdown-item deleteBtn" data-delete-url="'.route('warranties.delete',$row->id).'" data-method="DELETE" data-table="#warrantyTable"><i class="ri-delete-bin-fill me-2"></i>Delete</button></li>
                          </ul>
                        </div>';
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }

        return view('admin.warranty.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'warranty_duration' => 'required|string|max:255',
            'price_increase_percent' => 'required|numeric',
        ]);

        $warranty = new Warranty();
        $warranty->warranty_duration = $request->warranty_duration;
        $warranty->price_increase_percent = $request->price_increase_percent;
        $warranty->status = 1;
        $warranty->created_by = auth()->id();
        $warranty->save();

        return response()->json(['message'=>'Warranty created successfully.']);
    }

    public function edit($id)
    {
        $warranty = Warranty::findOrFail($id);
        return response()->json($warranty);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:warranties,id',
            'warranty_duration' => 'required|string|max:255',
            'price_increase_percent' => 'required|numeric',
        ]);

        $warranty = Warranty::findOrFail($request->id);
        $warranty->warranty_duration = $request->warranty_duration;
        $warranty->price_increase_percent = $request->price_increase_percent;
        $warranty->updated_by = auth()->id();
        $warranty->save();

        return response()->json(['message'=>'Warranty updated successfully.']);
    }

    public function destroy($id)
    {
        $warranty = Warranty::find($id);
        if(!$warranty) return response()->json(['message'=>'Not found'],404);
        $warranty->delete();
        return response()->json(['message'=>'Warranty deleted successfully.']);
    }

    public function toggleStatus(Request $request)
    {
        $warranty = Warranty::find($request->id);
        if(!$warranty) return response()->json(['message'=>'Not found'],404);
        $warranty->status = $request->status;
        $warranty->save();
        return response()->json(['message'=>'Status updated successfully.']);
    }
}