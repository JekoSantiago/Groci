<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Product extends Model
{
    public static function topSaverItems($storeCode = NULL, $isLogged, $stores = NULL)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_TopSaversItem_Get ?, ?, ?', [ $storeCode, $isLogged, $stores ]);

        return $result;
    }

    public static function showItemsInBasket($orderID, $storeCode)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_OrderTempItems_Get ?, ?', [ $orderID, $storeCode ]);
        
        return $result;
    }

    /**
     * Get list of items per category
     */
    public static function getProductItems($catID = NULL, $storeCode = NULL, $searchKey = NULL, $isLogged, $stores = NULL)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_ItemsPerCategory_Get ?, ?, ?, ?, ?', [ $catID, $storeCode, $searchKey, $isLogged, $stores ]);

        return $result;
    }

    /**
     * Get list of items per category
     */
    public static function getPromoItems($storeCode = NULL, $isLogged, $stores = NULL)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_PromoItems_Get ?, ?, ?', [ $storeCode, $isLogged, $stores ]);

        return $result;
    }

    /**
     * Update items in the basket
     */
    public static function updateItemInBasket($data)
    {
        $query  = DB::connection('dbSqlSrv')->select('EXEC sp_OrderTempItems_Update ?, ?, ?', [ $data['id'], $data['qty'], $data['price'] ]);
        $result = $query[0]->_RETURN;

        return $result;
    }

    /**
     * Save temporary order details and items
     */
    public static function saveTempOrderID($orderID)
    {
        $query  = DB::connection('dbSqlSrv')->select('EXEC sp_OrderTemp_Insert ?', [ $orderID ]);
        $result = $query[0]->_RETURN;

        return $result;
    }

    public static function saveTempOrderItems($data)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_OrderTempItems_Insert ?, ?, ?, ?, ?, ?, ?', [ $data['tempID'], $data['itemID'], $data['itemName'], $data['itemPrice'], $data['itemQty'], $data['totalAmt'], $data['isPromo'] ]);
        
        return $result;
    }

    /**
     * Check order ID
     */
    public static function checkOrderID($orderID)
    {
        $query  = DB::connection('dbSqlSrv')->select('EXEC sp_OrderID_Check ?', [ $orderID ]);
        $result = $query[0]->_RETURN;

        return $result;
    }

    /**
     * Get order details
     */
    public static function getOrderDetails($orderID)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_OrderDetails_Get ?', [ $orderID ]);

        return $result;
    }

    /**
     * Save customer order
     */
    public static function saveOrder($data)
    {
        $query = DB::connection('dbSqlSrv')->select('EXEC sp_Orders_Insert ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', [ $data['orderID'], $data['customerID'], $data['orderDate'], $data['orderType'], $data['orderAmt'], $data['orderStatus'], $data['payOption'], $data['payStatus'], $data['origin'], $data['createdBy'], $data['createdAt'], $data['addressID'], $data['amtChange'], $data['remarks'], $data['delTime'] ]);
        $result = $query[0]->_RETURN;

        return $result;
    }

    /**
     * Save order items
     */
    public static function saveOrderItems($data)
    {
        $query = DB::connection('dbSqlSrv')->select('EXEC sp_OrderItems_Insert ?, ?, ?, ?, ?, ?, ?', [ $data['orderID'], $data['itemID'], $data['itemName'], $data['itemPrice'], $data['quantity'], $data['totAmt'], $data['promo'] ]);
        $result = $query[0]->_RETURN;

        return $result;
    }

    /**
     * Save order status
     */

    public static function saveOrderStatus($orderID, $status)
    {
        $query = DB::connection('dbSqlSrv')->select('EXEC sp_OrderStatus_Insert ?, ?', [ $orderID, $status ]);
        $result = $query[0]->_RETURN;

        return $result;
    }

    /**
     * Item Price per store
     */
    public static function itemPricePerStore($itemID, $storeCode)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_ItemPricePerStore_Get ?, ?', [ $itemID, $storeCode ]);
        
        return $result[0]->price;
    }


    public static function getOrderStatus($orderID)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_OrderStatus_Get ?', [ $orderID ]);
        
        return $result;
    }
}
