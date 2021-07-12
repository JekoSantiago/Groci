<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ContentServices;

class LocatorController extends Controller
{
    public function index(Request $request)
    {
        $result = ContentServices::nearestStore($request->input('lat'), $request->input('lon'));

        $response = [
            'store'     => $result[0]['storeName'],
            'latitude'  => $result[0]['userLatitude'],
            'longitude' => $result[0]['userLongitude']
        ];

        echo json_encode($response);
    }

    public function nearestStore(Request $request)
    {
        $data = (($request->segment(3) == 0) && ($request->segment(4) == 0)) ? NULL : ContentServices::topNearestStore($request->segment(3), $request->segment(4));
        
        return view('pages.partials.nearest_store', 
            [ 
                'data' => $data 
            ]
        );
    }

    public function searchNearestStore(Request $request)
    {
        $data = ContentServices::searchNearestStore($request->input('keyword'));

        if(empty($data)) :
            $htm  = '<div class="col-lg-12"><h3>No results found.</h3></div>';
        else :
            $htm  = '';
            foreach($data as $item) :
                $htm .= '<div class="col-md-6 col-xs-12 mb-4">
                <div style="border-radius: 5px; box-shadow: 0 2px 8px 0 rgba(0, 0, 0, 0.1); margin-bottom: 16px; overflow: hidden; padding: 12px; position: relative;">
                    <h6><i aria-hidden="true" class="mdi mdi-map-marker-circle"></i> '.$item['storeName'] .'</h6>
                    <div style="font-weight: 500; font-size: 11px; color: #000">'.strtoupper($item['city']) .', '.$item['province'] .'</div>
                    <div class="float-right">
                        <a href="'.url('/register/'.$item['storeCode']).'" class="btn btn-secondary">REGISTER</a>
                    </div>
                </div>
            </div>';
            endforeach;
        endif;
        
        echo json_encode($htm);
    }
}
