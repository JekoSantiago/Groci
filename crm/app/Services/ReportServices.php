<?php
namespace App\Services;
use App\Report;
use App\Cms;

class ReportServices 
{
    public static function overAllReport($branchCode, $dateFrom, $dateTo)
    {
        $result = Report::getOverAllReport($branchCode, $dateFrom, $dateTo);
        $wd  = static::countDays($dateFrom, $dateTo);
        $tc  = (empty($result)) ? 0 : $result[0]->TC;
        $amt = (empty($result)) ? 0 : $result[0]->AMT;
        $cs  = (empty($result)) ? 0 : $result[0]->CS;
        $qty = (empty($result)) ? 0 : $result[0]->QTY;
        $spd = ($cs == 0) ? 0 : ($amt / ($cs * $wd));
        $std = ($cs == 0) ? 0 : ($tc / ($cs * $wd));
        $apc = ($std == 0) ? 0 : ($spd / $std);

	    $response = [
            'CS'  => $cs,
            'QTY' => $qty,
            'TC'  => $tc,
            'AMT' => $amt,
            'SPD' => number_format($spd, 2, '.', ''),
            'STD' => ($std == 0) ? 0 : ROUND($std, 2, PHP_ROUND_HALF_UP),
            'APC' => ROUND($apc, 3, PHP_ROUND_HALF_UP)
        ];

        return $response;
    }

    public static function reportPerDCPerDay($branchCode, $date)
    {
        $result = Report::getReportPerDCPerDay($branchCode, $date);
        $wd  = 1;
        $tc  = (empty($result)) ? 0 : $result[0]->TC;
        $amt = (empty($result)) ? 0 : $result[0]->AMT;
        $cs  = (empty($result)) ? 0 : $result[0]->CS;
        $qty = (empty($result)) ? 0 : $result[0]->QTY;
        $spd = ($cs == 0) ? 0 : ($amt / ($cs * $wd));
        $std = ($cs == 0) ? 0 : ($tc / ($cs * $wd));
        $apc = ($std == 0) ? 0 : ($spd / $std);

	    $response = [
            'CS'  => $cs,
            'QTY' => $qty,
            'TC'  => $tc,
            'AMT' => $amt,
            'SPD' => number_format($spd, 2, '.', ''),
            'STD' => ($std == 0) ? 0 : ROUND($std, 2, PHP_ROUND_HALF_UP),
            'APC' => ROUND($apc, 3, PHP_ROUND_HALF_UP)
        ];

        return $response;
    }

    public static function reportPerDCPerStorePerRange($storeCode, $dateFrom, $dateTo)
    {
        $result = Report::getReportPerDCPerStore($storeCode, $dateFrom, $dateTo);
        $wd  = static::countDays($dateFrom, $dateTo);
        $tc  = (empty($result)) ? 0 : $result[0]->TC;
        $amt = (empty($result)) ? 0 : $result[0]->AMT;
        $cs  = (empty($result)) ? 0 : $result[0]->CS;
        $qty = (empty($result)) ? 0 : $result[0]->QTY;
        $spd = ($cs == 0) ? 0 : ($amt / ($cs * $wd));
        $std = ($cs == 0) ? 0 : ($tc / ($cs * $wd));
        $apc = ($std == 0) ? 0 : ($spd / $std);

	    $response = [
            'CS'  => $cs,
            'QTY' => $qty,
            'TC'  => $tc,
            'AMT' => $amt,
            'SPD' => number_format($spd, 2, '.', ''),
            'STD' => ($std == 0) ? 0 : ROUND($std, 2, PHP_ROUND_HALF_UP),
            'APC' => ROUND($apc, 3, PHP_ROUND_HALF_UP)
        ];

        return $response;
    }

