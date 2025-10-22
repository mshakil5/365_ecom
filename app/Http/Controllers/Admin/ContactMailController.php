<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactEmail;
use DataTables;

class ContactMailController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $mails = ContactEmail::latest();
            return DataTables::of($mails)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    return '
                        <div class="dropdown">
                            <button class="btn btn-soft-secondary btn-sm" data-bs-toggle="dropdown"><i class="ri-more-fill"></i></button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><button class="dropdown-item editBtn" data-id="'.$row->id.'"><i class="ri-pencil-fill me-2"></i>Edit</button></li>
                                <li class="dropdown-divider"></li>
                                <li>
                                    <button class="dropdown-item deleteBtn" 
                                        data-delete-url="'.route('contactemails.destroy', $row->id).'" 
                                        data-method="DELETE" 
                                        data-table="#contactTable">
                                        <i class="ri-delete-bin-fill me-2"></i>Delete
                                    </button>
                                </li>
                            </ul>
                        </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.contact_email.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:contact_emails,email',
            'email_holder' => 'required|string|max:255',
        ]);

        ContactEmail::create([
            'email' => $request->email,
            'email_holder' => $request->email_holder,
            'created_by' => auth()->id(),
        ]);

        return response()->json(['message' => 'Contact mail created successfully.']);
    }

    public function edit($id)
    {
        return response()->json(ContactEmail::findOrFail($id));
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:contact_emails,id',
            'email' => 'required|email|unique:contact_emails,email,'.$request->id,
            'email_holder' => 'required|string|max:255',
        ]);

        $mail = ContactEmail::findOrFail($request->id);
        $mail->update([
            'email' => $request->email,
            'email_holder' => $request->email_holder,
            'updated_by' => auth()->id(),
        ]);

        return response()->json(['message' => 'Contact mail updated successfully.']);
    }

    public function destroy($id)
    {
        $mail = ContactEmail::findOrFail($id);
        $mail->delete();
        return response()->json(['message' => 'Contact mail deleted successfully.']);
    }
}