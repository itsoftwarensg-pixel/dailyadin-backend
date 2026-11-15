<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeVideoSection;
use Illuminate\Http\Request;

class HomeVideoSectionController extends Controller
{
    /**
     * Display the single edit form.
     */
    public function index()
    {
        $video = HomeVideoSection::firstOrNew(['id' => 1], [
            'type'    => 2, // Default Facebook
            'is_live' => 2, // Default No
            'status'  => 1, // Default Active
        ]);

        return view('admin.home-video-section.index', [
            'video' => $video
        ]);
    }

    /**
     * Store/Update the single resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'embed_code' => 'required',
            'type'       => 'required|integer',
            'is_live'    => 'required|integer',
        ]);

        HomeVideoSection::updateOrCreate(['id' => 1], $request->all());

        return redirect()->back()->with('message', 'Home video section updated successfully!');
    }

    /**
     * Show the form for editing (Redirect to index).
     */
    public function edit(HomeVideoSection $homeVideoSection)
    {
        return redirect()->route('home-video-section.index');
    }

    /**
     * Update (Redirect to store logic).
     */
    public function update(Request $request, HomeVideoSection $homeVideoSection)
    {
        return $this->store($request);
    }
}
