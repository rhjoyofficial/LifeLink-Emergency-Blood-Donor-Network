<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function dashboard()
    {
        if (Auth::check()) {
            $role = Auth::user()->role;
            return match ($role) {
                'admin'     => redirect()->route('admin.dashboard'),
                'donor'     => redirect()->route('donor.dashboard'),
                'recipient' => redirect()->route('recipient.dashboard'),
                default     => redirect()->route('login'),
            };
        }
        return redirect()->route('login');
    }

    // public function dashboard()
    // {
    //     if (Auth::check()) {
    //         $role = Auth::user()->role;

    //         return match ($role) {
    //             'admin'     => view('admin.dashboard'),
    //             'donor'     => view('donor.dashboard'),
    //             'recipient' => view('recipient.dashboard'),
    //             default     => redirect()->route('login'),
    //         };
    //     }

    //     return redirect()->route('login');
    // }
}
