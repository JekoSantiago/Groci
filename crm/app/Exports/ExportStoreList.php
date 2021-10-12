<?php

namespace App\Exports;

use App\Cms;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;




class ExportStoreList implements FromView
{

	public function view(): View
	{

		$stores = Cms::getStores();

		return view('pages.reports.xlsx.store_list',[
            'stores' => $stores
        ]);
	}
}
