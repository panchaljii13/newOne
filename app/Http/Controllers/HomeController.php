<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
    // Function to show public parent folders with their subfolders and files
    
    // public function index()
    // {
        
    //     // Fetch only public folders that are parent folders (where parent_id is null)
    //     $folders = Folder::where('is_public', true)->whereNull('parent_id')->with('user')->get();
    
    //     return view('Home', compact('folders'));
    // }
    public function index()
    {
        $user = Auth::user(); // Get the authenticated user

        if (!$user) {
            return redirect()->route('UserLogin'); // Redirect to login page if user is not authenticated
        }
        $folders = Folder::where('is_public', true)->whereNull('parent_id')->with('user')->get();
    
        return view('Home', compact('folders'));
       
    }
    
  
}
