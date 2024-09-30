<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    // Display form for uploading a file
    public function showUploadForm($folderId)
    {
        $folder = Folder::findOrFail($folderId);
        return view('create', compact('create'));
    }

    // Store the uploaded file
    public function uploadFile(Request $request, $id)
    {
        // Validate the file upload
        $request->validate([
            'file' => 'required|file|mimes:jpg,png,pdf,docx,txt|max:2048'
        ]);

        // Handle the file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = $file->store('files', 'public'); // Save file in public storage

            // Save file info to the database
            File::create([
                'user_id' => Auth::id(),
                'folder_id' => $id,
                'file_path' => $filePath,
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
        $file = File::findOrFail($id);
        Storage::disk('public')->delete($file->file_path);
        $file->delete();

        return redirect()->back()->with('success', 'File deleted successfully.');
    }
}
