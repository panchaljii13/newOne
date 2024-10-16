<?php

namespace App\Http\Controllers;
use App\Models\Url;
use App\Models\File;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;


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

   // --------------------------------------Store the uploaded file-----------------------------------
   public function uploadFile(Request $request, $id)
   {
       // Call the uploadFile method in the File model
       $file = File::uploadFile($request, $id);
   
       if ($file) {
           return redirect()->back()->with('success', 'File uploaded successfully.');
       }
   
       return redirect()->back()->with('error', 'Failed to upload file.');
   }
   

    // ---------------------------------------Download the file-------------------------------------------
    public function download($id)
    {
        $file = File::findOrFail($id);
        return Storage::disk('public')->download($file->file_path);
    }

    // Delete the file
    public function destroy($id)
{
    // Find the file by ID
    $file = File::findOrFail($id); // File ko find karenge

    // Call the deleteFile method to handle deletion
    $file->deleteFile();

    return redirect()->back()->with('success', 'File deleted successfully!');
}



    //---------------- ------------------------------rename file name ----------------

   public function renameFile(Request $request, $id)
{
    // Validate the request
    $request->validate([
        'new_file_name' => [
            'required',
            'string',
            'max:255',
            Rule::unique('files', 'name')->ignore($id), // Ensure the new file name is unique
        ],
    ]);

    // Find the file entry in the database
    $file = File::find($id);

    if (!$file) {
        return redirect()->back()->with('error', 'File not found.');
    }

    // Rename the file and save changes
    $file->name = $request->input('new_file_name');
    if ($file->save()) {
        return redirect()->back()->with('success', 'File renamed successfully.');
    }
dd($file);
    return redirect()->back()->with('error', 'Failed to rename the file.');
}

    



}
