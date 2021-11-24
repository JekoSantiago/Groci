<?php

namespace App\Services;
use App\Models\Account;
use App\Models\Content;
use App\Models\Product;
use Illuminate\Support\Facades\Session;


class AccountServices
{
    public static function doLogin($email, $password)
    {
        $user = Account::checkUser($email);

        if(empty($user)) :
            $response = -1;
        else :
            if(password_verify($password, $user[0]->password)) :
                $response = $user;
            else :
                $response = -2;
            endif;
        endif;

        return $response;
    }

    public static function doRegister($data)
    {
        $result = Account::registerCustomer($data);

        return $result;
    }

    public static function customerDetails($customerID)
    {
        return Account::getCustomerDetails($customerID);
    }

    public static function checkCustomerStatus($customerID)
    {
        $result = Account::getCustomerDetails($customerID);

        return $result[0]->is_active;
    }

    public static function updateCustomerDetails($data)
    {
        $result = Account::modifyCustomerDetail($data);

        if($result == 1) :
            $response = [
                'status'  => 'success',
                'message' => 'You have successfully update your account.'
            ];
        else :
            $response = [
                'status'  => 'fail',
                'message' => 'Unable to modify your account due to server error. Try again alter!'
            ];
        endif;

        return $response;
    }

    public static function getCustomerDetails($email, $storeCode)
    {
        $result = Account::getCustomerDetails(NULL, $email);
        $details = Account::getCustomerAddress(NULL, $email, NULL, $storeCode);
        $count = count($result);
        $countDetails = count($details);
        $response = [
            'customerID' => (empty($result)) ? NULL : $result[0]->customer_id,
            'firstname'  => (empty($result)) ? NULL : $result[0]->firstname,
            'lastname'   => (empty($result)) ? NULL : $result[0]->lastname,
            'contactNum' => (empty($result)) ? NULL : $result[0]->contact_num,
            'count'      => $count,
            'address'    => (is_null($storeCode) || $countDetails == 0) ? '' : $details[0]->address,
            //'city'       => (is_null($storeCode) || $countDetails == 0) ? '' : $details[0]->city,
            //'prov_id'    => (is_null($storeCode) || $countDetails == 0) ? static::provinceOption() : $details[0]->province_id.'-'.$details[0]->province_name,
            'landmarks'  => (is_null($storeCode) || $countDetails == 0) ? '' : $details[0]->landmarks,
            'type'       => (is_null($storeCode) || $countDetails == 0) ? '' : $details[0]->type,
            'store_name' => (is_null($storeCode) || $countDetails == 0) ? '' : $details[0]->store_name,
            //'stores'     => (empty($result)) ? '<option value=""></option>' : static::selectedStore($details)
        ];

        return $response;
    }

    public static function checkoutDetails($customerID, $addressID)
    {
        $customer = Account::getCustomerDetails($customerID);
        $row  = Account::getCustomerAddress(NULL, NULL, $addressID);

        $response = [
            'firstname'   => $customer[0]->firstname,
            'lastname'    => $customer[0]->lastname,
            'email'       => $customer[0]->email_address,
            'mobile_no'   => $customer[0]->contact_num,
            'type'        => $row[0]->type,
            'address'     => $row[0]->address,
            'city'        => $row[0]->city,
            'province_id' => $row[0]->province_id,
            'landmarks'   => $row[0]->landmarks,
            'store_code'  => $row[0]->store_code,
            'address_id'  => $row[0]->address_id
        ];

        return $response;
    }

    public static function customerAddressAssignedStore($addressID)
    {
        $row  = Account::getCustomerAddress(NULL, NULL, $addressID);

        return $row[0]->store_code;
    }

    public static function customerAddress($type, $email, $storeCode)
    {
        $result = Account::getCustomerAddress($type, $email, NULL, $storeCode);

        $response = [
            'count'     => count($result),
            'addressID' => (empty($result)) ? NULL : $result[0]->address_id,
            'address'   => (empty($result)) ? NULL : $result[0]->address,
            'city'      => (empty($result)) ? NULL : $result[0]->city,
            'landmarks' => (empty($result)) ? NULL : $result[0]->landmarks,
            'province'  => (empty($result)) ? static::provinceOption() : static::selectedProvince($result),
            'stores'    => (empty($result)) ? '<option value=""></option>' : static::selectedStore($result)
        ];

        return $response;
    }

