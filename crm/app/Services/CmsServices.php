<?php
namespace App\Services;
use App\Cms;
use Illuminate\Support\Facades\Session;


class CmsServices
{
	public static function productItems()
	{
		$encode = [];
		$result = Cms::getProductItems();

		foreach($result as $row) :
			if($row->is_active == 1) :
				$row->text   = 'active';
				$row->bg     = 'bg-success-800';
				$row->icon   = 'icon-close2';
				$row->action = 'deactivate';
			else :
				$row->text   = 'inactive';
				$row->bg     = 'bg-danger';
				$row->icon   = 'icon-checkmark-circle2';
				$row->action = 'activate';
			endif;

			if($row->is_promo == 0) :
				$row->stat = 'NO';
				$row->bg_color = 'bg-danger-800';
			else :
				$row->stat = 'YES';
				$row->bg_color = 'bg-success-800';
			endif;

			$row->img = (is_null($row->img_pic)) ? 'no-image-available.png' : $row->img_pic;
			$row->regular_price = (is_null($row->price)) ? '0.00' : $row->price;
			$row->promo_price   = (is_null($row->promo_price) || $row->promo_price == '.00') ? '0.00' : $row->promo_price;

			$encode[] = array_map("utf8_encode", (array)$row);
		endforeach;

		return $encode;
	}

    public static function dataToArray($result)
    {
        $encode = [];

		foreach($result as $row) :
			if($row->is_active == 1) :
				$row->text   = 'active';
				$row->bg     = 'bg-success-800';
				$row->icon   = 'icon-close2';
				$row->action = 'deactivate';
			else :
				$row->text   = 'inactive';
				$row->bg     = 'bg-danger';
				$row->icon   = 'icon-checkmark-circle2';
				$row->action = 'activate';
			endif;

			$encode[] = array_map("utf8_encode", (array)$row);
		endforeach;

		return $encode;
    }

    public static function processData($action, $result)
    {
        $i = ($action == 'save') ? 100 : 200;

        if($result == $i) :
        	$status  = 'ok';
        	$message = static::errorMessage($i);
        else :
            $status  = 'fail';
            $message = static::errorMessage($result);
        endif;

        return ['status'=>$status, 'message'=>$message];
    }

    public static function modifyStatus($action, $result)
    {
        if($result == 1) :
    		$status  = 'ok';
    		$message = 'Selected data is successfully '.$action.'d.';
    	else :
    		$status  = 'fail';
    		$message = static::errorMessage($result);
        endif;

        return ['status'=>$status, 'message'=>$message];
	}

	public static function saveExcludeStores($itemID, $stores)
	{
		$storeCodes = explode(',', $stores);
		foreach($storeCodes as $code) :
			Cms::insertExcludeStores($itemID, $code);
		endforeach;
	}

	public static function updateExcludeStores($itemID, $stores)
	{
		Cms::deleteExcludeStores($itemID);
		$storeCodes = explode(',', $stores);
		foreach($storeCodes as $code) :
			Cms::insertExcludeStores($itemID, $code);
		endforeach;
	}

	public static function savePrice()
	{
		$products = CMS::getProductItems(NULL, 1);
		$dataFromAPI = static::itemsFromAPIAllBranch();
		$sku = $dataFromAPI['sku'];
		$arrData = $dataFromAPI['data'];

		foreach($products as $p) :
			if(in_array($p->sku, $sku)) :
				$items = $arrData[$p->sku];

				$data = [
					'itemID'         => $p->item_id,
					'price'          => $items['price'],
					'promo_price'    => $items['promo_price'],
					'effective_date' => date('Y-m-d', strtotime($items['eff_date'])),
					'isPromo'        => $items['is_promo'],
					'createdBy'      => base64_decode(Session::get('Emp_Name')),
				];

				Cms::savePrice($data);
			endif;
        endforeach;

		$response = [
            'message' => 'Item price successfully updated.'
        ];

		return $response;
	}

	public static function saveNoMinimumChargeStores($id, $codes)
	{
		$stores = explode(',', $codes);
		foreach($stores as $code) :
			$data = [
				'id' => $id,
				'storeCode' => $code
			];

			Cms::saveNoMinimumCharge($data);
		endforeach;

		return 100;
	}

	public static function updateNoMinimumChargeStores($id, $codes)
	{
		Cms::deleteNoMinimumCharge($id);
		$stores = explode(',', $codes);
		foreach($stores as $code) :
			$data = [
				'id' => $id,
				'storeCode' => $code
			];

			Cms::saveNoMinimumCharge($data);
		endforeach;

		return 200;
	}

	public static function saveNoDeliveryChargeStores($id, $codes)
	{
		$stores = explode(',', $codes);
		foreach($stores as $code) :
			$data = [
				'id' => $id,
				'storeCode' => $code
			];

			Cms::saveNoDeliveryCharge($data);
		endforeach;

		return 100;
	}

