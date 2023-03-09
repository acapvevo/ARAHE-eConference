<?php

namespace App\Traits;

use App\Models\Record;

trait RecordTrait
{
    use FormTrait;

    public function createRecord($reviewer_id, $form_id)
    {
        return new Record([
            'reviewer_id' => $reviewer_id,
            'form_id' => $form_id,
            'accept' => 0,
            'reject' => 0,
            'return' => 0,
            'assign' => 0,
            'reviewing' => 0,
        ]);
    }

    public function getCurrentRecordByReviewerId($reviewer_id)
    {
        $currentForm = $this->getCurrentForm();

        if (Record::where('reviewer_id', $reviewer_id)->where('form_id', $currentForm->id)->exists()) {
            return Record::where('reviewer_id', $reviewer_id)->where('form_id', $currentForm->id)->first();
        } else {
            return $this->createRecord($reviewer_id, $currentForm->id);
        }
    }

    public function getRecordByReviewerIdAndFormId($reviewer_id, $form_id)
    {
        if (Record::where('reviewer_id', $reviewer_id)->where('form_id', $form_id)->exists()) {
            return Record::where('reviewer_id', $reviewer_id)->where('form_id', $form_id)->first();
        } else {
            return $this->createRecord($reviewer_id, $form_id);
        }
    }
}
