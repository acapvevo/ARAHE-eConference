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

    public function getPaper($type, $fileName, $submission)
    {
        $filePath = 'ARAHE' . $submission->form->session->year . '/' . $type . '/' . $fileName;
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
}
