<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportItemsPrice implements FromView
{
	private $data;

	public function __construct($data)
	{
		$this->data = $data;
	}

	public function view(): View
	{
		$details = $this->data;

		return view('pages.maintenance.products.items.xls.download_xls', 
			[
				'data' => $details['data']
			]
		);
	}

}