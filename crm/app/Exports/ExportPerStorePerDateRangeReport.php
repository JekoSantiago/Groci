<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportPerStorePerDateRangeReport implements FromView
{
    private $data;

    public function __construct($data)
	{
		$this->data = $data;
	}

	public function view(): View
	{
		$details = $this->data;

		return view('pages.reports.xlsx.store_range', 
			[
				'data'  => $details['data'],
                'dateRange' => $details['dateRange'],
                'countDays' => $details['countDays'],
                'overall'   => $details['total']
			]
		);
	}
}
