<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Services\OrderServices;
use App\Services\HelperServices;
use App\Services\CustomerServices;
use App\Orders;
use App\Cms;
use Illuminate\Support\Facades\Session;

use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Facades\Mail;
use File;
use View;
use Illuminate\Support\Facades\Storage;
use App\Mail\SendOrderReceipt;
use App\Mail\SendThankYou;
use App\Mail\SendOrderReceiveConfirmation;
use App\Mail\SendOrderReadyConfirmation;

class OrdersController extends Controller
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
        Session::forget(['orderID']);
        return view('pages.orders.index',
            [
                'page'  => 'Order Page',
                'items' => OrderServices::getOrders(base64_decode(Session::get('LocationCode')))
            ]
        );
    }

    public function orderForm()
    {
        if(!is_null(Session::get('orderID'))) :
            $orderID = Session::get('orderID');
        else :
            $orderID = time();
            Session::put('orderID', $orderID);
            Session::save();
        endif;

        return view('pages.orders.order_form',
            [
                'page'    => 'Order Form Page',
                'items'   => OrderServices::productItems(base64_decode(Session::get('LocationCode'))),
                'orderID' => $orderID
            ]
        );
    }

    public function cartItemJSON(Request $request)
    {
        $encode = array();
        $items  = Orders::getCartItems($request->segment(4));
        $storeCode = Session::get('LocationCode');
        $dc = Orders::deliveryCharge();
        $noDelChargeStore = explode(',', $dc[0]->store_code);
        $charge = (in_array($storeCode, $noDelChargeStore) ? 0 : $dc[0]->dc_amount);
        $count  = count($items);
        $total  = 0;

        if($count > 0) :
            foreach($items as $row) :
                $total += $row->total_amount;
                $encode[] = array_map("utf8_encode", (array)$row);
            endforeach;
        endif;

        $totalAmt = ($count == 0) ? '0.00' : $total;

        echo HelperServices::viewJSONTable_Data($count, $encode, $totalAmt, $charge);
    }

    public function saveOrders(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        if($request->input('customerID') == "") :
            $data = [
                'lastName'   => $request->input('lastName'),
                'firstName'  => $request->input('firstName'),
                'email'      => $request->input('email'),
                'contactNum' => $request->input('mobileNum'),
                'password'   => password_hash('pass123', PASSWORD_DEFAULT),
                'storeCode'  => base64_decode(Session::get('LocationCode')),
                'regFrom'    => 'MANUAL',
                'regDate'    => date('Y-m-d H:i:s'),
                'addType'    => $request->input('type'),
                'address'    => $request->input('address'),
                'city'       => $request->input('city'),
                'province'   => $request->input('province'),
                'landmarks'  => $request->input('landmark'),
                'code'       => base64_decode(Session::get('LocationCode')).'-'.substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 6),
                'customerID' => NULL,
                'addressID'  => NULL,
                'isConfirm'  => 1,
                'isActive'   => 1
            ];

            $result = CustomerServices::saveCustomerData($data);

            $addressID = $result[0]->address_id;
            $custID    = $result[0]->customer_id;

        else :

            if($request->input('addressID') != "") :
                $addressID = $request->input('addressID');
                $custID    = $request->input('customerID');
            else :
                $addressData = [
                    'customerID' => $request->input('customerID'),
                    'addType'    => $request->input('type'),
                    'address'    => $request->input('address'),
                    'city'       => $request->input('city'),
                    'province'   => $request->input('province'),
                    'landmarks'  => $request->input('landmark'),
                    'storeCode'  => base64_decode(Session::get('LocationCode')),
                    'code'       => base64_decode(Session::get('LocationCode')).'-'.substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 6),
                ];

                $addressID = CustomerServices::saveCustoreAddressData($addressData);
                $custID    = $request->input('customerID');
            endif;
        endif;

        $orderData = [
            'orderID'      => $request->input('orderID'),
            'customerID'   => $custID,
            'orderDate'    => date('Y-m-d'),
            'orderType'    => $request->input('transType'),
            'orderAmount'  => $request->input('totalAmount'),
            'orderStatus'  => config('app.received'),
            'payOption'    => 'Cash on Delivery',
            'payStatus'    => 'INCOMPLETE',
            'origin'       => 'MANUAL',
            'createdBy'    => base64_decode(Session::get('Emp_Name')),
            'createAt'     => date('Y-m-d H:i:s'),
            'addressID'    => $addressID,
            'changeFor'    => $request->input('changeFor'),
            'deliveryTime' => $request->input('schedule'),
            'remarks'      => $request->input('remarks'),
        ];

        $response = OrderServices::saveOrderData($orderData);
        $this->orderReceiptMail($request->input('orderID'));
        $check = $this->checkOrderSlip($request->input('orderID'));
        if(!$check['isExist']) :
            $this->generatePDF($request->input('orderID'));
        endif;

        echo json_encode($response);
    }

    public function addToBasket(Request $request)
    {
        $items = explode(',', $request->input('items'));
        $totalAmt = 0;
        $ids = [];
        foreach($items as $item) :
            $i = explode('@@', $item);
            $amount = $i[5] * $i[3];
            $totalAmt += $amount;
            $ids[] = $i[1];
            $data = [
                'orderID'   => $i[0],
                'itemID'    => $i[1],
                'itemName'  => $i[2],
                'itemPrice' => $i[3],
                'itemQty'   => $i[5],
                'totalAmt'  => $amount,
                'promo'     => $i[4]
            ];

            Orders::saveItemsToBasket($data);
        endforeach;

        $a = OrderServices::basketItems($request->input('id'));

        $response = [
            'status'      => 'ok',
            'message'     => '',
            'totalAmount' => $a['amount'],
            'itemIDs'     => $ids
        ];

        echo json_encode($response);
    }

    public function updateBasket(Request $request)
    {
        $data = [
            'orderItemID' => $request->input('orderItemID'),
            'itemPrice'   => $request->input('itemPrice'),
            'qty'         => $request->input('qty'),
            'orderID'     => $request->input('orderID'),
        ];

        $result = Orders::updateBasketItems($data);
        $item = OrderServices::updateOrderAmount($request->input('orderID'));

        if($result == 2) :

            $message = ($request->input('action') == 'remove') ? OrderServices::errorMessage(-4) : OrderServices::errorMessage(-5);
            $response = [
                'status'      => 'ok',
                'message'     => $message,
                'totalAmount' => $item['totalAmount'],
                'itemCount'   => $item['itemCount']
            ];
        else :
            $response = [
                'status'      => 'fail',
                'message'     => OrderServices::errorMessage($result),
                'totalAmount' => $item['totalAmount'],
                'itemCount'   => $item['itemCount']
            ];
        endif;

        echo json_encode($response);
    }

    public function reviewBasket(Request $request)
    {
        return view('pages.orders.modals.review',
            [
                'data'      => OrderServices::basketItems($request->segment(4)),
                'province'  => Cms::getProvince(),
                'customers' => Orders::getCustomers(NULL, 1, NULL, base64_decode(Session::get('LocationCode'))),
                'orderID'   => $request->segment(4)
            ]
        );
    }

    public function orderDetails(Request $request)
    {
        $details = Orders::getOrderDetails($request->segment(3));

        if($details[0]->origin == 'ONLINE' && $details[0]->order_status == 'FLOAT') :
            $this->generatePDF($request->segment(3));
        endif;

        $view = ($details[0]->order_status == 'FLOAT') ? 'order_details' : 'validate_order';

        return view('pages.orders.modals.'.$view,
            [
                'data'    => OrderServices::basketItems($request->segment(3)),
                'details' => $details,
                'orderID' => $request->segment(3)
            ]
        );
    }

    public function createPDF(Request $request)
    {
        $orderID = $request->input('id');
        $check = $this->checkOrderSlip($orderID);

        if(!$check['isExist']) :
            $this->generatePDF($orderID);
        endif;

        echo json_encode($check['fileName']);
    }

    public function validateOrder(Request $request)
    {
        $details = Orders::getOrderDetails($request->segment(3));

        return view('pages.orders.modals.validate_order',
            [
                'data'    => OrderServices::basketItems($request->segment(3)),
                'details' => $details,
                'orderID' => $request->segment(3)
            ]
        );
    }

    /**
     * CHECK ORDER SLIP PDF
     */
    public function checkOrderSlip($orderID)
    {
        $fileName = $orderID.'.pdf';
        $path = 'public/pdfs/';
        $isExist = Storage::exists($path.$fileName);

        $response = [
            'isExist' => $isExist,
            'fileName' => $fileName
        ];

        return $response;
    }

    public function punchReceiptForm(Request $request)
    {
        return view('pages.orders.modals.receipt',
            [
                'orderID' => $request->segment(3)
            ]
        );
    }

    public function orderReceiptMail($orderID)
    {
        $items  = OrderServices::basketItems($orderID);
        $detail = Orders::getOrderDetails($orderID);
        $name = $detail[0]->customer_name;
        $email = $detail[0]->email_address;

        $data = [
            'name'    => $name,
            'orderID' => $orderID,
            'data'    => $items,
            'detail'  => $detail
        ];

        Mail::to($email)->send(new SendOrderReceipt($data));
    }

    public function generatePDF($orderID)
    {
        $detail = Orders::getOrderDetails($orderID);
        $deliveryDate = ($detail[0]->delivery_time == 'PROMISE TIME') ? $detail[0]->order_date : date('Y-m-d', strtotime($detail[0]->delivery_time));
        $deliveryTime = ($detail[0]->delivery_time == 'PROMISE TIME') ? '1pm-3pm' : date('g:i A', strtotime($detail[0]->delivery_time));
        $deliveryCharge = Orders::deliveryCharge();

        $data = [
            'data'    => OrderServices::basketItems($orderID),
            'details' => $detail,
            'dDate'   => $deliveryDate,
            'dTime'   => $deliveryTime,
            'charges' => $deliveryCharge
        ];

        $fileName = $orderID.'.pdf';
        $path = 'public/pdfs/'.$fileName;
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('pdf.pdf_order_slip', $data);

        Storage::put($path, $pdf->output());
    }

    public function printView(Request $request)
    {
        $orderID = $request->segment(3);
        $detail = Orders::getOrderDetails($orderID);
        $deliveryDate = ($detail[0]->delivery_time == 'PROMISE TIME') ? $detail[0]->order_date : date('Y-m-d', strtotime($detail[0]->delivery_time));
        $deliveryTime = ($detail[0]->delivery_time == 'PROMISE TIME') ? '1pm-3pm' : date('g:i A', strtotime($detail[0]->delivery_time));
        $deliveryCharge = Orders::deliveryCharge();

        $data = [
            'data'    => OrderServices::basketItems($orderID),
            'details' => $detail,
            'dDate'   => $deliveryDate,
            'dTime'   => $deliveryTime,
            'charges' => $deliveryCharge
        ];

        return view('pdf.order_slip', $data);
    }

    public function orderStatus(Request $request)
    {
        $orderID   = $request->input('orderID');
        $receipt   = ($request->input('receipt') == '') ? NULL : $request->input('receipt');
        $payStatus = ($request->input('payStatus') == '') ? NULL : $request->input('payStatus');
        $status    = config('app.'.$request->input('status'));

        $detail = Orders::getOrderDetails($orderID);
        $name = $detail[0]->customer_name;
        $email = $detail[0]->email_address;

        if($request->input('status') == 'received') :
            $data = [ 'name' => $name ];
          Mail::to($email)->send(new SendOrderReceiveConfirmation($data));
        endif;

        if($request->input('status') == 'pick-up' || $request->input('status') == 'delivery') :
            $data = [
                'name'   => $name,
                'status' => $request->input('status'),
                'store'  => $detail[0]->store_name
            ];
           Mail::to($email)->send(new SendOrderReadyConfirmation($data));
        endif;

        if($request->input('status') == 'picked' || $request->input('status') == 'delivered') :
            $data = [ 'name' => $name];
            $this->updateStoreItemsQty($orderID, base64_decode(Session::get('LocationCode')));
            Mail::to($email)->send(new SendThankYou($data));
        endif;

        $response = OrderServices::orderStatus($orderID, $status, $receipt, $payStatus, base64_decode(Session::get('LocationCode')));

        echo json_encode($response);
    }

    public function cashChangeForm(Request $request)
    {
        return view('pages.orders.modals.cash_change',
            [
                'orderID' => $request->segment(4),
            ]
        );
    }

    public function saveCashChange(Request $request)
    {
        $data = [
            'orderID'    => $request->input('orderID'),
            'amount'     => $request->input('amount'),
            'modifiedBy' => base64_decode(Session::get('Emp_Name')),
        ];

        $result = Orders::insertCashChange($data);

        if($result == 1) :
            $response = [
                'status'  => 'success',
                'message' => ''
            ];
        else :
            $response = [
                'status'  => 'fail',
                'message' => OrderServices::errorMessage($result)
            ];
        endif;

        echo json_encode($response);
    }

    public function checker()
    {
        $result = Orders::checkNewOrders(base64_decode(Session::get('LocationCode')));

        $ids   = '';
        $count = 0;
        $tc    = 0;
        foreach($result as $row) :
            $ids .= $row->order_id.',';
        endforeach;

        $orderID = substr($ids, 0, -1);

        $response = [
            'orderCount' => count($result),
            'tagCount'   => Orders::countTagOrders($orderID),
            'ids'        => $orderID
        ];

        echo json_encode($response);
    }

    public function tagOrders(Request $request)
    {
        $ids = explode(',', $request->input('ids'));

        foreach($ids as $id) :
            Orders::tagNewOrders($id);
        endforeach;

        echo json_encode(['status' => 'ok']);
    }

    public function updateInventory(Request $request)
    {
        $orderID   = $request->segment(4);
        $storeCode = base64_decode(Session::get('LocationCode'));

        $this->updateStoreItemsQty($orderID, $storeCode);

        return Redirect::to('/orders');
    }

    public static function updateStoreItemsQty($orderID, $storeCode)
    {
        $result  = OrderServices::getOrderItems($orderID);
        $ids     = explode(',', $result['ids']);
        $items   = $result['items'];
        $inv     = Orders::getProductItems(NULL, $storeCode, NULL);

        foreach($inv as $row) :
            if(in_array($row->item_id, $ids)) :
                $qty = $row->stocks_on_hand - $items[$row->item_id]['qty'];
                $data = [
                    'itemID'    => $row->item_id,
                    'qty'       => $qty,
                    'storeCode' => $storeCode
                ];

                Orders::updateStoreItemsInvQty($data);
            endif;
        endforeach;
    }


    public function orderBasketModifyWithLogs(Request $request)
    {
        $data = [
           'orderItemID' => $request->input('oID'),
           'qty'         => ($request->input('qty') == '') ? 0 : $request->input('qty'),
           'itemPrice'   => $request->input('price'),
           'orderID'     => $request->input('id')
        ];

        $result = OrderServices::updateBasketDetails($data);

        if($result == 'success') :

            $message = ($request->input('action') == 'delete') ? $request->input('oID') .' has been removed.' : 'Order ID '.$request->input('id').' quantity  has been updated from '.$request->input('curQty').' to '. $request->input('qty').'.';
            $logs = [
                'orderID'   => $request->input('id'),
                'itemID'    => $request->input('itemID'),
                'message'   => $message,
                'createdBy' => base64_decode(Session::get('Emp_Name'))
            ];

            $save = Orders::saveOrderLogs($logs);
            $a = OrderServices::basketItems($request->input('id'));

            if($save == 1) :
                $response = [
                    'status'  => 'success',
                    'message' => 'Logs successfuly save',
                    'amount'  => $a['amountDue']
                ];
            else :
                $response = [
                    'status'  => 'fail',
                    'message' => HelperServices::errorMessage(-4),
                    'amount'  => $a['amountDue']
                ];
            endif;
        else :
            $response = [
                'status'  => 'fail',
                'message' => HelperServices::errorMessage(-4),
                'amount'  => $a['amountDue']
            ];
        endif;

        echo json_encode($response);
    }

    public function ordersCancelForm(Request $request)
    {
        return view('pages.orders.modals.cancel_remarks',
            [
                'orderID' => $request->segment(3),
            ]
        );
    }

    public function saveCancelRemarks(Request $request)
    {
        $data = [
            'orderID'   => $request->input('orderID'),
            'remarks'   => $request->input('remarks'),
            'createdBy' => base64_decode(Session::get('Emp_Name'))
        ];

        $result = Orders::saveCancelRemarks($data);

        if($result == 1) :
            $response = [
                'status'   => 'success',
                'messasge' => 'Successfully save!'
            ];
        else :
            $response = [
                'status'   => 'fail',
                'messasge' => HelperServices::errorMessage(-4),
            ];
        endif;

        echo json_encode($response);
    }

    public function editOrder(Request $request)
    {
        $orderID = $request->segment(3);

        return view('pages.orders.edit_order',
            [
                'page'    => 'Edit Order Page',
                'items'   => Cms::getProductItems(NULL, 1),
                'details' => Orders::getOrderDetails($orderID),
                'data'    => OrderServices::basketItems($orderID),
                'orderID' => $orderID,
            ]
        );
    }

    public function manualAdd(Request $request)
    {
        $orderID = $request->segment(3);
        return view('pages.orders.modals.add_order',
        [
            'items'   => OrderServices::productItems(base64_decode(Session::get('LocationCode'))),
            'orderID' => $orderID
        ]);
    }

    public function addToBasketNew(Request $request)
    {
        $items = explode(',', $request->input('items'));
        $oid = $request->input('id');
        // dd($oid);
        $totalAmt = 0;
        $ids = [];
        foreach($items as $item) :
            $i = explode('@@', $item);
            $amount = $i[5] * $i[3];
            $totalAmt += $amount;
            $ids[] = $i[1];
            $data = [
                'orderID'   => $oid,
                'itemID'    => $i[1],
                'itemName'  => $i[2],
                'itemPrice' => $i[3],
                'itemQty'   => $i[5],
                'totalAmt'  => $amount,
                'promo'     => $i[4]
            ];

            Orders::saveItemsToBasket($data);
        endforeach;

        $a = OrderServices::basketItems($request->input('id'));

        $item = [
            'orderID' => $oid ,
            'amount'  => $a['amount']
        ];

        $update = Orders::updateOrderAmount($item);



        $response = [
            'status'      => 'ok',
            'message'     => '',
            'totalAmount' => $a['amount'],
            'itemIDs'     => $ids
        ];

        echo json_encode($response);
    }
}
