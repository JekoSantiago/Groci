<?php

namespace App\Services;
use App\Models\Product;
use App\Models\Account;
use Illuminate\Support\Facades\Session;


class ProductServices
{
    public static function productItems($catID, $storeCode, $searchKey, $isLogged)
    {
        $stores = ($isLogged == 0) ? NULL : static::customerRegisteredStores(Session::get('email'));
        $result = Product::getProductItems($catID, $storeCode, $searchKey, $isLogged, $stores);
        $encode = [];

        foreach($result as $row) :

            if(is_null($row->reg_price)) :
                $row->promo = $row->is_promo;
                $row->regular_price = (is_null($row->nat_price)) ? '0.00' : $row->nat_price;
                $row->promo_price = (is_null($row->nat_promo_price)) ? '0.00' : $row->nat_promo_price;
            else :
                $row->promo = $row->reg_is_promo;
                $row->regular_price = (is_null($row->reg_price)) ? '0.00' : $row->reg_price;
                $row->promo_price = (is_null($row->reg_promo_price)) ? '0.00' : $row->reg_promo_price;
            endif;

            $price = ($row->promo == 1) ? $row->promo_price : $row->regular_price;
            $row->params = $row->item_id.'@@'.$row->item_name.'@@'.$price.'@@'.$row->promo.'@@'.$row->category_id;
            $row->stocks_on_hand = (is_null($row->stocks_on_hand)) ? 0 : $row->stocks_on_hand;
            $row->pre_order_qty  = (is_null($row->pre_order_qty)) ? 0 : $row->pre_order_qty;
            $row->img = ($row->img_pic == NULL) ? 'no-image-available.png' : $row->img_pic;
			$encode[] = array_map("utf8_encode", (array)$row);
        endforeach;

		return $encode;
    }

    public static function topRandomItems($storeCode, $isLogged)
    {
        $stores = ($isLogged == 0) ? NULL : static::customerRegisteredStores(Session::get('email'));
        $result = Product::topSaverItems($storeCode, $isLogged, $stores);
        $encode = [];

        foreach($result as $row) :
            if(is_null($row->reg_price)) :
                $row->promo = $row->is_promo;
                $row->regular_price = (is_null($row->nat_price)) ? '0.00' : $row->nat_price;
                $row->promo_price = (is_null($row->nat_promo_price)) ? '0.00' : $row->nat_promo_price;
            else :
                $row->promo = $row->reg_is_promo;
                $row->regular_price = (is_null($row->reg_price)) ? '0.00' : $row->reg_price;
                $row->promo_price = (is_null($row->reg_promo_price)) ? '0.00' : $row->reg_promo_price;
            endif;

            $price = ($row->promo == 1) ? $row->promo_price : $row->regular_price;

            $row->params = $row->item_id.'@@'.$row->item_name.'@@'.$price.'@@'.$row->promo.'@@'.$row->category_id;
            $row->stocks_on_hand = (is_null($row->stocks_on_hand)) ? 0 : $row->stocks_on_hand;
            $row->pre_order_qty  = (is_null($row->pre_order_qty)) ? 0 : $row->pre_order_qty;
            $row->img = ($row->img_pic == NULL) ? 'no-image-available.png' : $row->img_pic;
			$encode[] = array_map("utf8_encode", (array)$row);
        endforeach;

		return $encode;
    }

    public static function promoItems($storeCode, $isLogged)
    {
        $stores = ($isLogged == 0) ? NULL : static::customerRegisteredStores(Session::get('email'));
        $result = Product::getPromoItems($storeCode, $isLogged, $stores);
        $encode = [];

        foreach($result as $row) :

            if(is_null($row->reg_price)) :
                $row->promo = $row->is_promo;
                $row->regular_price = (is_null($row->nat_price)) ? '0.00' : $row->nat_price;
                $row->promo_price = (is_null($row->nat_promo_price)) ? '0.00' : $row->nat_promo_price;
            else :
                $row->promo = $row->reg_is_promo;
                $row->regular_price = (is_null($row->reg_price)) ? '0.00' : $row->reg_price;
                $row->promo_price = (is_null($row->reg_promo_price)) ? '0.00' : $row->reg_promo_price;
            endif;

            $price = ($row->promo == 1) ? $row->promo_price : $row->regular_price;
            $row->params = $row->item_id.'@@'.$row->item_name.'@@'.$price.'@@'.$row->promo.'@@'.$row->category_id;
            $row->stocks_on_hand = (is_null($row->stocks_on_hand)) ? 0 : $row->stocks_on_hand;
            $row->pre_order_qty  = (is_null($row->pre_order_qty)) ? 0 : $row->pre_order_qty;
            $row->img = ($row->img_pic == NULL) ? 'no-image-available.png' : $row->img_pic;
			$encode[] = array_map("utf8_encode", (array)$row);
        endforeach;

		return $encode;
    }

