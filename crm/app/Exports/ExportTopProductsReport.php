<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportTopProductsReport implements FromView
{
    private $data;

    public function __construct($data)
	{
		$this->data = $data;
	}

	public function view(): View
	{
		$details = $this->data;

		return view('pages.reports.xlsx.top_products', 
			[
                'data' => $details['data'],
				'dateRange' => $details['dateRange'],
                'countDays' => $details['countDays']
			]
		);
	}
}
