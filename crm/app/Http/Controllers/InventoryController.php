<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\InventoryServices;
use App\Exports\ExportItems;
use Illuminate\Support\Facades\Session;


class InventoryController extends Controller
{
    public function __construct()
    {
        // $this->middleware(function ($request, $next) {
        //     if(base64_decode(Session::get('Role_ID')) != 2) :
        //         abort(403, json_encode(config('app.btn_previous')));
        //     endif;

        //     return $next($request);
        // });
    }

    public function index()
    {
        return view('pages.inventory.index',
            [
                'page'  => 'Store Inventory',
                'items' => InventoryServices::inventoryItems(base64_decode(Session::get('LocationCode'))),
            ]
        );
    }

    public function update(Request $request)
    {
        $scode    = base64_decode($request->input('code'));
        $logUser  = base64_decode(Session::get('Emp_Name'));
        $response = InventoryServices::replenishInventory($scode, $logUser);

        echo json_encode($response);
    }
}
