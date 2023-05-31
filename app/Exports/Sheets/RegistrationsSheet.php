<?php

namespace App\Exports\Sheets;

use App\Models\Registration;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RegistrationsSheet implements FromView, WithTitle, ShouldAutoSize
{
    private $form_id;

    public function __construct($form_id)
    {
        $this->form_id = $form_id;
    }

    public function view(): View
    {
        return view('exports.sheets.registration', [
            'registrations' => Registration::where('form_id', $this->form_id)->get(),
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Registrations';
    }
}
