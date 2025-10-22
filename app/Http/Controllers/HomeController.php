<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function dashboard()
    { 
        if (Auth::check()) {
            $user = auth()->user();

            if ($user->is_type == '1') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->is_type == '2') {
                return redirect()->route('manager.dashboard');
            } else {
                return redirect()->route('user.dashboard');
            }
        } else {
            return redirect()->route('login');
        }
    }
    
    public function adminHome()
    {
        return view('admin.pages.dashboard');
    }

    public function managerHome()
    {
        return 'manager';
    }

    public function userHome()
    {
        return 'user';
    }
}
