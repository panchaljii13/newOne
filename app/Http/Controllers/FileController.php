<?php

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    // Show the form to upload a file to a specific folder
    public function create($folderId)
    {
        return view('files.create', compact('folderId'));
    }

    // Store a newly created file in storage
    public function store(Request $request)
    {
        $request->validate([
            'folder_id' => 'required|exists:folders,id',
            'file' => 'required|file|mimes:pdf,jpg,png,docx|max:2048', // Validate file types and size
        ]);

        // Store the file
        $path = $request->file('file')->store('files');

        // Create a new file record in the database
        File::create([
            'user_id' => auth()->id(),  // Assuming the user is authenticated
            'folder_id' => $request->folder_id,
            'file_path' => $path,
        ]);

        return redirect()->route('files.index', $request->folder_id)
            ->with('success', 'File uploaded successfully.');
    }

    // Display all files in a specific folder
    public function index($folderId)
    {
        $files = File::where('folder_id', $folderId)->get();
        return view('files.index', compact('files', 'folderId'));
    }

    // Delete a specific file
    public function destroy($id)
    {
        $file = File::findOrFail($id);
        
        // Delete the file from storage
        Storage::delete($file->file_path);
        
        // Delete the file record from the database
        $file->delete();

        return redirect()->back()->with('success', 'File deleted successfully.');
    }
}
