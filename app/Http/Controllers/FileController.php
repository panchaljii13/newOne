<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
   
    // =============================================================================================================================
//                                       uploading a file
// =============================================================================================================================
 // Display form for uploading a file
    public function showUploadForm($folderId)
    {
        $folder = Folder::findOrFail($folderId);
        return view('showUploadForm', compact('File'));
    }

   // Store the uploaded file
public function uploadFile(Request $request, $id)
{
    // Validate the file upload
    $request->validate([
        'file' => 'required|file|mimes:jpg,png,pdf,docx,txt|max:2048',
    ]);

    // Handle the file upload
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $filePath = $file->store('files', 'public'); // Save file in public storage

        // Get original file name
        $fileName = $file->getClientOriginalName();

        // Save file info to the database
        File::create([
            'user_id' => Auth::id(),
            'folder_id' => $id,
            'file_path' => $filePath,
            'file_name' => $fileName, // Add this line
        ]);

        return redirect()->back()->with('success', 'File uploaded successfully.');
    }

    return redirect()->back()->with('error', 'Failed to upload file.');
}

    // Download the file
    public function download($id)
    {
        $file = File::findOrFail($id);
        return Storage::disk('public')->download($file->file_path);
    }

    // Delete the file
    public function destroy($id)
    {
        $file = File::findOrFail($id); // File ko find karenge
    
        // File ka path delete karne se pehle ensure karein
        $filePath = storage_path('app/public/' . $file->file_path);
        if (file_exists($filePath)) {
            unlink($filePath); // File ko delete karein from storage
        }
    
        // Database se file ko delete karna
        $file->delete();
    
        return redirect()->back()->with('success', 'File deleted successfully!');
    }
}
