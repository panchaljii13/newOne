<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\DownloadHistory;
use Illuminate\Support\Facades\Auth;

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
// =============================================================================================================================
//                                         Display all folders
// =============================================================================================================================

    // Display all folders
    public function index()
{
    // Get all folders that do not have a parent and belong to the authenticated user, along with their subfolders
    $folders = Folder::whereNull('parent_id')
        ->where('user_id', auth()->id()) // Ensure the folder belongs to the authenticated user
        ->with('subfolders') // Eager load subfolders
        ->get();

    return view('indexFolder', compact('folders'));
}

    // Show a specific folder and its subfolders
    public function show($id)
    {
        // Find the folder by ID
        $folder = Folder::with('subfolders', 'files')->findOrFail($id);
    
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

   
// =============================================================================================================================
                                          // Delete a specific folder
// =============================================================================================================================

    // Delete a specific folder
   public function destroy($id)
{
    $folder = Folder::findOrFail($id);
    
    // Delete all download history records related to the folder
    \DB::table('download_histories')->where('folder_id', $folder->id)->delete();

    // Now delete the folder
    $folder->delete();

    return redirect()->back()->with('success', 'Folder deleted successfully.');
}
// =============================================================================================================================
//                                                        Show foloder in public
// =============================================================================================================================

    public function togglePublic($id)
{
    $folder = Folder::findOrFail($id);
    
    // Check if the folder is empty or if any of its subfolders are empty
    if ($this->isFolderEmpty($folder)) {
        return redirect()->back()->withErrors(['error' => 'Cannot make the folder public. It must contain at least one file or non-empty subfolder.']);
    }

    // Toggle the public status
    $folder->is_public = !$folder->is_public;
    $folder->save();

    // Update all subfolders recursively only if the folder is made public
    if ($folder->is_public) {
        $this->setSubfoldersVisibility($folder, true);
    }

    return redirect()->back()->with('success', 'Folder visibility updated successfully.');
}

// Function to check if the folder or its subfolders are empty
private function isFolderEmpty(Folder $folder): bool
{
    // Check if the current folder has files
    if ($folder->files->isNotEmpty()) {
        return false; // Folder is not empty
    }

    // Check if the current folder has non-empty subfolders
    foreach ($folder->subfolders as $subfolder) {
        if (!$this->isFolderEmpty($subfolder)) {
            return false; // Found a non-empty subfolder
        }
    }

    // All checks passed, folder and subfolders are empty
    return true;
}

// Recursive function to set visibility for subfolders at all levels
private function setSubfoldersVisibility(Folder $folder, bool $isPublic)
{
    // Retrieve subfolders
    $subfolders = $folder->subfolders;

    // Iterate through each subfolder
    foreach ($subfolders as $subfolder) {
        // Update the current subfolder's visibility
        $subfolder->is_public = $isPublic;
        $subfolder->save();

        // Recursively update the subfolder's subfolders (nested subfolders)
        if ($subfolder->subfolders()->exists()) {
            $this->setSubfoldersVisibility($subfolder, $isPublic);
        }
    }
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
 
     $zip = new ZipArchive;
     $fileName = $folder->name . '.zip';
     $zipFilePath = storage_path('app/public/' . $fileName);
 
     // Open the ZIP file for writing
     if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
         // Add the root folder and its content to the ZIP
         $this->addFolderToZip($folder, $zip);
         // Close the ZIP file
         $zip->close();
     } else {
         return redirect()->back()->with('error', 'Could not create ZIP file.');
     }
 
     // Record download history
     auth()->user()->downloadHistories()->create([
         'folder_id' => $folder->id,
         'downloaded_at' => now(),
     ]);
 
     // Return the ZIP for download
     return response()->download($zipFilePath)->deleteFileAfterSend(true);
 }
 
 private function addFolderToZip(Folder $folder, ZipArchive $zip, $parentFolder = '')
{
    // Loop through files in the current folder
    foreach ($folder->files as $file) {
        // Construct the full file path
        $filePath = storage_path('app/public/' . $file->file_path);
        
        // Ensure the file exists
        if (file_exists($filePath)) {
            // Add the file to the ZIP archive with the correct path inside the ZIP
            $zip->addFile($filePath, $parentFolder . $file->file_name); // Correct file name inside the ZIP
        }
    }

    // Recursively add subfolders
    foreach ($folder->subfolders as $subfolder) {
        // Add subfolder and its files recursively
        $this->addFolderToZip($subfolder, $zip, $parentFolder . $subfolder->name . '/');
    }
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

