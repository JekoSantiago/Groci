<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AccountServices;
use App\Services\ProductServices;
use App\Models\Product;
use App\Models\Account;
use App\Models\Content;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOrderReceipt;
use App\Mail\SendConfirmationCode;
use App\Services\TextServices;
use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\Redirect;

class CheckoutController extends Controller
{
    public function index()
    {
        $details = AccountServices::checkoutDetails(base64_decode(Session::get('CustomerID')), Session::get('addressID'));
        $cityOption = Content::getCityMunicipalOption($details['province_id']);
        $city = Content::getCityMunicipalityDetails($details['city']);
        return view('pages.checkout',
            [
                'page'    => 'Checkout Page',
                'details' => $details,
                'result'  => ProductServices::showBasketItems(Session::get('orderID'), Session::get('orderBasket'), Session::get('addressID')),
                'orderID' => base64_encode(Session::get('orderID')),
                'serviceType' => (Session::get('transType') == 'Pick-up' || in_array($details['store_code'],config('app.nodel_stores'))) ? 'Pick-up' : 'Delivery',
                'storeCode' => AccountServices::customerAddressAssignedStore(Session::get('addressID')),
                'items'      => static::defaultTrans(),
                'cityOption' => $cityOption,
                'cityID'     => $city[0]->municipal_id
            ]
        );
    }

    public function defaultTrans()
    {
        $trans = Session::get('transType');

        if($trans == 'Delivery Now') :
            $items = [
                'sdate' => '',
                'shour' => date('g'),
                'smin'  => '00',
                'ampm'  => date('A')
            ];
        else :

            $dTime = explode(' ', Session::get('deliveryTime'));
            $time  = explode(':', $dTime[1]);
            $items = [
                'sdate' => $dTime[0],
                'shour' => $time[0],
                'smin'  => $time[1],
                'ampm'  => $dTime[2]
            ];
        endif;

        return $items;
    }

    public function saveOrders(Request $request)
    {
        if($request->input('landmark') != "") :
            $deliveryAddress = [
                'addressID'  => $request->input('addressID'),
                'landmark'   => $request->input('landmark')
            ];

            $result = Account::updateCustomerAddressLandmark($deliveryAddress);
        endif;

        $data = [
            'orderID'     => Session::get('orderID'),
            'customerID'  => base64_decode(Session::get('CustomerID')),
            'orderDate'   => date('Y-m-d'),
            'orderType'   => $request->input('orderType'),
            'orderAmt'    => str_replace(',', '', $request->input('amount')),
            'orderStatus' => 'FLOAT',
            'payOption'   => $request->input('payMethod'),
            'payStatus'   => 'INCOMPLETE',
            'storeCode'   => $request->input('storeCode'),
            'origin'      => 'ONLINE',
            'createdBy'   => 'SYSTEM',
            'createdAt'   => date('Y-m-d H:i:s'),
            'addressID'   => $request->input('addressID'),
            'amtChange'   => $request->input('amtChange'),
            'delTime'     => $request->input('delTime'),
            'remarks'     => $request->input('remarks'),
            'smac'        => $request->input('smac')
        ];

        $save = Product::saveOrder($data);

        if($save == 1) :
            $items = Product::showItemsInBasket(Session::get('orderID'), $request->input('storeCode'));

            foreach($items as $i) :
                $itemData = [
                    'orderID'   => $i->order_id,
                    'itemID'    => $i->item_id,
                    'itemName'  => $i->item_name,
                    'itemPrice' => $i->item_price,
                    'quantity'  => $i->qty,
                    'totAmt'    => $i->total_amount,
                    'promo'     => $i->is_promo
                ];

                Product::saveOrderItems($itemData);
            endforeach;

            Product::saveOrderStatus(Session::get('orderID'), 'FLOAT');
            static::sentReceipt(Session::get('orderID'), $request->input('storeCode'));

            Session::forget('transType');
            Session::forget('deliveryTime');
            Session::forget('orderBasket');
            Session::forget('orderID');
            Session::forget('tempID');
            //Session::forget('addressID');
	        //Session::forget('minimumCharge');
            // Session::forget('deliveryCharge');

            $response = [
                'status'  => 'success',
                'message' => '',
            ];
        else :
            $response = [
                'status'  => 'fail',
                'message' => TextServices::message($save)
            ];
        endif;

        echo json_encode($response);
    }

    public function success(Request $request)
    {
        if(Session::get('isLogged') == false) :
            return view('pages.404',
                [
                    'page' => '404 - Page Not Found'
                ]
            );
        else :
            $orderID = base64_decode($request->segment(2));
            $check = Product::checkOrderID($orderID);

            if($check == 1) :
                return view('pages.checkout_success',
                    [
                        'page'    => 'Checkout Success Page',
                        'data'    => ProductServices::viewOrderItems($orderID),
                        'details' => Product::getOrderDetails($orderID)
                    ]
                );
            else :
                return view('pages.404',
                    [
                        'page' => '404 - Page Not Found'
                    ]
                );
            endif;
        endif;
    }

    public function cancelOrders()
    {
        Session::forget('transType');
        Session::forget('deliveryTime');
        Session::forget('orderBasket');
        Session::forget('orderID');
        Session::forget('tempID');
        Session::forget('addressID');

        return Redirect::to('/');
    }

    public function cancelPendingOrder(Request $request)
    {
        $orderID = $request->input('orderID');
        $status = 'CANCEL';

        $result = Account::updateOrderStatus($orderID, $status);

        if($result == 1) :
            Account::saveOrderStatus($orderID, $status);
            $response = [
                'status'  => 'success',
                'message' => 'You have successfully cancelled your order.'
            ];
        else :
            $response = [
                'status'  => 'fail',
                'message' => 'Unable to process you request due to server error. Try again later!'
            ];
        endif;

        echo json_encode($response);
    }

    public function sentReceipt($orderID, $storeCode)
    {
        $items  = ProductServices::viewOrderItems($orderID);
        $detail = Product::getOrderDetails($orderID);
        $name   = $detail[0]->customer_name;
        $email  = $detail[0]->email_address;
        $dc     = Content::getDeliveryCharge();
        $noDelChargeStore = explode(',', $dc[0]->store_code);
        $charge = (in_array($storeCode, $noDelChargeStore) ? 0 : $dc[0]->dc_amount);

        $data = [
            'name'    => $name,
            'orderID' => $orderID,
            'data'    => $items,
            'detail'  => $detail,
            'charges' => $charge
        ];

        Mail::to($email)->send(new SendOrderReceipt($data));
    }

    public function testMail()
    {
        $name = 'Jeko';
        $email = 'no_reply@atp.ph';

        $data = [
            'id'   => '000001',
            'name' => $name,
            'code' => '1707-ABCDEF',
            'store' => '1707'
        ];

        Mail::to($email)->send(new SendConfirmationCode($data));

        if(count(Mail::failures()) > 0) :
            echo 'Mail not sent';
        else :
            echo 'Successfully sent mail';
        endif;
    }
}
