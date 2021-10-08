<?php

namespace App\Services;
use App\Orders;
use Illuminate\Support\Facades\Session;


class OrderServices
{
    public static function productItems($storeCode)
    {
        $result = Orders::getProductItems(NULL, $storeCode, NULL);
        $encode = [];

        foreach($result as $row) :
            $price = (empty($row->reg_price)) ? $row->nat_price : number_format($row->reg_price, 2);
            $promo = (empty($row->reg_price)) ? $row->is_promo : $row->reg_is_promo;
            $row->img    = ($row->img_pic == NULL) ? 'no-image-available.png' : $row->img_pic;
            $row->params = $row->item_id.'@@'.$row->item_name.'@@'.$price.'@@'.$promo;
            $row->stocks_on_hand = (is_null($row->stocks_on_hand)) ? 0 : $row->stocks_on_hand;
            $row->pre_order_qty  = (is_null($row->pre_order_qty)) ? 0 : $row->pre_order_qty;
			$encode[] = array_map("utf8_encode", (array)$row);
        endforeach;

		return $encode;
    }

    public static function getOrders($storeCode)
    {
        $items = Orders::getOrders($storeCode);
        $encode = [];

        foreach($items as $item) :
            if($item->order_status == 'FLOAT') :
                $item->bg = 'bg-grey-light';
            elseif($item->order_status == 'FOR DELIVERY') :
                $item->bg = 'bg-blue';
            elseif($item->order_status == 'DELIVERED') :
                $item->bg = 'bg-success';
            elseif($item->order_status == 'CANCEL') :
                $item->bg = 'bg-danger';
            else :
                $item->bg = 'bg-yellow';
            endif;

            $encode[] = array_map("utf8_encode", (array)$item);
        endforeach;

        return $encode;
    }

    public static function basketItems($orderID)
    {
        $result = Orders::getCartItems($orderID);
        $storeCode = base64_decode(Session::get('LocationCode'));
        $encode = [];
        $amount = 0;
        $x = 1;
        $dc = Orders::deliveryCharge();
        $noDelChargeStore = explode(',', $dc[0]->store_code);
        $delCharge = (in_array($storeCode, $noDelChargeStore) ? 0 : $dc[0]->dc_amount);

        foreach($result as $row) :
            $amount += $row->total_amount;
            $row->num = $x;
            $row->img = ($row->img_pic == NULL) ? 'no-image-available.png' : $row->img_pic;
            $encode[] = array_map("utf8_encode", (array)$row);
            $x++;
        endforeach;

        $response = [
            'charges'   => $delCharge,
            'items'     => $encode,
            'amount'    => $amount,
            'amountDue' => $amount + $delCharge,
        ];

        return $response;
    }

    public static function updateOrderAmount($orderID)
    {
        $result = Orders::getCartItems($orderID);
        $amount = 0;
        $count  = count($result);

        foreach($result as $row) :
            $amount += $row->total_amount;
        endforeach;

        $response = [
            'itemCount'   => $count,
            'totalAmount' => ($count == 0) ? '0.00' : round($amount, 2)
        ];

        return $response;
    }

    public static function saveOrderData($data)
    {
        $result = Orders::saveOrder($data);
        $status = [ 'FLOAT', 'RECEIVED' ];

        if($result == 1) :
            foreach($status as $s) :
                Orders::saveOrderStatus($data['orderID'], $s);
            endforeach;
    	    $status  = 'ok';
    	else :
    		$status  = 'fail';
        endif;

        $message = static::errorMessage($result);

        return ['status'=>$status, 'message'=>$message];
    }

    public static function orderStatus($id, $status, $receipt, $payStatus, $storeCode)
    {
        $result = Orders::updateOrderStatus($id, $status, $receipt, $payStatus);
        $items = Orders::checkNewOrders($storeCode);
        $action = ($status == 'DELIVERED') ? 'delivered' : 'updated';
        if($result == 1) :
            Orders::saveOrderStatus($id, $status);
            $response = [
                'status'  => 'success',
                'count'   => count($items),
                'message' => 'Order status has been '.$action.'.'
            ];
        else :
            $response = [
                'status'  => 'fail',
                'count'   => count($items),
                'message' => static::errorMessage(-1)
            ];
        endif;

        return $response;
    }

