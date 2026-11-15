<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusEnum;
use App\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use App\Models\Writer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class WriterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $writers = Writer::query();

        if ($search) {
            $writers = $writers
                ->where('name', 'like', '%' . $search . '%')
                ->orWhere('alias', 'like', '%' . $search . '%')
                ->orWhere('title', 'like', '%' . $search . '%');
        }


        $writers = $writers
            ->latest('id')
            ->paginate(30)
            ->withQueryString();

        return view('admin.writer.index', [
            'writers' => $writers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.writer.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'name'  => 'required',
                'alias' => 'required',
                'photo' => 'image',
            ]
        );

        $writer = new Writer();
        $writer->name         = $request->name;
        $writer->alias        = $request->alias;
        $writer->title        = $request->title;
        $writer->description  = $request->description;

        if ($request->photo) {
            $writer->photo = FileUploadHelper::uploadFile($request->photo, $writer->photo);
        }

        $writer->save();
        return redirect()->route('writer.index')->with('message', 'New writer added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Writer $writer)
    {
        return view('admin.writer.edit', [
            'writer' => $writer,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Writer $writer)
    {
        $request->validate(
            [
                'name'      => 'required',
                'alias'     => 'required',
                'photo'     => 'image',
                'status'    => 'required',
            ]
        );

        $writer->name         = $request->name;
        $writer->alias        = $request->alias;
        $writer->title        = $request->title;
        $writer->description  = $request->description;
        $writer->status       = $request->status;

        if ($request->photo) {
            $writer->photo = FileUploadHelper::uploadFile($request->photo, $writer->photo);
        }

        $writer->update();
        return redirect()->route('writer.index')->with('message', 'Successfully writer information updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Writer $writer)
    {
        if (auth()->user()->role_id == 1) {
            // Mark writer as inactive
            $writer->status = StatusEnum::INACTIVE->value;
            $writer->save();

            // Soft delete the writer
            $writer->delete();

            return redirect()->route('writer.index')
                ->with('message', 'Writer has been deleted from this system!');
        }

        return redirect()->route('writer.index')
            ->with('permissionError', Config::get('helper.permissionErrorMsg'));
    }
}
