<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    // Show the form to create a new folder
    public function create($parentId = null)
    {
        return view('CreateFolder', compact('parentId'));
    }

    // Store a newly created folder
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:folders,id',
        ]);

        // Check if a folder with the same name already exists in the same parent folder
        $existingFolder = Folder::where('name', $request->name)
            ->where('parent_id', $request->parent_id)
            ->where('user_id', auth()->id()) // Ensure the folder belongs to the authenticated user
            ->first();

        if ($existingFolder) {
            // If folder with the same name exists, return an error
            return redirect()->back()
                ->withErrors(['name' => 'A folder with the same name already exists in this location.'])
                ->withInput();
        }

        // If no duplicate is found, create a new folder
        Folder::create([
            'user_id' => auth()->id(),  // Assuming the user is authenticated
            'name' => $request->name,
            'parent_id' => $request->parent_id,
        ]);

        return redirect()->route('indexFolder')
            ->with('success', 'Folder created successfully.');
    }

    // Display all folders
    public function index()
    {
        // Get all folders that do not have a parent and their subfolders
        $folders = Folder::whereNull('parent_id')->with('subfolders')->get();
        return view('indexFolder', compact('folders'));
    }
    
    // Show a specific folder and its subfolders
    public function show($id)
    {
        // Fetch the folder with its subfolders
        $folder = Folder::with('subfolders')->findOrFail($id);
        return view('show', compact('folder'));
    }

    // Update folder visibility
    public function update(Request $request, $id)
    {
        $folder = Folder::findOrFail($id);
        $request->validate([
            'is_public' => 'required|boolean',
        ]);

        $folder->update([
            'is_public' => $request->is_public,
        ]);

        return redirect()->route('folders.index')
            ->with('success', 'Folder visibility updated successfully.');
    }

    // Delete a specific folder
    public function destroy($id)
    {
        $folder = Folder::findOrFail($id);
        $folder->delete();

        return redirect()->route('folders.index')
            ->with('success', 'Folder deleted successfully.');
    }
}
