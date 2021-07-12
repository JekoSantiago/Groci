<?php

namespace App\Services;
use App\Models\Content;
use App\Models\Account;

class ContentServices
{
    public static function homeSliders()
    {
        $sliders = Content::getSliders(NULL, 1);
        $encode = [];

        foreach($sliders as $slide) :
            $encode[] = array_map("utf8_encode", (array)$slide);
        endforeach;

        return $encode;
    }

    public static function getCategories($storeCode)
    {
        $result = Content::getCategories(NULL, 1, $storeCode);
        $encode = [];

        foreach($result as $row) :
            $encode[] = array_map("utf8_encode", (array)$row);
        endforeach;

        return $encode;
    }
    
    public static function getCategoryName($catID)
    {
        $result = Content::getCategories($catID);

        return $result[0]->category_name;
    }

    public static function deliveryCharge()
    {
        $result = Content::getDeliveryCharge();

        return $result[0]->dc_amount;
    }

    public static function minimumCharge()
    {
        $result = Content::getMinimumCharge();

        return $result[0]->amount;
    }

    public static function storeOption($province)
    {
        $option = '<option value="">* Select a store</option>';

        if(!is_null($province)) :
            $result = Content::getStorePerProvince($province);
            foreach($result as $row) :
                $option .= '<option value="'.$row->store_code.'">'.$row->store_name.'</option>';
            endforeach;
        endif;

        return $option;
    } 

    public static function cityOption($provinceID)
    {
        $option = '<option value="">* Select city</option>';

        if(!is_null($provinceID)) :
            $result = Content::getCityMunicipalOption($provinceID);
            foreach($result as $row) :
                $option .= '<option value="'.$row->municipal_name.'">'.$row->municipal_name.'</option>';
            endforeach;
        endif;

        return $option;
    }

    public static function storePerCityOption($city)
    {
        $option = '<option value="">* Select a store</option>';

        if(!is_null($city)) :
            $result = Content::getSearchStores($city);
            foreach($result as $row) :
                $option .= '<option value="'.$row->store_code.'">'.$row->store_name.'</option>';
            endforeach;
        endif;

        return $option;
    }

    public static function hourOption()
    {
        $min = date('i');
        $hour = ($min > 45) ? date('h', strtotime('+2 hour')) :date('h', strtotime('+1 hour'));
        $encode = [];
        for ($h=1; $h<=12; $h++) :
            if($h < $hour) :
                $attr = 'disabled=disabled';
            elseif($h == $hour) :
                $attr = 'selected=selected';
            else :
                $attr = '';
            endif;

            $encode[] = [
                'hour' => $h,
                'attr' => $attr
            ];
        endfor;

        return $encode;
    }

    public static function storeDetails($storeCode)
    {
        $detail = Content::getStores($storeCode);

        return $detail;
    }

    public static function storeName($storeCode)
    {
        $detail = Content::getStores($storeCode);

        return $detail[0]->store_name;
    }

    public static function catArr($storeCode)
    {
        $result = Content::getCategories(NULL, 1, $storeCode);
        $encode = [];

        foreach($result as $row) :
            $encode[] = $row->category_id;
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

    public static function calculateDistance($latOne, $lonOne, $latTwo, $lonTwo)
    {
        $theta = $lonOne - $lonTwo;
        $dist  = sin(deg2rad($latOne)) * sin(deg2rad($latTwo)) +  cos(deg2rad($latOne)) * cos(deg2rad($latTwo)) * cos(deg2rad($theta));
        $dist  = acos($dist);
        $dist  = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;

        return ($miles * 1609.34);
    }

    public static function nearestStore($userLat, $userLong)
    {
        $stores = Content::getStores(NULL, 1);
        $detail = [];
	foreach($stores as $k => $row) :

            if(!empty($row->latitude) && !empty($row->longitude)) :
				$distance = static::calculateDistance($userLat, $userLong, $row->latitude, $row->longitude);
				$detail[$k]['storeName']     = $row->store_name;
                $detail[$k]['storeCode']     = $row->store_code;
	    		$detail[$k]['km']            = $distance;
                $detail[$k]['userLatitude']  = $userLat;
                $detail[$k]['userLongitude'] = $userLong;
			endif; 
		endforeach;

        $keys = array_column($detail, 'km');
        array_multisort($keys, SORT_ASC, $detail);
        $topOne = array_slice($detail, 0, 1);
		
        return $topOne; 
    }

    public static function topNearestStore($userLat, $userLong)
    {
        $stores = Content::getStores(NULL, 1);
        $detail = [];
		foreach($stores as $k => $row) :
            if(!empty($row->latitude) && !empty($row->longitude)) :
				$distance = static::calculateDistance($userLat, $userLong, $row->latitude, $row->longitude);

                if($distance <= 1000) :
                    $detail[$k]['storeName'] = $row->store_name;
                    $detail[$k]['storeCode'] = base64_encode($row->store_code);
                    $detail[$k]['km']        = $distance;
                    $detail[$k]['address']   = $row->address;
                    $detail[$k]['province']  = $row->province;
                    $detail[$k]['city']      = $row->city;
                    $detail[$k]['barangay']  = $row->barangay;
                endif;
			endif; 
		endforeach;

        $keys = array_column($detail, 'km');
        array_multisort($keys, SORT_ASC, $detail);
		
        return $detail; 
    }

    public static function searchNearestStore($keyword)
    {
        $stores = Content::getSearchStores($keyword);
        $detail = [];
		foreach($stores as $k => $row) :
            $detail[$k]['storeName'] = $row->store_name;
            $detail[$k]['storeCode'] = base64_encode($row->store_code);
            $detail[$k]['address']   = $row->address;
            $detail[$k]['province']  = $row->province;
            $detail[$k]['city']      = $row->city;
            $detail[$k]['barangay']  = $row->barangay;
        endforeach;

        return $detail; 
    }
}