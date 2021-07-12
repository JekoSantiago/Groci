<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Content extends Model
{
    public static function getSliders($sliderID = NULL, $isActive = NULL)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_Sliders_Get ?, ?', [ $sliderID, $isActive ]);

        return $result;
    }

    public static function getCategories($catID = NULL, $isActive = NULL, $storeCode = NULL)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_Categories_Get ?, ?, ?', [ $catID, $isActive, $storeCode]);

        return $result;
    }

    public static function getDeliveryCharge()
    {
        $result  = DB::connection('dbSqlSrv')->select('EXEC sp_CurrentDeliveryCharge_Get');

        return $result;
    }

    public static function getMinimumCharge()
    {
        $result  = DB::connection('dbSqlSrv')->select('EXEC sp_CurrentMinimumCharge_Get');

        return $result;
    }

    public static function getProvince($provinceName = NULL)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_Province_Get ?', [ $provinceName ]);

        return $result;
    }

    public static function getStores($scode = NULL, $isActive = NULL)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_Stores_Get ?, ?', [ $scode, $isActive ]);

        return $result;
    }

    public static function getSearchStores($keyword)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_SearchStore_Get ?', [ $keyword ]);

        return $result;
    }

    public static function getStorePerProvince($province)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_StorePerProvince_Get ?', [ $province ]);
        //$result = DB::connection('dbSqlSrv')->select('EXEC [10.143.192.90].ATPI_HR.dbo.sp_vwLocation_Get ?, ?', [ NULL, $province ]);

        return $result;
    }

    public static function getCityMunicipalOption($provinceID)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_CityMunicipality_Get ?', [ $provinceID ]);

        return $result;
    }

    public static function getCityMunicipalityDetails($cityName)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_CityMunicipalityDetails_Get ?', [ $cityName ]);

        return $result;
    }
}
