<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use App\Models\MediaFile;
use Illuminate\Http\Request;

class MediaFileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mediaFiles = MediaFile::latest('id')->paginate(15)->withQueryString();
        return view('admin.media-file.index', [
            'mediaFiles' => $mediaFiles
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.media-file.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate allowed file types
        // Max 20 MB
        $request->validate([
            'file' => 'required|file|mimes:doc,docx,pdf,jpg,jpeg,png,webp,mp3,mp4,xls,xlsx|max:20480',
        ]);

        $mediaFile = new MediaFile();
        $file = $request->file('file');
        $mediaFile->type = $file->getClientOriginalExtension();

        if ($file) {
            $mediaFile->path = FileUploadHelper::uploadFile($file, $mediaFile->file);
        }

        $mediaFile->save();
        return redirect()->route('media-file.index')->with('message', 'Successfully new file uploaded!');
    }
}
