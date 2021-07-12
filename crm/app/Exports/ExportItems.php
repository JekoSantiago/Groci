<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportItems implements FromView
{
	private $data;

	public function __construct($data)
	{
		$this->data = $data;
	}

	public function view(): View
	{
		$details = $this->data;

		return view('pages.inventory.xls.download_xls', 
			[
				'data'  => $details['data'],
				'scode' => $details['scode']
			]
		);
	}

}