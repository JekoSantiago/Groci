<?php
namespace App\Services;
use App\Customer;

class CustomerServices
{
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
				$row->bg     = 'bg-danger-800';
				$row->icon   = 'icon-checkmark-circle2';
				$row->action = 'activate';
			endif;

			if(is_null($row->is_confirm)) :
				$row->badge_bg  = 'bg-warning';
				$row->badge_txt = 'FOR CONFIRMATION';
			else :
				if($row->is_confirm == 1) : 
					$row->badge_bg  = 'bg-success-800';
					$row->badge_txt = 'CONFIRMED';
				elseif($row->is_confirm == 0) :
					$row->badge_bg  = 'bg-danger-800';
					$row->badge_txt = 'CANCELLED';
				endif;
			endif;

			$encode[] = array_map("utf8_encode", (array)$row);
		endforeach;

		return $encode;
	}

	public static function allCustomers()
	{
		$result = Customer::getAllCustomers();
		$encode = [];
		foreach($result as $row) :
			if($row->is_active == 1) :
				$row->text   = 'active';
				$row->bg     = 'bg-success-800';
				$row->icon   = 'icon-close2';
				$row->action = 'deactivate';
			else :
				$row->text   = 'inactive';
				$row->bg     = 'bg-danger-800';
				$row->icon   = 'icon-checkmark-circle2';
				$row->action = 'activate';
			endif;

			$encode[] = array_map("utf8_encode", (array)$row);
		endforeach;

		return $encode;
	}

	public static function customerAddress($type, $email, $storeCode)
	{
		$result = Customer::getCustomerAddress($type, $email, NULL, $storeCode);
		$encode = [];

		foreach($result as $row) :
			$row->landmark = ($row->landmarks == NULL) ? NULL : ' | Landmark: '.$row->landmarks;
			$encode[] = array_map("utf8_encode", (array)$row);
		endforeach;

		return $encode;
	}

	public static function customerAddressDetails($customerID, $address)
	{
		$result = Customer::getCustomerAddressDetails($customerID, $address);
		$encode = [];

		foreach($result as $row) :
			$row->landmark = ($row->landmarks == NULL) ? NULL : ' | Landmark: '.$row->landmarks;
			$encode[] = array_map("utf8_encode", (array)$row);
		endforeach;

		return $encode;
	}


	public static function saveCustomerData($data)
	{
		$result = Customer::saveCustomer($data);

		return $result;
	}

	public static function saveCustoreAddressData($data)
	{
		$result = Customer::saveCustomerAddress($data);

		return $result;
	}

	public static function countNewCustomer($storeCode)
	{
		$result = Customer::checkNewCustomer($storeCode);

		return count($result);
	}

	public static function message($index)
	{
		$message = [
			1 => 'Customer has been confirmed.',
			2 => 'Customer has been cancelled.',
			3 => 'SERVER ERROR: Unable to sent confirmation email. Try again later.',
			-200 => 'ERROR: Unable to process your request due to server error.' 
		];

		return $message[$index];
	}

	public static function customerAssignedStore($email)
	{
		$result = Customer::getCustomerAddress(NULL, $email, NULL, NULL);
		$store = '';
		foreach($result as $row) :
			$store .= $row->store_name.',';
		endforeach;

		return substr($store, 0, -1);
	} 
}