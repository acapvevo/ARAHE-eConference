<?php

namespace App\Traits;

use App\Models\Submission;
use Illuminate\Support\Facades\Storage;

trait SubmissionTrait
{
    public function reformatFileName($fileName)
    {
        return preg_replace('/\s+/', '_', $fileName);
    }

    public function getPaper($fileName, $submission)
    {
        $filePath = 'ARAHE' . $submission->registration->form->session->year . '/submission/' . str_replace('-', '_', $submission->registration->code) . '/' . $fileName;
        $fileExtension = pathinfo(storage_path($filePath), PATHINFO_EXTENSION);

        switch ($fileExtension) {
            case 'docx':
            case 'doc':
                return Storage::download($filePath);
                break;

            default:
                return Storage::response($filePath);
                break;
        }
    }

    public function getSubmission($id)
    {
        return Submission::find($id);
    }

    public function getSubmissionByReviewerID($reviewer_id)
    {
        return Submission::where('reviewer_id', $reviewer_id)->get();
    }
}
