<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FolderController extends Controller
{
    // Create a new folder
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:folders,id',
        ]);

        $folder = Folder::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'parent_id' => $request->parent_id,
        ]);

        return redirect()->back()->with('success', 'Folder created successfully.');
    }

    // Fetch folders for a user
    public function index()
    {
        $folders = Folder::where('user_id', auth()->id())->with('subfolders')->get();
        return view('folders.index', compact('folders'));
    }
}
