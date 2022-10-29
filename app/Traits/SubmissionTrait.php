<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait SubmissionTrait
{
    public function reformatFileName($fileName)
    {
        return preg_replace('/\s+/', '_', $fileName);
    }

    public function getPaper($fileName, $submission)
    {
        $filePath = 'submission/ARAHE' . $submission->form->session->year . '/' . $fileName;
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
}
