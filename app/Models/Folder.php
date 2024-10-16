<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ZipArchive;

class Folder extends Model
{
    use HasFactory;

    // Define the fillable properties
    protected $fillable = ['user_id', 'name', 'parent_id', 'is_public'];

/**---------------------------------------------Store /create----------------------------------
 * 
 * Creates a new folder for the authenticated user if a folder with the same name does not already exist
 * in the specified parent folder. It checks for duplicates based on name, parent_id, and user_id
 * to prevent naming conflicts within the user's folder structure.
 */

    public static function createFolder($data)
    {
        // Check if a folder with the same name already exists in the same parent folder
        $existingFolder = self::where('name', $data['name'])
            ->where('parent_id', $data['parent_id'])
            ->where('user_id', $data['user_id']) // Ensure the folder belongs to the authenticated user
            ->first();

        if ($existingFolder) {
            // Return an error if folder already exists
            return ['error' => 'A folder with the same name already exists in this location.'];
        }

        // If no duplicate is found, create a new folder
        return self::create([
            'user_id' => $data['user_id'],
            'name' => $data['name'],
            'parent_id' => $data['parent_id'],
        ]);
    }

/** ---------------------------------------------------Updates--------------------------------
 * 
 * Updates an existing folder identified by its ID with the provided data.
 * It retrieves the folder using the findOrFail method, ensuring it exists,
 * and then applies the updates, returning the updated folder instance.
 */

    public static function updateFolder($id, $data)
{
    $folder = self::findOrFail($id);
    
    // Optionally, you can add additional validation here if necessary
    // For example, checking if a folder with the same name exists in the same parent folder

    // Update the folder with the new data
    $folder->update($data);
    
    return $folder; // Return the updated folder (optional)
}

/**----------------------------------------------Deletes----------------------------
 * 
 * Deletes the folder along with its associated download history records.
 * First, it removes all entries in the download_histories table related to
 *
 */

// In Folder.php model
public function deleteFolderWithHistories()
{
    // First, delete all download history records related to the folder
    \DB::table('download_histories')->where('folder_id', $this->id)->delete();

    // Now delete the folder
    $this->delete();
}

// -----------------------------------------------------------------------------
/**----------------------------------------------  Show foloder in public  ----------------------
 * 
 * Determines if the current folder is empty by checking for the presence
 * of files and non-empty subfolders. It returns true if the folder and
 * all its subfolders are empty, otherwise false.
 */

public function isEmpty(): bool
{
    // Check if the current folder has files
    if ($this->files()->exists()) {
        return false; // Folder is not empty
    }

    // Check if the current folder has non-empty subfolders
    foreach ($this->subfolders as $subfolder) {
        if (!$subfolder->isEmpty()) {
            return false; // Found a non-empty subfolder
        }
    }

    // All checks passed, folder and subfolders are empty
    return true;
}
/**
 * Checks if the folder contains any URLs. It returns true if URLs are present
 * and false if there are none, helping to identify the folder's content.
 */

public function hasUrls(): bool
{
    // Check if the folder has URLs
    return $this->urls()->exists(); // Returns true if there are URLs, false otherwise
}
/**
 * Toggles the public status of the folder. If the folder is made public,
 * it recursively updates the visibility of all subfolders to public as well.
 */

public function togglePublic()
{
    // Toggle the public status
    $this->is_public = !$this->is_public;
    $this->save();

    // Update all subfolders recursively only if the folder is made public
    if ($this->is_public) {
        $this->setSubfoldersVisibility(true);
    }
}

// Recursive function to set visibility for subfolders at all levels
public function setSubfoldersVisibility(bool $isPublic)
{
    // Retrieve subfolders
    $subfolders = $this->subfolders;

    // Iterate through each subfolder
    foreach ($subfolders as $subfolder) {
        // Update the current subfolder's visibility
        $subfolder->is_public = $isPublic;
        $subfolder->save();

        // Recursively update the subfolder's subfolders (nested subfolders)
        if ($subfolder->subfolders()->exists()) {
            $subfolder->setSubfoldersVisibility($isPublic);
        }
    }
}
// --------------------------------------------Downlord------------------------------------
 // Method to download the folder as a ZIP file
 public function download(): string
 {
     // Create a new ZIP archive
     $zip = new ZipArchive;
     $fileName = $this->name . '.zip';
     $zipFilePath = storage_path('app/public/' . $fileName);

     // Open the ZIP file for writing
     if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
         // Add the root folder and its content to the ZIP
         $this->addToZip($zip);
         // Close the ZIP file
         $zip->close();
     } else {
         throw new \Exception('Could not create ZIP file.');
     }

     return $zipFilePath;
 }

 // Add folder and its content to ZIP
 private function addToZip(ZipArchive $zip, $parentFolder = '')
 {
     // Loop through files in the current folder
     foreach ($this->files as $file) {
         // Construct the full file path
         $filePath = storage_path('app/public/' . $file->file_path);
         
         // Ensure the file exists
         if (file_exists($filePath)) {
             // Add the file to the ZIP archive with the correct path inside the ZIP
             $zip->addFile($filePath, $parentFolder . $file->file_name);
         }
     }

     // Recursively add subfolders
     foreach ($this->subfolders as $subfolder) {
         // Add subfolder and its files recursively
         $subfolder->addToZip($zip, $parentFolder . $subfolder->name . '/');
     }
 }


// ---------------------------------------------------------------------------------------
    // Relationship to the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship for subfolders (self-referential)
    public function subfolders()
    {
        return $this->hasMany(Folder::class, 'parent_id');
    }

    // Relationship for files in the folder
    public function files()
    {
        return $this->hasMany(File::class);
    }
    public function downloadHistories()
    {
        return $this->hasMany(DownloadHistory::class);
    }
//  relationship for URLs
public function urls()
{
    return $this->hasMany(Url::class);
}

    
  
}
