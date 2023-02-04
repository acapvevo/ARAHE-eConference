<?php

namespace App\Http\Controllers\Admin\Member;

use App\Models\Reviewer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReviewerController extends Controller
{
    public function list()
    {
        $reviewers = Reviewer::with(['submissions', 'participant'])->get();

        return view('admin.member.reviewer.list')->with([
            'reviewers' => $reviewers,
        ]);
    }

    public function view($id)
    {
        $reviewer = Reviewer::find($id);

        return view('admin.member.reviewer.view')->with([
            'reviewer' => $reviewer
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'submit' => 'required|string|in:active,inactive',
        ]);

        $reviewer = Reviewer::find($id);

        $reviewer->active = $request->submit === 'active';

        $reviewer->save();

        return redirect(route('admin.member.reviewer.view', array('id' => $reviewer->id)))->with('success', "This Reviewer's Status has been successfully updated to " . strtoupper($request->submit));
    }
}