    public static function reportPerStorePerDay($storeCode, $transDate)
    {
        $result = Report::getReportPerStorePerDay($storeCode, $transDate);
        $wd  = 1;
        $tc  = (empty($result)) ? 0 : $result[0]->TC;
        $amt = (empty($result)) ? 0 : (is_null($result[0]->AMT)) ? 0 : $result[0]->AMT;
        $cs  = (empty($result)) ? 0 : $result[0]->CS;
        $qty = (empty($result)) ? 0 : (is_null($result[0]->QTY)) ? 0 : $result[0]->QTY;
        $spd = ($cs == 0) ? 0 : ($amt / ($cs * $wd));
        $std = ($cs == 0) ? 0 : ($tc / ($cs * $wd));
        $apc = ($std == 0) ? 0 : ($spd / $std);

	    $response = [
            'CS'  => $cs,
            'QTY' => $qty,
            'TC'  => $tc,
            'AMT' => $amt,
            'SPD' => number_format($spd, 2, '.', ''),
            'STD' => ($std == 0) ? 0 : ROUND($std, 2, PHP_ROUND_HALF_UP),
            'APC' => ROUND($apc, 3, PHP_ROUND_HALF_UP)
        ];

        return $response;
    }

    public static function top15Products($first, $last)
    {
        $encode = [];
        $startDate = date('Y-m-d', strtotime($first));
        $endDate   = date('Y-m-d', strtotime($last));
        $result = Report::getTop15Products($startDate, $endDate);
        $rank = 1;
        foreach($result as $row) :
            $row->rank = $rank;
            $encode[] = array_map("utf8_encode", (array)$row);
            $rank++;
        endforeach;

        return $encode;
    }

    public static function top15ProductsDailyData($itemID, $date)
    {
        $filterDate = date('Y-m-d', strtotime($date));
        $results = Report::getTop15ProductsDailyData($itemID, $filterDate);
        
        $data = [
            'AMT' => (empty($results)) ? 0 : $results[0]->total_price,
            'QTY' => (empty($results)) ? 0 : $results[0]->total_qty
        ];

        return $data;
    }

    public static function extractOverAllReport($first, $last, $dc)
    {
        $encode = [];
        $totalQty = 0;
        $cntStore = 0;
        $netSales = 0;
        $transCount = 0;
        $spd = 0;
        $std = 0;
        $cntDays = static::countDays($first, $last);
        $bcode   = ($dc == 'all') ? NULL : $dc;
        $active  = ($dc == 'all') ? 1 : NULL;
        $branch = Cms::getBranch($bcode, $active);

        foreach($branch as $row) :
            $data = static::overAllReport($row->branch_code, date('Y-m-d', strtotime($first)), date('Y-m-d', strtotime($last)));
            $row->date_range = date('M j', strtotime($first)) .'-'. date('M j, Y', strtotime($last));
            $row->count_days = $cntDays;
            $row->items = $data;
            $cntStore += $data['CS'];
            $netSales += $data['AMT'];
            $transCount += $data['TC'];
            $spd += $data['SPD'];
            $std += $data['STD'];
            $totalQty += $data['QTY'];

            $encode[] = $row;
        endforeach;

        $total = [
            'totalCS'  => $cntStore,
            'totalQTY' => $totalQty,
            'totalAMT' => $netSales,
            'totalTC'  => $transCount,
            'totalSPD' => $spd,
            'totalSTD' => $std,
            'totalAPC' => ($spd / $std)
        ];

        $response = [
            'data'  => $encode,
            'total' => $total
        ];

        return $response;
    }

    public static function extractDCDailyReport($first, $last, $bcode)
    {
        $encode = [];
        $dateRange = static::dateRange($first, $last);
        $dcName = static::dcName($bcode);

        foreach($dateRange as $date) :
            $items = static::reportPerDCPerDay($bcode, date('Y-m-d', strtotime($date)));

            $data = [
                'dc_name'    => $dcName,
                'trans_date' => date('M j, Y', strtotime($date)),
                'work_days'  => 1,
                'items'      => $items
            ];
            
            $encode[] = $data;
        endforeach;

        return $encode;
    }

