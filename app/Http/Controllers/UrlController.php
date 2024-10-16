<?php

namespace App\Http\Controllers;

use App\Models\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UrlController extends Controller
{
   

    public function storeUrl(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'folder_id' => 'required|exists:folders,id', // Ensure the folder exists
            'url' => 'required|url',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Create a new URL
        Url::create([
            'user_id' => auth()->id(), // Assuming you're using authentication
            'name' => $request->name,
            'folder_id' => $request->folder_id,
            'url' => $request->url,
        ]);

        return redirect()->back()->with('success', 'URL added successfully.');
    }

    
    // Update the specified URL in storage
    public function update(Request $request, $id)
{
    $request->validate([
        'new_url_name' => 'required|string|max:255',
    ]);

    $url = Url::findOrFail($id);
    $url->name = $request->input('new_url_name');
    $url->save();

    return redirect()->back()->with('success', 'URL added successfully.');
}


    // Remove the specified URL from storage
    public function destroy(Url $url)
    {
        $url->delete(); // Delete the URL
        return redirect()->back()->with('success', 'URL added successfully.');
    }
}
