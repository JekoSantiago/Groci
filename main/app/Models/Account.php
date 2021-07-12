<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Account extends Model
{
    /**
     * Login Accounts
     */
    public static function checkUser($email)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_CheckUser_Get ?', [ $email ]);

        return $result;
    }

    /**
     * Register User
     */
    public static function registerCustomer($data)
    {
        $query  = DB::connection('dbSqlSrv')->select('EXEC sp_Customer_Insert ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', [ $data['lastName'], $data['firstName'], $data['email'], $data['contactNum'], $data['password'], $data['storeCode'], $data['regFrom'], $data['regDate'], $data['address'], $data['city'], $data['province'], $data['landmark'], $data['addType'], $data['code'], $data['customerID'], $data['addressID'], $data['isConfirm'], $data['isActive'] ]);
        $result = $query[0]->_RETURN;

        return $result;
    }

    /**
     * Customer Details
     */
    public static function getCustomerDetails($customerID = NULL, $email = NULL)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_CustomerDetails_Get ?, ?', [ $customerID, $email ]);

        return $result;
    }

    public static function modifyCustomerDetail($data)
    {
        $query  = DB::connection('dbSqlSrv')->select('EXEC sp_CustomerDetails_Update ?, ?, ?, ?, ?', [ $data['firstName'], $data['lastName'], $data['mobileNum'], $data['password'], $data['customerID'] ]);
        $result = $query[0]->_RETURN;

        return $result;
    }

    /**
     * Get Customer Address
     */
    public static function getCustomerAddress($type = NULL, $email = NULL, $addressID = NULL, $storeCode = NULL)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_CustomerAddress_Get ?, ?, ?, ?', [ $type, $email, $addressID, $storeCode ]);

        return $result;
    }

    /**
     * Save Customer Address
     */
    public static function saveCustomerAddress($data)
    {
        $query = DB::connection('dbSqlSrv')->select('EXEC sp_CustomerAddress_Insert ?, ?, ?, ?, ?, ?, ?, ?, ?', [ $data['customerID'], $data['address'], $data['city'], $data['province'], $data['landmarks'], $data['type'], $data['storeCode'], $data['isConfirm'], $data['code'] ]);
        $result = $query[0]->_RETURN;

        return $result;
    }


    /**
     * Get Customer Orders
     */
    public static function customerOrders($customerID, $storeCode = NULL)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_CustomerOrders_Get ?, ?',[ $customerID, $storeCode ]);

        return $result;
    }

    public static function viewOrderItems($orderID)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_OrderItems_Get ?', [ $orderID ]);

        return $result;
    }

    /**
     * Activate customer account
     */
    public static function activateAccount($email)
    {
        $query  = DB::connection('dbSqlSrv')->select('EXEC sp_CustomerAccount_Activation ?', [ $email ]);
        $result = $query[0]->_RETURN;

        return $result;
    }

    /**
     * Update address landmark
     */
    public static function updateCustomerAddressLandmark($data)
    {
        $query = DB::connection('dbSqlSrv')->select('EXEC sp_CustomerAddressLandmark_Update ?, ?', [ $data['addressID'], $data['landmark'] ]);

        $response = [
            'addressID' => $query[0]->addressID,
            'RETURN'    => $query[0]->_RETURN
        ];

        return $response;
    }

    public static function getCode($email)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_Code_Get ?', [ $email ]);

        return $result;
    }

    public static function updateOrderStatus($orderID, $status, $receipt = NULL, $payStatus = NULL)
    {
        $query  = DB::connection('dbSqlSrv')->select('EXEC sp_OrderStatus_Update ?, ?, ?, ?', [ $orderID, $status, $receipt, $payStatus ]);
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
}