    public static function extractPerDCReport($first, $last, $bcode)
    {
        $encode = [];
        $totalQty = 0;
        $cntStore = 0;
        $netSales = 0;
        $transCount = 0;
        $spd = 0;
        $std = 0;
        $dateRange = static::dateRange($first, $last);
        $stores = Report::storePerDCWithTransaction($bcode, date('Y-m-d', strtotime($first)), date('Y-m-d', strtotime($last)));

        foreach($stores as $row) :
            $items = static::reportPerDCPerStorePerRange($row->store_code, date('Y-m-d', strtotime($first)), date('Y-m-d', strtotime($last)));
            $storeName = $row->store_code .'-'. $row->store_name;
            $cntStore += $items['CS'];
            $netSales += $items['AMT'];
            $transCount += $items['TC'];
            $spd += $items['SPD'];
            $std += $items['STD'];
            $totalQty += $items['QTY'];
            $data = [
                'store_name' => $storeName,
                'trans_date' => date('M j', strtotime($first)) .'-'. date('M j, Y', strtotime($last)),
                'work_days'  => count($dateRange),
                'items'      => $items
            ];

            $encode[] = $data;
        endforeach;

        $total = [
            'totalCS'  => $cntStore,
            'totalQTY' => $totalQty,
            'totalAMT' => $netSales,
            'totalTC'  => $transCount,
            'totalSPD' => $spd,
            'totalSTD' => $std,
            'totalAPC' => ($spd / $std)
        ];

        $response = [
            'data'  => $encode,
            'total' => $total
        ];

        return $response;
    }

    public static function extractDCPerStoreReport($first, $last, $scode)
    {
        $encode = [];
        $totalQty = 0;
        $cntStore = 0;
        $netSales = 0;
        $transCount = 0;
        $spd = 0;
        $std = 0;
        $dateRange = static::dateRange($first, $last);
        $items = static::reportPerDCPerStorePerRange($scode, date('Y-m-d', strtotime($first)), date('Y-m-d', strtotime($last)));
        $storeName = $scode .'-'. static::storeName($scode);
        $data = [
            'store_name' => $storeName,
            'trans_date' => date('M j', strtotime($first)) .'-'. date('M j, Y', strtotime($last)),
            'work_days'  => count($dateRange),
            'items'      => $items
        ];

        $encode[] = $data;

        $total = [
            'totalCS'  => $items['CS'],
            'totalQTY' => $items['QTY'],
            'totalAMT' => $items['AMT'],
            'totalTC'  => $items['TC'],
            'totalSPD' => $items['SPD'],
            'totalSTD' => $items['STD'],
            'totalAPC' => ($items['SPD'] / $items['STD'])
        ];

        $response = [
            'data'  => $encode,
            'total' => $total
        ];

        return $response;
    }

    public static function exportStoreDailyReport($first, $last, $scode)
    {
        $encode = [];
        $dateRange = static::dateRange($first, $last);
        $storeName = static::storeName($scode);

        foreach($dateRange as $date) :
            $items = static::reportPerStorePerDay($scode, date('Y-m-d', strtotime($date)));

            $data = [
                'store_name' => $storeName,
                'trans_date' => date('M j, Y', strtotime($date)),
                'work_days'  => 1,
                'items'      => $items
            ];
            
            $encode[] = $data;

        endforeach;

        return $encode;
    }

    public static function exportTopProducts($first, $last)
    {
        $encode = [];
        $dateRange = static::dateRange($first, $last);
        $topProduct = static::top15Products($first, $last);

        foreach($topProduct as $row) :
            $arrItems = [];
            foreach($dateRange as $date) :
                $d = date('Ymd', strtotime($date));
                $items = static::top15ProductsDailyData($row['item_id'], $date);
                $arrItems[] = [ $d => $items ];
            endforeach;

            $row['items'] = json_encode($arrItems);
            $encode[] = $row;
        endforeach;

        return $encode;
    }

    public static function storeName($scode)
    {
        $detail = Cms::getStores($scode);
        $storeName = $detail[0]->store_code .'-'.$detail[0]->store_name;

        return $storeName;
    }

    public static function dcName($bcode)
    {
        $detail = Cms::getBranch($bcode);

        return $detail[0]->branch_name;
    }

    public static function base64url_encode($data) 
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
      
    public static function base64url_decode($data)
    {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }

    public static function dateRange($first, $last, $step = '+1 day', $format = 'm/d/Y' ) 
	{
		$dates   = array();
		$current = strtotime($first);
		$last    = strtotime($last);
	
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
}