	public static function updateNoDeliveryChargeStores($id, $codes)
	{
		Cms::deleteNoDeliveryCharge($id);
		$stores = explode(',', $codes);
		foreach($stores as $code) :
			$data = [
				'id' => $id,
				'storeCode' => $code
			];

			Cms::saveNoDeliveryCharge($data);
		endforeach;

		return 200;
	}

    public static function errorMessage($i)
	{
		$error = [
			-1   => 'Store code already exist.',
			2    => 'Password successfully reset.',
			100  => 'Data successfully save.',
			200  => 'Data successfully update.',
			-100 => 'Error processing your request. Try again later!',
			-200 => 'Unable to create directory. Try again later!',
			-300 => 'Unable to upload selected files. Try again later!',
		];

		return $error[$i];
	}

	public static function storeBranch()
	{
		$result = Cms::getBranch();

		foreach($result as $row) :
			if($row->is_active == 1) :
				$row->text   = 'active';
				$row->bg     = 'bg-success-800';
				$row->icon   = 'icon-close2';
				$row->action = 'deactivate';
			else :
				$row->text   = 'inactive';
				$row->bg     = 'bg-danger';
				$row->icon   = 'icon-checkmark-circle2';
				$row->action = 'activate';
			endif;

			$encode[] = array_map("utf8_encode", (array)$row);
		endforeach;

		return $encode;
	}

	public static function stores()
	{
		$result = Cms::getStores();

		foreach($result as $row) :
			if($row->is_active == 1) :
				$row->text   = 'active';
				$row->bg     = 'bg-success-800';
				$row->icon   = 'icon-close2';
				$row->action = 'deactivate';
			else :
				$row->text   = 'inactive';
				$row->bg     = 'bg-danger';
				$row->icon   = 'icon-checkmark-circle2';
				$row->action = 'activate';
			endif;

			$encode[] = array_map("utf8_encode", (array)$row);
		endforeach;

		return $encode;
	}

	public static function refreshBranchList($logUser)
	{
		$result = Cms::getBranchMyHub();

		foreach($result as $row) :
			if($row->Location_ID == 2 || $row->Location_ID == 973) :
				$address = $row->LocAddress.', '.$row->Barangay.', '.$row->Municipal.', '.$row->Province;
			else :
				$address = $row->LocAddress;
			endif;

			$data = [
				'branchCode' => $row->LocationCode,
				'branchName' => $row->Location,
				'address'    => $address,
				'logUser'    => $logUser
			];

			Cms::updateBranch($data);
		endforeach;

		$response = [
			'message' => 'Store branch lists successfully refreshed.'
		];

		return $response;
	}

	public static function refreshStoreList($logUser)
	{
		$result = Cms::getStoresMyHub();

		foreach($result as $row) :
			$data = [
				'branchCode' => $row->DCCode,
				'storeCode'  => $row->LocationCode,
				'storeName'  => $row->Location,
				'address'    => $row->LocAddress,
				'province'   => $row->Province,
				'city'       => $row->Municipal,
				'barangay'   => $row->Barangay,
				'latitude'   => $row->Latitude,
				'longitude'  => $row->Longitude,
				'logUser'    => $logUser
			];

			Cms::updateStore($data);

		endforeach;

		$response = [
			'message' => 'Store lists successfully refreshed.'
		];

		return $response;
	}

	public static function itemsFromAPIAllBranch()
	{
		$branch = Cms::getBranch(NULL, 1);
        $encode = [];
        $sku    = [];

        foreach($branch as $b) :
            $data = array(
                'varBranch' => $b->branch_code,
                'varStore'  => NULL,
                'varType'   => 'nas',
                'varKey'    => 'MlMNtBnSxZe2hhjy49WkTw=='
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://10.245.65.232/atpss/api/GrociStore.php');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            $err = curl_error($ch);
            curl_close($ch);

            if($err) :
                $error = true;
            else :
				$error = false;
                $result = json_decode($response);
                $items = [];
                $skus  = [];

                foreach($result->Data as $row) :
                    $items[$row->SKU] = [
                        'category' => $row->CATEGORYNAME,
                        'sku'      => $row->SKU,
                        'item'     => $row->ITEMNAME,
                        'price'    => $row->NORMALPRICE,
                        'promo_price' => $row->PROMOPRICE,
                        'eff_date' => $row->EFFECTIVEDATE,
                        'is_promo' => $row->ISPROMO
                    ];

                    $skus[] = $row->SKU;
                endforeach;
            endif;

            $encode[] = $items;
            $sku = array_merge($sku, $skus);
        endforeach;

		if($error == false) :
			$result = [];
			foreach($encode as $k => $i) :
				$result = array_replace($result, $i);
			endforeach;

			$data = [
				'sku'  => array_unique($sku),
				'data' => $result
			];
		else :
			$data = [
				'sku'  => NULL,
				'data' => NULL
			];
		endif;

		return $data;
	}

}
