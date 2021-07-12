<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportPerDCPerDayReport implements FromView
{
    private $data;

    public function __construct($data)
	{
		$this->data = $data;
	}

	public function view(): View
	{
		$details = $this->data;

		return view('pages.reports.xlsx.dc_daily', 
			[
				'data'  => $details['data'],
                'dateRange' => $details['dateRange'],
                'total' => $details['total'],
                'countDays' => $details['countDays']
			]
		);
	}
}
