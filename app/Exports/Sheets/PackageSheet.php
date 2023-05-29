<?php

namespace App\Exports\Sheets;

use App\Traits\SummaryTrait;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class PackageSheet implements FromView, WithTitle
{
    use SummaryTrait;

    private $form_id;

    public function __construct($form_id)
    {
        $this->form_id = $form_id;
    }

    public function view(): View
    {
        return view('exports.sheets.package', [
            'summaries' => $this->getSummariesByFormID($this->form_id),
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'By Packages';
    }
}