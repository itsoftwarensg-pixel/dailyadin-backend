<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ScrollTypeEnum;
use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Models\BnScroll;
use Illuminate\Http\Request;

class BnScrollController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bnScrolls = BnScroll::with(['creator', 'updater'])
            ->latest('id')
            ->paginate(30)
            ->withQueryString();

        return view('admin.bn-scroll.index', [
            'bnScrolls' => $bnScrolls,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $scrollTypes = ScrollTypeEnum::options();
        return view('admin.bn-scroll.create', [
            'scrollTypes' => $scrollTypes,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type'       => 'required|integer|in:' . implode(',', array_column(ScrollTypeEnum::options(), 'id')),
            'heading'    => 'required|string|max:255',
            'end_time'   => 'nullable|date',
        ]);

        $bnScroll = new BnScroll();
        $bnScroll->type         = $request->type;
        $bnScroll->heading      = $request->heading;
        $bnScroll->end_time     = $request->end_time;
        $bnScroll->created_by   = auth()->id();

        $bnScroll->save();

        return redirect()->route('bn-scroll.index')->with('message', 'Scroll created successfully.');
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
    public function edit(BnScroll $bnScroll)
    {
        $scrollTypes = ScrollTypeEnum::options();
        return view('admin.bn-scroll.edit', [
            'bnScroll' => $bnScroll,
            'scrollTypes' => $scrollTypes,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BnScroll $bnScroll)
    {
        $validated = $request->validate([
            'type'       => 'required|integer|in:' . implode(',', array_column(ScrollTypeEnum::options(), 'id')),
            'heading'    => 'required|string|max:255',
            'end_time'   => 'nullable|date',
        ]);

        $bnScroll->type         = $request->type;
        $bnScroll->heading      = $request->heading;
        $bnScroll->end_time     = $request->end_time;
        $bnScroll->created_by   = auth()->id();

        $bnScroll->update();

        return redirect()->route('bn-scroll.index')->with('message', 'Scroll updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BnScroll $bnScroll)
    {
        // Soft delete
        $bnScroll->delete();

        return redirect()->route('bn-scroll.index')
            ->with('message', 'Scroll news has been deleted from this system!');
    }
}