    public static function countBasketItems($orderID, $orderBasket, $addressID)
    {
        if(!$orderBasket) :
            $count = 0;
        else :
            $storeCode = AccountServices::customerAddressAssignedStore($addressID);
            $result = Product::showItemsInBasket($orderID, $storeCode);
            $count = count($result);
        endif;

        return $count;
    }

    public static function showBasketItems($orderID, $orderBasket, $addressID)
    {
        if(!$orderBasket) :
            $count = 0;
            $encode = NULL;
            $amountDue = 0.00;
        else :
            $storeCode = AccountServices::customerAddressAssignedStore($addressID);
            $result = Product::showItemsInBasket($orderID, $storeCode);

            $count = count($result);
            $encode = [];
            $amount = 0;
            foreach($result as $row) :
                $amount += $row->total_amount;
                $row->img = ($row->img_pic == NULL) ? 'no-image-available.png' : $row->img_pic;
                $encode[] = array_map("utf8_encode", (array)$row);
            endforeach;

            $amountDue = $amount;
        endif;

        $response = [
            'cntItems' => $count,
            'items'    => $encode,
            'amount'   => $amountDue,
            'd_charge' => ContentServices::deliveryCharge(),
            'm_charge' => ContentServices::minimumCharge(),
            'totalAmt' => $amountDue + ContentServices::deliveryCharge()
        ];

        return $response;
    }

    public static function runningAmt($orderID, $orderBasket, $addressID)
    {
        if(!$orderBasket) :
            $amountDue = 0.00;
        else :
            $storeCode = AccountServices::customerAddressAssignedStore($addressID);
            $result = Product::showItemsInBasket($orderID, $storeCode);
            $amount = 0;
            foreach($result as $row) :
                $amount += $row->total_amount;
            endforeach;

            $amountDue = $amount;
        endif;

        $totalAmount = ($amountDue >= 10000) ? substr($amountDue, 0, 2).'K+' : number_format($amountDue, 2);
        return $totalAmount;
    }

    public static function addItemToCart($orderBasket, $param, $qty, $storeCode)
    {
        if($orderBasket == false) :
            $orderID = time();
            $tempID  = Product::saveTempOrderID($orderID);

            Session::put('orderBasket', true);
            Session::put('orderID', $orderID);
            Session::put('tempID', $tempID);
            Session::save();

        else :
            $orderID = Session::get('orderID');
            $tempID  = Session::get('tempID');
        endif;

        $items = explode('@@', base64_decode($param));
        $price = Product::itemPricePerStore($items[0], $storeCode);

        $data = [
            'tempID'    => $tempID,
            'itemID'    => $items[0],
            'itemName'  => $items[1],
            'itemPrice' => number_format($price, 2),
            'itemQty'   => $qty,
            'totalAmt'  => ($qty * floatval($price)),
            'isPromo'   => $items[3],
        ];

        $result = Product::saveTempOrderItems($data);

        return $result;
    }

    public static function viewOrderItems($orderID)
    {
        $result = Account::viewOrderItems($orderID);
        $count = count($result);
        $encode = [];
        $amount = 0;
        foreach($result as $row) :
            $amount += $row->total_amount;
            $row->img = ($row->img_pic == NULL) ? 'no-image-available.png' : $row->img_pic;
            $encode[] = array_map("utf8_encode", (array)$row);
        endforeach;

        $totalAmt = $amount;

        $response = [
            'items'  => $encode,
            'count'  => $count,
            'amount' => $totalAmt
        ];

        return $response;
    }

    public static function arrItems($catID, $storeCode, $isLogged)
    {
        $stores = ($isLogged == 0) ? NULL : static::customerRegisteredStores(Session::get('email'));
        $result = Product::getProductItems($catID, $storeCode, NULL, $isLogged, $stores);
        $encode = [];

        foreach($result as $row) :
            $encode[] = $row->item_id;
        endforeach;

        return $encode;
    }

    public static function customerRegisteredStores($email)
    {
        $result = Account::getCustomerAddress(NULL, $email, NULL, NULL);
        $codes = [];

        foreach($result as $row) :
            $codes[] = $row->store_code;
        endforeach;

        return implode(',', $codes);
    }
}
