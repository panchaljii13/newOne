<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show()
    {
        dd("hhhhh");
        // Profile page view return karega
        return view('Myprofile');
    }
}