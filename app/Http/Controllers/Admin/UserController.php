<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()){
            $users = User::select(['id','name','email','phone','status','created_at'])->where('is_type',3)->orderByDesc('id');
            return DataTables::of($users)
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
                            <li><button class="dropdown-item deleteBtn" data-delete-url="'.route('user.delete',$row->id).'" data-method="DELETE" data-table="#userTable"><i class="ri-delete-bin-fill me-2"></i>Delete</button></li>
                          </ul>
                        </div>';
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }

        return view('admin.users.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email|unique:users,email',
            'phone'=>'required|string|max:20',
            'password'=>'required|string|min:6|confirmed',
        ]);

        User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'password'=>Hash::make($request->password),
            'is_type'=>3,
            'status'=>1,
        ]);

        return response()->json(['message'=>'User created successfully.']);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id'=>'required|exists:users,id',
            'name'=>'required|string|max:255',
            'email'=>'required|email|unique:users,email,'.$request->id,
            'phone'=>'required|string|max:20',
            'password'=>'nullable|string|min:6|confirmed',
        ]);

        $user = User::findOrFail($request->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        if($request->password) $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['message'=>'User updated successfully.']);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message'=>'User deleted successfully.']);
    }

    public function toggleStatus(Request $request)
    {
        $user = User::findOrFail($request->id);
        $user->status = $request->status;
        $user->save();
        return response()->json(['message'=>'Status updated successfully.']);
    }
}