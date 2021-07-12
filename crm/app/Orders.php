<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Orders extends Model
{
    public static function getOrders($storeCode, $orderID = NULL)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_Orders_Get ?, ?', [ $storeCode, $orderID ]);

        return $result;
    }

    public static function getOrderDetails($orderID)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_OrderDetails_Get ?', [ $orderID ]);

        return $result;
    }

    public static function getCartItems($orderID)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_OrderItems_Get ?', [ $orderID ]);

        return $result;
    }

    public static function saveItemsToBasket($data)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_OrderItems_Insert ?, ?, ?, ?, ?, ?, ?', [ $data['orderID'], $data['itemID'], $data['itemName'], $data['itemPrice'], $data['itemQty'], $data['totalAmt'], $data['promo'] ]);
        
        return $result;
    }

    public static function updateBasketItems($data)
    {
        $query  = DB::connection('dbSqlSrv')->select('EXEC sp_OrderItems_Update ?, ?, ?, ?', [ $data['orderItemID'], $data['qty'], $data['itemPrice'], $data['orderID'] ]);
        $result = $query[0]->_RETURN;

        return $result;
    }

    public static function getCustomers($customerID = NULL, $isActive = NULL, $addressID = NULL, $storeCode)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_Customers_Get ?, ?, ?, ?', [ $customerID, $isActive, $addressID, $storeCode ]);

        return $result;
    }

    public static function saveOrder($data)
    {
        $query  = DB::connection('dbSqlSrv')->select('EXEC sp_Orders_Insert ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', [ $data['orderID'], $data['customerID'], $data['orderDate'], $data['orderType'], $data['orderAmount'], $data['orderStatus'], $data['payOption'], $data['payStatus'], $data['origin'], $data['createdBy'], $data['createAt'], $data['addressID'], $data['changeFor'], $data['remarks'], $data['deliveryTime']  ]);
        $result = $query[0]->_RETURN;

        return $result;
    }

    public static function minimumCharge()
    {
        $query  = DB::connection('dbSqlSrv')->select('EXEC sp_CurrentMinimumCharge_Get');
        $result = $query[0]->amount;

        return $result;
    }

    public static function deliveryCharge()
    {
        $result  = DB::connection('dbSqlSrv')->select('EXEC sp_CurrentDeliveryCharge_Get');
        
        return $result;
    }

    public static function updateOrderStatus($orderID, $status, $receipt = NULL, $payStatus = NULL)
    {
        $query  = DB::connection('dbSqlSrv')->select('EXEC sp_OrderStatus_Update ?, ?, ?, ?', [ $orderID, $status, $receipt, $payStatus ]);
        $result = $query[0]->_RETURN;

        return $result;
    }

    public static function insertCashChange($data)
    {
        $query  = DB::connection('dbSqlSrv')->select('EXEC sp_CashChange_Insert ?, ?, ?', [ $data['orderID'], $data['amount'], $data['modifiedBy'] ]);
        $result = $query[0]->_RETURN;

        return $result;
    }

    public static function checkNewOrders($storeCode)
    {
        $result  = DB::connection('dbSqlSrv')->select('EXEC sp_CheckNewOrders_Get ?', [ $storeCode ]);
        
        return $result; 
    }

    public static function tagNewOrders($orderID)
    {
        $query = DB::connection('dbSqlSrv')->select('EXEC sp_OrderTag_Insert ?', [ $orderID ]);
        $result = $query[0]->_RETURN;

        return $result;
    } 

    public static function countTagOrders($ids)
    {
        $query = DB::connection('dbSqlSrv')->select('EXEC sp_OrderTagCount_Get ?', [ $ids ]);
        $result = $query[0]->tagCount;

        return $result;
    }

    public static function updateStoreItemsInvQty($data)
    {
        $query  = DB::connection('dbSqlSrv')->select('EXEC sp_StoreItemsInventory_Update ?, ?, ?', [ $data['itemID'], $data['qty'], $data['storeCode'] ]);
        $result = $query[0]->_RETURN;

        return $result;
    }

    public static function saveOrderLogs($data)
    {
        $query  = DB::connection('dbSqlSrv')->select('EXEC sp_OrderLogs_Insert ?, ?, ?, 2', [ $data['orderID'], $data['itemID'], $data['message'], $data['createdBy'] ]);
        $result = $query[0]->_RETURN;

        return $result;
    }

    public static function updateOrderAmount($data)
    {
        $query  = DB::connection('dbSqlSrv')->select('EXEC sp_OrderAmount_Update ?, ?', [ $data['orderID'], $data['amount'] ]);
        $result = $query[0]->_RETURN;
        
        return $result;
    }

    public static function saveCancelRemarks($data)
    {
        $query = DB::connection('dbSqlSrv')->select('EXEC sp_CancelOrderRemarks_Insert ?, ?, ?', [ $data['orderID'], $data['remarks'], $data['createdBy'] ]);
        $result = $query[0]->_RETURN;
        
        return $result;
    }
         
    /**
     * Get list of items per category
     */
    public static function getProductItems($catID = NULL, $storeCode = NULL, $searchKey = NULL)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_ItemsPerCategory_Get ?, ?, ?, ?, ?', [ $catID, $storeCode, $searchKey, 0, NULL ]);

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
     * show order status
     */
    public static function getOrderStatus($orderID)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_OrderStatus_Get ?', [ $orderID ]);

        return $result;
    }
}

