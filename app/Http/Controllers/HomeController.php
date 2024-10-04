<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // Function to show public parent folders with their subfolders and files
    
    // public function Homeindex()
    // {
    //     // Fetch only public folders that are parent folders (where parent_id is null)
    //     $folders = Folder::where('is_public', true)->whereNull('parent_id')->with('user')->get();
    
    //     return view('Home', compact('folders'));
    // }
    
  
}
