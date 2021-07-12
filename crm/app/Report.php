<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Report extends Model
{
    public static function getOverAllReport($branchCode, $dateFrom, $dateTo)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_RPT_OverAll_Get ?, ?, ?', [ $dateFrom, $dateTo, $branchCode ]);

        return $result;
    }

    public static function getReportPerDCPerDay($bcode, $date)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_RPT_DCPerDay_Get ?, ?', [ $bcode, $date ]);

        return $result;
    }

    public static function getReportPerDCPerStore($scode, $dateFrom, $dateTo)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_RPT_DCPerStore_Get ?, ?, ?', [ $scode, $dateFrom, $dateTo ]);

        return $result;
    }

    public static function getReportPerStorePerDay($scode, $transDate)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_RPT_StorePerDay_Get ?, ?', [ $scode, $transDate ]);

        return $result;
    }

    public static function storePerDCWithTransaction($bcode, $dateFrom, $dateTo)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_StorePerDC_WithTransaction_Get ?, ?, ?', [ $bcode, $dateFrom, $dateTo ]);

        return $result;
    } 

    public static function getTop15Products($dateFrom, $dateTo)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_RPT_Top15Products_Get ?, ?', [ $dateFrom, $dateTo ]);

        return $result;
    }

    public static function getTop15ProductsDailyData($itemID, $date)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_RPT_Top15Products_Daily_Data_Get ?, ?', [ $itemID, $date ]);

        return $result;
    }

}
