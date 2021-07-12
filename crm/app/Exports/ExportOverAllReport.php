<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportOverAllReport implements FromView
{
    private $data;

    public function __construct($data)
	{
		$this->data = $data;
	}

	public function view(): View
	{
		$details = $this->data;

		return view('pages.reports.xlsx.overall', 
			[
				'data'  => $details['data'],
				'total' => $details['total'],
				'countDays' => $details['countDays'],
				'dateRange' => $details['dateRange']
			]
		);
	}
}
