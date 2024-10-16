<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\DownloadHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

// use App\Models\Url;

use ZipArchive;

class FolderController extends Controller
{

    // =============================================================================================================================
//                                        create a new folder 
// =============================================================================================================================
          // Show the form to create a new folde
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

        // Prepare data for folder creation
        $data = [
            'user_id' => auth()->id(),  // Ensure the folder belongs to the authenticated user
            'name' => $request->name,
            'parent_id' => $request->parent_id,
        ];

        // Call the createFolder method in the Folder model
        $folder = Folder::createFolder($data); 

        if (isset($folder['error'])) {
            // If there's an error, redirect back with error message
            return redirect()->back()
                ->withErrors(['name' => $folder['error']])
                ->withInput();
        }

        // Redirect back with success message
        return back()->with('success', 'Folder created successfully.');
    }
// =============================================================================================================================
//                                         Display all folders
// =============================================================================================================================

//     // Display all folders
//     public function index()
// {
//     // Get all folders that do not have a parent and belong to the authenticated user, along with their subfolders
//     $folders = Folder::whereNull('parent_id')
//         ->where('user_id', auth()->id()) // Ensure the folder belongs to the authenticated user
//         ->with('subfolders') // Eager load subfolders
//         ->get();

//     return view('indexFolder', compact('folders'));
// }

//     // Show a specific folder and its subfolders
//     public function show($id)
//     {
//         // Find the folder by ID
//         $folder = Folder::with('subfolders', 'files')->findOrFail($id);
    
//         // Fetch the parent folder for breadcrumb navigation (optional)
//         $parentFolder = $folder->parent;
    
//         return view('folderview', compact('folder', 'parentFolder'));
//     }





// -------------------------------------------------Display all folders--------------------------------------
public function index()
{
    // Get all folders that do not have a parent and belong to the authenticated user, along with their subfolders and URLs
    $folders = Folder::whereNull('parent_id')
        ->where('user_id', auth()->id()) // Ensure the folder belongs to the authenticated user
        ->with(['subfolders', 'urls']) // Eager load subfolders and URLs
        ->get();

    return view('indexFolder', compact('folders'));
}

// ----------------------------------Show a specific folder, its subfolders, and URLs
public function show($id)
{
    // Find the folder by ID, including subfolders, files, and URLs
    $folder = Folder::with(['subfolders', 'files', 'urls'])->findOrFail($id);
    
    // Fetch the parent folder for breadcrumb navigation (optional)
    $parentFolder = $folder->parent;
    
    return view('folderview', compact('folder', 'parentFolder'));
}
 




    // =============================================================================================================================
//                                              edit /  update
// =============================================================================================================================

public function edit($id)
{
    $folder = Folder::findOrFail($id); // Find the folder by its ID
    return view('editFolder', compact('folder')); // Return the edit view with folder data
}


public function update(Request $request, $id)
{
   
    // Find the folder
    $folder = Folder::findOrFail($id);
     
     
    // Validate the request data
    $request->validate([
        'name' => [
            'required',
            'string',
            'max:255',
            // Exclude the current folder's name from the unique check
            Rule::unique('folders', 'name')->ignore($folder->id),
        ],
    ]);
    // dd($folder);
    // Update folder name directly
    $folder->name = $request->input('name');
    $folder->save(); // Save changes to the database

    // Redirect back with success message
    return redirect()->back()->with('success', 'Folder deleted successfully.');
}


// =============================================================================================================================
                                          // Delete a specific folder
// =============================================================================================================================

    // Delete a specific folder
    public function destroy($id)
    {
        // Find the folder by ID
        $folder = Folder::findOrFail($id);
    
        // Use the model method to delete the folder and its related download histories
        $folder->deleteFolderWithHistories();
    
        return redirect()->back()->with('success', 'Folder deleted successfully.');
    }
    
// =============================================================================================================================
//                                                        Show foloder in public
// =============================================================================================================================

public function togglePublic($id)
{
    $folder = Folder::findOrFail($id);

    // Check if the folder is empty or if it has no URLs
    if ($folder->isEmpty() && !$folder->hasUrls()) {
        return redirect()->back()->withErrors(['error' => 'Cannot make the folder public. It must contain at least one file, non-empty subfolder, or URL.']);
    }

    // Use the model method to toggle public status
    $folder->togglePublic();

    return redirect()->back()->with('success', 'Folder visibility updated successfully.');
}


// // =============================================================================================================================

    

public function public()
{
    // Fetch only public folders along with their files and the user who created the folder
    $folders = Folder::with(['files', 'user'])
                     ->where('is_public', true)
                     ->get();

    return view('Home', compact('folders'));
}

// =============================================================================================================================
//                                                    Download folder into Zip file
// =============================================================================================================================

public function download(Folder $folder)
{
    // Check if the folder contains any files or subfolders
    if ($folder->files->isEmpty() && $folder->subfolders->isEmpty()) {
        return redirect()->back()->with('error', 'This folder is empty and cannot be downloaded.');
    }

    try {
        $zipFilePath = $folder->download(); // Call the model method to create the ZIP
    } catch (\Exception $e) {
        return redirect()->back()->with('error', $e->getMessage());
    }

    // Record download history
    auth()->user()->downloadHistories()->create([
        'folder_id' => $folder->id,
        'downloaded_at' => now(),
    ]);

    // Return the ZIP for download
    return response()->download($zipFilePath)->deleteFileAfterSend(true);
}


// =============================================================================================================================
//                                          show  Download History
// =============================================================================================================================


public function showDownloadHistory()
{
    
    // Get the download history for the current logged-in user
    $downloadHistories = DownloadHistory::where('user_id', Auth::id())->with('folder')->get();
// dd($downloadHistories);
    // Pass the download histories to the view
    return view('downloadHistory', compact('downloadHistories'));
}
}

