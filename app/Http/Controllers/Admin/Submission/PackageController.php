<?php

namespace App\Http\Controllers\Admin\Submission;

use App\Traits\SummaryTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\FormTrait;
use Illuminate\Support\Facades\Validator;

class PackageController extends Controller
{
    use SummaryTrait, FormTrait;

    public function list()
    {
        $currentForm = $this->getCurrentForm();

        $summaries = $this->getSummariesByFormID($currentForm->id);

        $acommadationSummaries = $summaries->filter(function ($summary) {
            return ($summary->hotel_id && $summary->occupancy_id) || $summary->getPackage()->fullPackage;
        });
        $extraSummaries = $summaries->filter(function ($summary) {
            return $summary->extras->isNotEmpty();
        });

        return view('admin.submission.package.list')->with([
            'form' => $currentForm,
            'summaries' => $summaries,
            'acommadationSummaries' => $acommadationSummaries,
            'extraSummaries' => $extraSummaries
        ]);
    }

    public function view($id)
    {
        $validation = Validator::make(['summary_id' => $id], [
            'summary_id' => 'required|integer|exists:summaries,id',
        ]);

        if ($validation->fails())
            return redirect(route('admin.submission.package.list'))->with('error', 'Please Try Again');

        $summary = $this->getSummary($id);

        return view('admin.submission.package.view')->with([
            'summary' => $summary,
        ]);
    }

    public function download(Request $request)
    {
        $request->validate([
            'summary_id' => 'required|exists:App\Models\Summary,id'
        ]);

        $summary = $this->getSummary($request->summary_id);

        return $summary->registration->getProofFile();
    }
}