    public static function getOrderItems($orderID)
    {
        $items = Orders::getCartItems($orderID);
        $arrItems = [];
        $itemIDs  = '';
        foreach($items as $row) :
            $arrItems[$row->item_id] = array_map("utf8_encode", (array)$row);
            $itemIDs .= $row->item_id.',';
        endforeach;

        $result = [
            'items' => $arrItems,
            'ids'   => substr($itemIDs, 0, -1)
        ];

        return $result;
    }

    public static function countNewOrders($storeCode)
	{
		$result = Orders::checkNewOrders($storeCode);

		return count($result);
	}

    public static function updateBasketDetails($data)
    {
        $result = Orders::updateBasketItems($data);

        if($result == 2) :
            $aa = static::basketItems($data['orderID']);
            $item = [
                'orderID' => $data['orderID'],
                'amount'  => $aa['amountDue']
            ];

            $update = Orders::updateOrderAmount($item);

            if($update == 1) :
                $response = 'success';
            else :
                $response = 'error';
            endif;
        else :
            $response = 'error';
        endif;

        return $response;
    }

    public static function showOrderStatus($orderID, $stat)
    {
        $statArr = static::getOrderStatus($orderID);
        $length = count($statArr);
        $count = 1;
        $htm = '<ul class="icons-list">';
        foreach(config('app.status') as $value) :
            if($value == 'SERVICE') :
                $status = ($stat == 'Pick-up') ? 'READY FOR PICK-UP' : 'OUT FOR DELIVERY';
            else :
                $status = $value;
            endif;

            if(in_array($status, $statArr)) :
                if($count == $length) :
                    if($status == 'DELIVERED' || $status == 'PICKED' ) :
                        $bg = 'bg-success';
                    else :
                        $bg = 'bg-yellow';
                    endif;
                else :
                    if(in_array('CANCEL', $statArr)) :
                        $c = $length;
                        if($statArr[$c] == $status) :
                            $bg = 'bg-danger';
                            else :
                                $bg = 'bg-success';
                        endif;
                    else :
                        $bg = 'bg-success';
                    endif;
                endif;
            elseif(in_array('PICKED', $statArr) || in_array('DELIVERED', $statArr)) :
                $bg = 'bg-yellow';
            else :
                $bg = 'bg-grey-300';
            endif;

            $detail = static::abbrStatus($status);

            $hide = '';
            if(($detail['text'] == 'PD' && $stat != 'Pick-up') || ($detail['text'] == 'D' && $stat == 'Pick-up' ) )
            {
                $hide = 'style="display: none"';
            }
            $htm .= '<li '.$hide.'><a data-popup="tooltip" title="'.$detail['tooltip'].'" data-placement="top"><span class="label label-rounded '.$bg.' }}" style="font-size: 11px; padding: 2px 8px;">'.$detail['text'].'</span></a></li>';
            $count++;
        endforeach;
        $htm .= '</ul>';

        return $htm;
    }

    public static function getOrderStatus($orderID)
    {
        $result = Orders::getOrderStatus($orderID);
        $stat = [];

        foreach($result as $row) :
            $stat[] = $row->status;
        endforeach;

        return $stat;
    }

    public static function abbrStatus($status)
    {
        switch($status) {
            case "CANCEL":
                $stat = 'C';
                $tooltip = 'CANCEL';
                break;
            case "OUT FOR DELIVERY":
                $stat = "FD";
                $tooltip = 'FOR DELIVERY';
                break;
            case "READY FOR PICK-UP";
                $stat = "RP";
                $tooltip = 'PICK-UP';
                break;
            case "RECEIVED":
                $stat = "OR";
                $tooltip = 'ORDER RECEIVED';
                break;
            case "ON PROCESS";
                $stat = "P";
                $tooltip = 'PROCESSING';
                break;
            case "DELIVERED";
                $stat = "D";
                $tooltip = 'DELIVERED';
                break;
            case "PICKED";
                $stat = "PD";
                $tooltip = 'PICKED';
             break;
            default:
                $stat = 'OR';
                $tooltip = 'ORDER RECEIVED';
                break;
          }

          return array('text'=>$stat, 'tooltip'=>$tooltip);
    }

    public static function errorMessage($i)
	  {
		    $error = [
            1    => 'Order successfully save!',
            2    => 'Item successfully added to basket.',
            -1   => 'ERROR: Unable to process your request due to server error.',
            -3   => 'ERROR: Unable to save your order due to server error. Try again later.',
            -4   => 'Item successfullly removed!',
            -5   => 'Item quantity successfully updated!',
            -100 => 'ERROR: Unable to add item due server error. Try again later',
		    ];

		    return $error[$i];
	  }
}