    public static function customerOrders($customerID, $storeCode)
    {
        $encode = [];
        $items = Account::customerOrders($customerID, $storeCode);

        foreach($items as $i) :
            $i->bg = static::statusBG($i->order_status);
            $encode[] = array_map("utf8_encode", (array)$i);
        endforeach;

        return $encode;
    }

    public static function provinceOption()
    {
        $result = Content::getProvince();
        $option = '<option value=""></option>';
        foreach($result as $p) :
            $option .= '<option value="'.$p->province_id.'-'.$p->province_name.'">'.$p->province_name.'</option>';
        endforeach;

        return $option;
    }

    public static function defaultStoreOption($segment = NULL)
    {
        $stores = Content::getStores(NULL, 1);
        $option = '<option value=""></option>';

        foreach($stores as $row) :
            $select = ($row->store_code == base64_decode($segment)) ? 'selected=selected' : '';
            $option .= '<option value="'.$row->store_code.'" '.$select.'>'.$row->store_name.'</option>';
        endforeach;

        return $option;
    }

    public static function selectedProvince($item)
    {
        $province = Content::getProvince();
        $current  = ($item == "") ? NULL : $item[0]->province_id.'-'.$item[0]->province_name;
        $options = '<option value=""></option>';

        foreach($province as $p) :
            $select = ($p->province_id.'-'.$p->province_name == $current) ? 'selected=selected' : '';
            $options .= '<option value="'.$p->province_id.'-'.$p->province_name.'" '.$select.'>'.$p->province_name.'</option>';
        endforeach;

        return $options;
    }

    public static function selectedStore($item)
    {
        if($item == "") :
            $province = NULL;
            $storeCode = NULL;
        else :
            $province = ($item[0]->province_id == 51) ? 'MOA,NCR,NCR NORTH,METRO MANILA' : $item[0]->province_name;
            $storeCode = $item[0]->store_code;
        endif;

        $stores = Content::getStorePerProvince($province);
        $option = '<option value=""></option>';

        foreach($stores as $row) :
            $select = ($row->store_code == $storeCode) ? 'selected=selected' : '';
            $option .= '<option value="'.$row->store_code.'" '.$select.'>'.$row->store_name.'</option>';
        endforeach;

        return $option;
    }

    public static function loginCustomerAddress($email, $storeCode)
    {
        $result = Account::getCustomerAddress(NULL, $email, NULL, $storeCode);
        $count  = count($result);
        $option = '<option value=""></option>';
        foreach($result as $row) :
            $select = ($storeCode == '') ? '' : 'selected="selected"';
            $option .= '<option value="'.$row->address_id.'" '.$select.'>'.$row->store_name.' - '.$row->address.' '.$row->city.' '.$row->province_name.'</option>';
        endforeach;

        $response = [
            'address' => $option,
            'count'   => $count
        ];

        return $response;
    }

    public static function saveCustomerAddress($data)
    {
        $result = Account::saveCustomerAddress($data);

        return $result;
    }

    public static function reOrderItems($soID, $orderBasket)
    {
        if($orderBasket == false) :
            $orderID = time();
            $tempID  = Product::saveTempOrderID($orderID);
            $details = Product::getOrderDetails($soID);

            Session::put('transType', $details[0]->order_type);
            Session::put('deliveryTime', $details[0]->delivery_time);
            Session::put('orderBasket', true);
            Session::put('orderID', $orderID);
            Session::put('tempID', $tempID);
            Session::save();

        else :
            $orderID = Session::get('orderID');
            $tempID  = Session::get('tempID');
        endif;

        $items = Account::viewOrderItems($soID);
        $errCount = 0;
        foreach($items as $i) :
            $data = [
                'tempID'    => $tempID,
                'itemID'    => $i->item_id,
                'itemName'  => $i->item_name,
                'itemPrice' => $i->item_price,
                'itemQty'   => $i->qty,
                'totalAmt'  => $i->total_amount,
                'isPromo'   => $i->is_promo,
            ];

            $result = Product::saveTempOrderItems($data);
            $errCount += ($result[0]->_RETURN == 2) ? 1 : 0;
        endforeach;

        return $errCount;
    }

