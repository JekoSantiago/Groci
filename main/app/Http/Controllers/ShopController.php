<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProductServices;
use App\Services\AccountServices;
use App\Services\ContentServices;
use App\Services\TextServices;
use App\Models\Product;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;


class ShopController extends Controller
{
    public function index(Request $request)
    {
        $catID = base64_decode($request->segment(2));
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
        $page = request('page', 1);
        $pageSize = 16;
        $results = ProductServices::productItems($catID, $storeCode, NULL, $isLogged);
        $offset = ($page * $pageSize) - $pageSize;
        $data = array_slice($results, $offset, $pageSize, true);
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator($data, count($results), $pageSize, $page);

        return view('pages.shop',
            [
                'page'     => 'Shop Page',
                'category' => ContentServices::getCategories($scode),
                'items'    => $paginator,
                'categoryName' => ContentServices::getCategoryName($catID)
            ]
        );
    }

    public function addToCart(Request $request)
    {
	    $storeCode = AccountServices::customerAddressAssignedStore(Session::get('addressID'));
        $result = ProductServices::addItemToCart(Session::get('orderBasket'), $request->input('param'), $request->input('qty'), $storeCode);
        $items = explode('@@', base64_decode($request->input('param')));

        if($result[0]->_RETURN  == 2) :
            $response = [
                'status'  => 'ok',
                'item'    => $items[1],
                'message' => '',
                'totalAmount' => $result[0]->totalAmount
            ];
        else :
            $response = [
                'status'  => 'fail',
                'item'    => $items[1],
                'message' => TextServices::message(-100),
                'totalAmount' => $result[0]->totalAmount
            ];
        endif;

        echo json_encode($response);
    }

    public function updateBasket(Request $request)
    {
        $data = [
            'id'    => $request->input('id'),
            'qty'   => $request->input('qty'),
            'price' => $request->input('price')
        ];

        $result = Product::updateItemInBasket($data);

        if($result == 1) :
            $response = [
                'status'  => 'ok',
                'message' => ''
            ];
        else :
            $response = [
                'status'  => 'fail',
                'message' => TextServices::message(-101)
            ];
        endif;

        echo json_encode($response);
    }

    public function search(Request $request)
    {
        $searchKey = str_replace('+', '-', urlencode($request->input('searchKey')));

        // $searchKey = (is_null($request->input('searchKey'))) ? 'a' : str_replace('+', '-', urlencode($request->input('searchKey')));
        // dd($searchKey);
        return Redirect::to('/search/result/'.$searchKey);
    }

    public function searchResult(Request $request)
    {
        $isLogged = (!Session::get('isLogged')) ? 0 : 1;
        $key  = urldecode(str_replace('-', '+', $request->segment(3)));
        $storeCode = (is_null(Session::get('addressID'))) ? NULL : AccountServices::customerAddressAssignedStore(Session::get('addressID'));
        $page = request('page', 1);
        $pageSize = 16;
        $results = ProductServices::productItems(NULL, $storeCode, $key, $isLogged);


        $offset = ($page * $pageSize) - $pageSize;
        $data = array_slice($results, $offset, $pageSize, true);
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator($data, count($results), $pageSize, $page);

        return view('pages.shop',
            [
                'page'         => 'Search Result Page',
                'category'     => ContentServices::getCategories($storeCode),
                'items'        => $paginator,
                'categoryName' => 'Search Results'
            ]
        );
    }

    public function saleItems()
    {
        $isLogged = (!Session::get('isLogged')) ? 0 : 1;
        $storeCode = (is_null(Session::get('addressID'))) ? NULL : AccountServices::customerAddressAssignedStore(Session::get('addressID'));
        $page = request('page', 1);
        $pageSize = 16;
        $results = ProductServices::promoItems($storeCode, $isLogged);
        $offset = ($page * $pageSize) - $pageSize;
        $data = array_slice($results, $offset, $pageSize, true);
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator($data, count($results), $pageSize, $page);

        return view('pages.shop',
            [
                'page'         => 'Search Result Page',
                'category'     => ContentServices::getCategories($storeCode),
                'items'        => $paginator,
                'categoryName' => 'Sale Products'
            ]
        );
    }
}
