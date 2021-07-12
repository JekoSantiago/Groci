<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\Redirect;
use App\Services\ContentServices;
use App\Services\ProductServices;
use App\Services\AccountServices;

class HomeController extends Controller
{
    public function index()
    {
       /* if(date('Y-m-d') == '2021-04-13') :
            return view('pages.under');
        endif; */

	    if(request()->query('id') != '') :
            return Redirect::to('/register/'.request()->query('id'));
        else :

            if(!Session::get('isLogged')) :
                $scode = NULL;
            else :
                if(is_null(Session::get('addressID'))) :
                    $scode = ContentServices::customerRegisteredStores(Session::get('email'));
                else :
                    $scode = AccountServices::customerAddressAssignedStore(Session::get('addressID'));
                endif;
            endif;

            $storeCode = (is_null(Session::get('addressID'))) ? NULL : AccountServices::customerAddressAssignedStore(Session::get('addressID'));
            $isLogged = (!Session::get('isLogged')) ? 0 : 1;

            return view('pages.index',
                [
                    'page'     => 'Home Page',
                    'category' => ContentServices::getCategories($scode),
                    'sliders'  => ContentServices::homeSliders(),
                    'topSaver' => ProductServices::topRandomItems($storeCode, $isLogged)
                ]
            );
        endif;
    }
}
