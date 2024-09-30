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
        // dd($request->all());
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

    public function edit($id)
{
    $folder = Folder::findOrFail($id); // Find the folder by its ID
    return view('editFolder', compact('folder')); // Return the edit view with folder data
}
public function update(Request $request, $id)
{
    // Validate the request data
    $request->validate([
        'name' => 'required|string|max:255',
    ]);

    // Find the folder and update its name
    $folder = Folder::findOrFail($id);
    $folder->update([
        'name' => $request->input('name'),
    ]);

    return redirect()->route('indexFolder')->with('success', 'Folder name updated successfully.');
}

    // Update folder visibility
    public function Rename(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'is_public' => 'nullable|boolean', // Ensure that is_public is a boolean value
        ]);
    
        // Find the folder and update its details
        $folder = Folder::findOrFail($id);
        $folder->update([
            'name' => $request->input('name'),
            'is_public' => $request->has('is_public') ? true : false, // Default to false if unchecked
        ]);
    
        return redirect()->route('indexFolder')->with('success', 'Folder updated successfully.');
    }

    // Delete a specific folder
    public function destroy($id)
    {
        $folder = Folder::findOrFail($id);
        $folder->delete();

        return redirect()->route('indexFolder')
            ->with('success', 'Folder deleted successfully.');
    }

    public function togglePublic($id)
{
    $folder = Folder::findOrFail($id);

    // Toggle the public status
    $folder->is_public = !$folder->is_public;
    $folder->save();

    return redirect()->back()->with('success', 'Folder visibility updated successfully.');
}
public function public()
{
    // Fetch only public folders along with their files and the user who created the folder
    $folders = Folder::with(['files', 'user'])
                     ->where('is_public', true)
                     ->get();

    return view('Home', compact('folders'));
}
}