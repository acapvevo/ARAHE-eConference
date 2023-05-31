<?php

namespace App\Exports\Sheets;

use App\Traits\RegistrationTrait;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RegistrationCompleteSheet implements FromView, WithTitle, ShouldAutoSize
{
    use RegistrationTrait;

    private $form_id;

    public function __construct($form_id)
    {
        $this->form_id = $form_id;
    }

    public function view(): View
    {
        return view('exports.sheets.registration_complete', [
            'registrations' => $this->getRegistrationsCompletedByFormID($this->form_id),
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Completed Registrations List';
    }
}
