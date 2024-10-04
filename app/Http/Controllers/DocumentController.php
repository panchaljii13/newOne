<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    // Create a new folder
    public function create(Request $request)
    {
        DB::beginTransaction();
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:folders,id',
        ]);

        $folder = Folder::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'parent_id' => $request->parent_id,
        ]);
        if($folder){
            DB::commit();
            return redirect()->route('')->with('success','');
        }
        else{
            DB::rollBack();
            return redirect()->route('')->with('error','');
        }

        return redirect()->back()->with('success', 'Folder created successfully.');
    }

    // Fetch folders for a user
    public function index()
    {
        $folders = Folder::where('user_id', auth()->id())->with('subfolders')->get();
        return view('folders.index', compact('folders'));
    }
}
