<?php

namespace App\Exports;

use App\Exports\Sheets\AccomodationsSheet;
use App\Exports\Sheets\ExtrasSheet;
use App\Exports\Sheets\PackageSheet;
use App\Exports\Sheets\RegistrationsSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RegistrationExport implements WithMultipleSheets
{
    use Exportable;

    private $form_id;

    public function __construct($form_id)
    {
        $this->form_id = $form_id;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        return [
            new RegistrationsSheet($this->form_id),
            new PackageSheet($this->form_id),
            new AccomodationsSheet($this->form_id),
            new ExtrasSheet($this->form_id),
        ];
    }
}
