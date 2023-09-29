<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function index()
    {
        $loginStatus = false;
        if(!empty(Auth::user())) {
            $loginStatus = true;
        }
        return Inertia::render('Contact/Index', [
            'loginStatus' => $loginStatus
        ]);
    }
}
