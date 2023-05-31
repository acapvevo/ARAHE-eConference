<?php

namespace App\Exports\Sheets;

use App\Traits\SummaryTrait;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AccomodationsSheet implements FromView, WithTitle, ShouldAutoSize
{
    use SummaryTrait;

    private $form_id;

    public function __construct($form_id)
    {
        $this->form_id = $form_id;
    }

    public function view(): View
    {
        return view('exports.sheets.accomodation', [
            'summaries' => $this->getSummariesByFormID($this->form_id)->filter(function ($summary) {
                return ($summary->hotel_id && $summary->occupancy_id) || $summary->getPackage()->fullPackage;
            }),
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'By Accomodations';
    }
}
