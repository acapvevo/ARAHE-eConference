<?php

namespace App\Exports;

use Illuminate\View\View;
use App\Models\Registration;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class RegistrationExport implements FromView
{
    use Exportable;

    private $form_id;

    public function __construct($form_id)
    {
        $this->form_id = $form_id;
    }

    public function view(): View
    {
        return view('exports.registration', [
            'registrations' => Registration::where('form_id', $this->form_id)->get(),
        ]);
    }
}
