<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusEnum;
use App\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use App\Models\Poll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class PollController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $polls = Poll::latest('id')->paginate(15)->withQueryString();

        return view('admin.poll.index', [
            'polls' => $polls
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.poll.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'question_bn'   => 'required',
                'photo'         => 'image',
            ]
        );

        $poll = new Poll();
        $poll->question_bn = $request->question_bn;

        if ($request->photo) {
            $poll->photo = FileUploadHelper::uploadFile($request->photo, $poll->photo);
        }

        $poll->save();
        return redirect()->route('poll.index')->with('message', 'New poll added!');
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
    public function edit(Poll $poll)
    {
        return view('admin.poll.edit', [
            'poll' => $poll,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Poll $poll)
    {
        $request->validate(
            [
                'question_bn'   => 'required',
                'photo'         => 'image',
                'status'        => 'required',
            ]
        );

        $poll->question_bn  = $request->question_bn;
        $poll->status       = $request->status;

        if ($request->photo) {
            $poll->photo = FileUploadHelper::uploadFile($request->photo, $poll->photo);
        }

        $poll->update();
        return redirect()->route('poll.index')->with('message', 'Successfully poll information updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Poll $poll)
    {
        if (auth()->user()->role_id == 1) {
            // Mark as inactive
            $poll->status = StatusEnum::INACTIVE->value;
            $poll->save();

            // Soft delete
            $poll->delete();

            return redirect()->route('poll.index')
                ->with('message', 'Poll has been deleted from this system!');
        }

        return redirect()->route('poll.index')
            ->with('permissionError', Config::get('helper.permissionErrorMsg'));
    }
}
