<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class File extends Model
{
    use HasFactory;

   protected $fillable = ['user_id', 'folder_id', 'file_name', 'file_path'];

//    -----------------------------------------------------------------------------
// --------------------------------------uplode file------------------------
public static function uploadFile($request, $folderId)
{
    // Validate the request data (you can choose to move this to the controller if you prefer)
    $request->validate([
        'file_name' => 'required|string|max:255',
        'file' => 'required|file|mimes:jpg,png,pdf,docx,txt|max:2048',
        'url' => 'nullable|url|max:255',
    ]);

    // Handle the file upload
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $filePath = $file->store('files', 'public'); // Save file in public storage

        // Use the file name provided by the user
        $fileName = $request->input('file_name');

        // Get the URL if provided
        $url = $request->input('url'); // This can be null if not provided

        // Save file info to the database
        return self::create([
            'user_id' => Auth::id(),
            'folder_id' => $folderId,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'url' => $url, // Store the URL in the database
        ]);
    }

    return null; // Return null if no file was uploaded
}


// ---------------------------------------------------------Delete files---------------------------------
public function deleteFile()
{
    // Construct the full file path
    $filePath = storage_path('app/public/' . $this->file_path);
    
    // Check if the file exists and delete it
    if (file_exists($filePath)) {
        unlink($filePath); // Delete the file from storage
    }

    // Delete the file record from the database
    $this->delete();
}
// ------------------------------------update /edit------------------------------------
public function renameFile($newFileName)
    {
        // Get the current file path
        $currentPath = storage_path('app/public/' . $this->file_path);

        // Create the new file path
        $newFileNameWithExt = $newFileName . '.' . pathinfo($this->file_path, PATHINFO_EXTENSION);
        $newFilePath = storage_path('app/public/files/' . $newFileNameWithExt);

        // Rename the file
        if (rename($currentPath, $newFilePath)) {
            // Update the file name and path in the database
            $this->file_name = $newFileNameWithExt;
            $this->file_path = 'files/' . $newFileNameWithExt; // Update the file path
            $this->save(); // Save the changes to the database

            return true; // Indicate success
        }

        return false; // Indicate failure
    }

// ---------------------------------------Reletionships--------------------------------
   
    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function files()
{
    return $this->hasMany(File::class);
}
}
