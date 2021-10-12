<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Cms extends Model
{
    public static function getProductItems($itemID = NULL, $isActive = NULL)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_ProductItems_Get ?, ?', [ $itemID, $isActive ]);

        return $result;
    }

    public static function saveProductItems($data)
    {
        $query = DB::connection('dbSqlSrv')->select('EXEC sp_ProductItems_Insert ?, ?, ?, ?, ?', [ $data['category'], $data['sku'], $data['itemName'], $data['img_file'], $data['createdBy'] ]);
        $result = $query[0]->_RETURN;

        return $result;
    }

    public static function updateProductItems($data)
    {
        $query = DB::connection('dbSqlSrv')->select('EXEC sp_ProductItems_Update ?, ?, ?, ?, ?, ?', [ $data['category'], $data['sku'], $data['itemName'], $data['img_file'], $data['modifiedBy'], $data['itemID'] ]);
        $result = $query[0]->_RETURN;

        return $result;
    }

    public static function modifyItemStatus($data)
    {
        $query  = DB::connection('dbSqlSrv')->select('EXEC sp_ProductItemStatus_Update ?, ?, ?', [ $data['value'], $data['modifiedBy'], $data['itemID'] ]);
        $result = $query[0]->_RETURN;

        return $result;
    }

    public static function getProductCategory($catID = NULL, $isActive = NULL, $storeCode = NULL)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_Categories_Get ?, ?, ?', [ $catID, $isActive, $storeCode ]);

        return $result;
    }

    public static function saveCategory($data)
    {
        $query = DB::connection('dbSqlSrv')->select('EXEC sp_Category_Insert ?, ?, ?', [ $data['categoryName'], $data['img_file'], $data['createdBy'] ]);
        $result = $query[0]->_RETURN;

        return $result;
    }

    public static function updateCategory($data)
    {
        $query = DB::connection('dbSqlSrv')->select('EXEC sp_Category_Update ?, ?, ?, ?', [ $data['categoryName'], $data['img_file'], $data['modifiedBy'], $data['catID'] ]);
        $result = $query[0]->_RETURN;

        return $result;
    }

    public static function modifyCategoryStatus($data)
    {
        $query  = DB::connection('dbSqlSrv')->select('EXEC sp_CategoryStatus_Update ?, ?, ?', [ $data['value'], $data['modifiedBy'], $data['catID'] ]);
        $result = $query[0]->_RETURN;

        return $result;
    }

    public static function getSliders($sliderID = NULL, $isActive = NULL)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_Sliders_Get ?, ?', [ $sliderID, $isActive ]);

        return $result;
    }

    public static function saveSlider($data)
    {
        $query = DB::connection('dbSqlSrv')->select('EXEC sp_Slider_Insert ?, ?, ?', [ $data['sliderName'], $data['img_file'], $data['createdBy'] ]);
        $result = $query[0]->_RETURN;

        return $result;
    }

    public static function updateSlider($data)
    {
        $query = DB::connection('dbSqlSrv')->select('EXEC sp_Slider_Update ?, ?, ?, ?', [ $data['sliderName'], $data['img_file'], $data['modifiedBy'], $data['sliderID'] ]);
        $result = $query[0]->_RETURN;

        return $result;
    }

    public static function modifySliderStatus($data)
    {
        $query  = DB::connection('dbSqlSrv')->select('EXEC sp_SliderStatus_Update ?, ?, ?', [ $data['value'], $data['modifiedBy'], $data['sliderID'] ]);
        $result = $query[0]->_RETURN;

        return $result;
    }


    public static function getAds($adID = NULL, $isActive = NULL)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_Ads_Get ?, ?', [ $adID, $isActive ]);

        return $result;
    }

    public static function saveBannerAds($data)
    {
        $query = DB::connection('dbSqlSrv')->select('EXEC sp_Ads_Insert ?, ?, ?, ?', [ $data['bannerName'], $data['pageLocation'], $data['img_file'], $data['createdBy'] ]);
        $result = $query[0]->_RETURN;

        return $result;
    }

    public static function updateBannerAds($data)
    {
        $query = DB::connection('dbSqlSrv')->select('EXEC sp_Ads_Update ?, ?, ?, ?, ?', [ $data['bannerName'], $data['pageLocation'], $data['img_file'], $data['modifiedBy'], $data['adID'] ]);
        $result = $query[0]->_RETURN;

        return $result;
    }

    public static function modifyAdStatus($data)
    {
        $query  = DB::connection('dbSqlSrv')->select('EXEC sp_AdStatus_Update ?, ?, ?', [ $data['value'], $data['modifiedBy'], $data['adID'] ]);
        $result = $query[0]->_RETURN;

        return $result;
    }

    public static function getBranch($branchCode = NULL, $isActive = NULL)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_StoreBranch_Get ?, ?', [ $branchCode, $isActive ]);

        return $result;
    }

    public static function getBranchMyHub()
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC [10.143.192.90].ATPI_HR.dbo.sp_Location_Get ?, ?', [ 0, 2 ]);

        return $result;
    }

    public static function updateBranch($data)
    {
        DB::connection('dbSqlSrv')->select('EXEC sp_StoreBranch_Update ?, ?, ?, ?', [ $data['branchCode'], $data['branchName'], $data['address'], $data['logUser'] ]);
    }

    public static function modifyBranchStatus($data)
    {
        $query  = DB::connection('dbSqlSrv')->select('EXEC sp_StoreBranchStatus_Update ?, ?, ?', [ $data['value'], $data['modifiedBy'], $data['branchID'] ]);
        $result = $query[0]->_RETURN;

        return $result;
    }

    public static function getStores($storeID = NULL, $isActive = NULL)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_Stores_Get ?, ?', [ $storeID, $isActive ]);

        return $result;
    }

    public static function getStoresMyHub($dcCode = NULL, $province = NULL)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC [10.143.192.90].ATPI_HR.dbo.sp_vwLocation_Get ?, ?', [ $dcCode, $province ]);

        return $result;
    }

    public static function updateStore($data)
    {
        DB::connection('dbSqlSrv')->select('EXEC sp_Stores_Update ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', [ $data['branchCode'], $data['storeCode'], $data['storeName'], $data['address'], $data['province'], $data['city'], $data['barangay'], $data['latitude'], $data['longitude'], $data['logUser'] ]);
    }

    public static function modifyStoreStatus($data)
    {
        $query  = DB::connection('dbSqlSrv')->select('EXEC sp_StoreStatus_Update ?, ?, ?', [ $data['value'], $data['modifiedBy'], $data['storeID'] ]);
        $result = $query[0]->_RETURN;

        return $result;
    }

    public static function getItemPrice($itemID)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_Price_Get ?', [ $itemID ]);

        return $result;
    }

    public static function savePrice($data)
    {
        $query = DB::connection('dbSqlSrv')->select('EXEC sp_Price_Insert ?, ?, ?, ?, ?, ?', [ $data['itemID'], $data['price'], $data['promo_price'], $data['effective_date'], $data['createdBy'], $data['isPromo'] ]);
        $result = $query[0]->_RETURN;

        return $result;
    }

    public static function getProvince()
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_Province_Get');

        return $result;
    }


    public static function getMinimumCharge($id = NULL)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_MinimumCharge_Get ?', [ $id ]);

        return $result;
    }

    public static function saveMinimumCharge($data)
    {
        $query = DB::connection('dbSqlSrv')->select('EXEC sp_MinimumCharge_Insert ?, ?, ?', [ $data['amount'], $data['effDate'], $data['createdBy'] ]);
        $result = $query[0]->_RETURN;

        return $result;
    }

    public static function updateMinimumCharge($data)
    {
        $query = DB::connection('dbSqlSrv')->select('EXEC sp_MinimumCharge_Update ?, ?, ?, ?', [ $data['amount'], $data['effDate'], $data['modifiedBy'], $data['id'] ]);
        $result = $query[0]->_RETURN;

        return $result;
    }

    public static function saveNoMinimumCharge($data)
    {
        $query = DB::connection('dbSqlSrv')->select('EXEC sp_NoMinimumCharge_Insert ?, ?', [ $data['id'], $data['storeCode'] ]);
        $result = $query[0]->_RETURN;

        return $result;
    }

    public static function deleteNoMinimumCharge($id)
    {
        $query = DB::connection('dbSqlSrv')->select('EXEC sp_NoMinimumCharge_Delete ?', [ $id ]);
        $result = $query[0]->_RETURN;

        return $result;
    }

    public static function getDeliveryCharge($id = NULL)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_DeliveryCharge_Get ?', [ $id ]);

        return $result;
    }

    public static function saveDeliveryCharge($data)
    {
        $query = DB::connection('dbSqlSrv')->select('EXEC sp_DeliveryCharge_Insert ?, ?, ?', [ $data['amount'], $data['effDate'], $data['createdBy'] ]);
        $result = $query[0]->_RETURN;

        return $result;
    }

    public static function updateDeliveryCharge($data)
    {
        $query = DB::connection('dbSqlSrv')->select('EXEC sp_DeliveryCharge_Update ?, ?, ?, ?', [ $data['amount'], $data['effDate'], $data['modifiedBy'], $data['id'] ]);
        $result = $query[0]->_RETURN;

        return $result;
    }

    public static function saveNoDeliveryCharge($data)
    {
        $query = DB::connection('dbSqlSrv')->select('EXEC sp_NoDeliveryCharge_Insert ?, ?', [ $data['id'], $data['storeCode'] ]);
        $result = $query[0]->_RETURN;

        return $result;
    }

    public static function deleteNoDeliveryCharge($id)
    {
        $query = DB::connection('dbSqlSrv')->select('EXEC sp_NoDeliveryCharge_Delete ?', [ $id ]);
        $result = $query[0]->_RETURN;

        return $result;
    }

    public static function insertExcludeStores($itemID, $code)
    {
        $query = DB::connection('dbSqlSrv')->select('EXEC sp_ExcludeStores_Insert ?, ?', [ $itemID, $code ]);
        $result = $query[0]->_RETURN;

        return $result;
    }

    public static function deleteExcludeStores($itemID)
    {
        $query = DB::connection('dbSqlSrv')->select('EXEC sp_ExcludeStores_Delete ?', [ $itemID ]);
        $result = $query[0]->_RETURN;

        return $result;
    }

    public static function getStorePerDC($branchCode)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_StorePerDC_Get ?', [ $branchCode ]);

        return $result;
    }

    public static function InsertItemsFromApi($data)
    {
        DB::connection('dbSqlSrv')->select('EXEC sp_ItemsFromAPI_Insert ?, ?, ?, ?, ?, ?, ?, ?', [ $data['category'], $data['sku'], $data['item'], $data['price'], $data['promo_price'], $data['eff_date'], $data['is_promo'], $data['bcode'] ]);
    }

    public static function storeDetails($code, $isActive = NULL)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_Stores_Get ?, ?', [ $code, $isActive ]);

        return $result;
    }
}
