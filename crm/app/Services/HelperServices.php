<?php
namespace App\Services;

class HelperServices
{
	/**
	 * JSON datatable
	 */
	public static function viewJSONTable_Data($count, $encode, $totalAmt, $charge)
    {
    	$draw         = request()->input('draw');
        $recordsTotal = $count; 
        $data = $encode;
    
        return '{"draw":"'.$draw.'","recordsFiltered":'.$recordsTotal.',"recordsTotal":'.$recordsTotal.', "charges":'.$charge.', "totalAmount":'.$totalAmt.', "data":'.json_encode($data).'}';  
	}
	
	public static function greetings()
	{
		if(date("H") < 12) :
	     	return "GOOD MORNING!";
		elseif(date("H") >= 12 && date("H") < 18) :
			return "GOOD AFTERNOON!";
	   elseif(date("H") >= 18) :
	     	return "GOOD EVENING!";
	     endif;
	}

	public static function dateRange($first, $last, $step = '+1 day', $format = 'm/d/Y' ) 
	{
		$dates   = array();
		$current = strtotime($first );
		$last    = strtotime($last );
	
		while( $current <= $last ) :
			$dates[] = date( $format, $current );
			$current = strtotime( $step, $current );
		endwhile;
	
		return $dates;
	}

	public static function countDays($first, $last)
	{
		$dates = static::dateRange($first, $last);

		return count($dates);
	}

	public static function errorMessage($i)
	{
		$error = [
			-1   => 'Data already exist.',
			-2   => 'Email address already exist. Try another one.',
			-3   => 'ERROR: Unable to save your order due to server error. Try again later.',
			-4   => 'ERROR: Unable to process your request. Please try again later.',
			1    => 'Order successfully save.',
			100  => 'Data successfully save.',
			200  => 'Data successfully update.',

			-100 => 'Error processing your request. Try again later!',
			-200 => 'Unable to create directory. Try again later!',
			-300 => 'Unable to upload selected files. Try again later!',
		];

		return $error[$i];
	}

	public static function timeFormat($time)
	{
		$newFormat = date('Y-m-d g:i A', strtotime($time));

		return $newFormat;
	}
}