    public static function getCode($email)
    {
        $result = Account::getCode($email);

        if(count($result) > 0) :
            $arr = [
                'count' => count($result),
                'code'  => $result[0]->confirmation_code,
                'scode' => $result[0]->store_code
            ];
        else :
            $arr = [
                'count' => 0,
                'code'  => NULL,
                'scode' => NULL
            ];
        endif;

        return $arr;
    }

    private static function statusBG($status)
    {
        switch($status) {
            case 'RECEIVED':
                $bg = 'bg-yellow';
                break;
            case 'ON PROCESS':
                $bg = 'bg-yellow';
                break;
            case 'OUT FOR DELIVERY':
                $bg = 'bg-blue';
                break;
            case 'READY FOR PICK-UP':
                $bg = 'bg-blue';
                break;
            case 'DELIVERED':
                $bg = 'bg-green';
                break;
            case 'PICKED':
                $bg = 'bg-green';
                break;
            case 'CLOSE':
                $bg = 'bg-green';
                break;
            case 'CANCEL':
                $bg = 'bg-red';
                break;
            default:
                $bg = 'bg-default';
        }

        return $bg;
    }

    public static function orderCharges($addressID)
    {
        $row = Account::getCustomerAddress(NULL, NULL, $addressID);
        $storeCode = $row[0]->store_code;
        $mc = Content::getMinimumChargePerStore([0,$storeCode]);
        $dc = Content::getDeliveryCharge();

        // dd($mc);
        $minCharge = ($mc[0]->MinimumCharge) ? : 0;

        $noDelChargeStore = explode(',', $dc[0]->store_code);
        $delCharge = (in_array($storeCode, $noDelChargeStore) ? 0 : $dc[0]->dc_amount);

        $charges = [
            'minimumCharge'  => $minCharge,
            'deliveryCharge' => $delCharge
        ];

        return $charges;
    }

    public static function orderCharges_old($addressID)
    {
        $row = Account::getCustomerAddress(NULL, NULL, $addressID);
        $storeCode = $row[0]->store_code;
        $mc = Content::getMinimumCharge();
        $dc = Content::getDeliveryCharge();

        $noMinChargeStore = explode(',', $mc[0]->store_code);
        $minCharge = (in_array($storeCode, $noMinChargeStore) ? 0 : $mc[0]->amount);

        $noDelChargeStore = explode(',', $dc[0]->store_code);
        $delCharge = (in_array($storeCode, $noDelChargeStore) ? 0 : $dc[0]->dc_amount);

        $charges = [
            'minimumCharge'  => $minCharge,
            'deliveryCharge' => $delCharge
        ];

        return $charges;
    }

    public static function logStore($addressID)
    {
        if(is_null($addressID)) :
            $storeName = '';
        else :
            $row = Account::getCustomerAddress(NULL, NULL, $addressID);
            $storeCode = $row[0]->store_code;
            $detail = Content::getStores($storeCode);
            $storeName = $detail[0]->store_name;
        endif;

        return $storeName;
    }

    public static function orderStatus($status)
    {
        switch($status) {
            case 'RECEIVED':
                $text = 'Order received @ ';
                break;
            case 'ON PROCESS':
                $text = 'Order processed @ ';
                break;
            case 'OUT FOR DELIVERY':
                $text = 'Order out for delivery @ ';
                break;
            case 'READY FOR PICK-UP':
                $text = 'Order ready for pick-up @ ';
                break;
            case 'DELIVERED':
                $text = 'Order delivered @ ';
                break;
            case 'PICKED':
                $text = 'Order picked-up @ ';
                break;
            case 'CLOSE':
                $text = 'Order closed @ ';
                break;
            case 'CANCEL':
                $text = 'Order cancelled @ ';
                break;
            default:
                $text = 'Order submit @ ';
                break;
        }

        return $text;
    }
}
