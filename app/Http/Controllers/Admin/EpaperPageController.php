<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use App\Models\EpaperPage;
use Illuminate\Http\Request;

class EpaperPageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $epaperPages = EpaperPage::all();
        return view('admin.epaper-page.index', [
            'epaperPages' => $epaperPages
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.epaper-page.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate allowed file types
        // Max 20 MB
        $request->validate([
            'file' => 'required|image|mimes:jpg,jpeg,png,webp|max:20480',
        ]);

        $epaperPage = new EpaperPage();
        $file = $request->file('file');

        if ($file) {
            $epaperPage->path = FileUploadHelper::uploadFile($file, $epaperPage->file);
        }

        $epaperPage->save();
        return redirect()->route('epaper-page.index')->with('message', 'Successfully new page uploaded!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EpaperPage $epaperPage)
    {
        return view('admin.epaper-page.edit', [
            'epaperPage' => $epaperPage
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EpaperPage $epaperPage)
    {
        $request->validate(
            [
                'file' => 'required|image|mimes:jpg,jpeg,png,webp|max:20480',
            ]
        );

        $file = $request->file('file');

        if ($file) {
            $epaperPage->path = FileUploadHelper::uploadFile($file, $epaperPage->path);
        }

        $epaperPage->update();
        return redirect()->route('division.index')->with('message', 'Successfully epaper page is updated!');
    }
}
