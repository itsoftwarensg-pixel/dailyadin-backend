<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use App\Models\StarProgram;
use Illuminate\Http\Request;

class StarProgramController extends Controller
{
    /**
     * Display the single edit form.
     */
    public function index()
    {
        $starProgram = StarProgram::firstOrNew(['id' => 1], [
            'status'  => 1, // Default Active
        ]);

        return view('admin.star-program.index', [
            'starProgram' => $starProgram
        ]);
    }

    /**
     * Store/Update the single resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'identity'   => 'nullable|string|max:255',
            'details'    => 'nullable|string',
            'quote'      => 'nullable|string',
            'video_link' => 'nullable|string',
            'photo'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status'     => 'required|integer',
        ]);

        $starProgram = StarProgram::firstOrNew(['id' => 1]);
        $starProgram->identity   = $request->identity;
        $starProgram->details    = $request->details;
        $starProgram->quote      = $request->quote;
        $starProgram->video_link = $request->video_link;
        $starProgram->status     = $request->status;

        if ($request->hasFile('photo')) {
            $starProgram->photo = FileUploadHelper::uploadFile($request->photo, $starProgram->photo);
        }

        $starProgram->save();

        return redirect()->back()->with('message', 'Star Program section updated successfully!');
    }
}
