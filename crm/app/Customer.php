<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Customer extends Model
{
    public static function getCustomers($customerID = NULL, $isActive = NULL, $addressID = NULL, $scode)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_Customers_Get ?, ?, ?, ?', [ $customerID, $isActive, $addressID, $scode ]);

        return $result;
    }

    public static function getCustomerDetails($customerID = NULL, $email = NULL)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_CustomerDetails_Get ?, ?', [ $customerID, $email ]);

        return $result;
    }

    public static function getCustomerAddress($type = NULL, $email = NULL, $addressID = NULL, $storeCode = NULL)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_CustomerAddress_Get ?, ?, ?, ?', [ $type, $email, $addressID, $storeCode ]);

        return $result;
    }

    public static function getCustomerAddressDetails($customerID, $addressID)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_CustomerAddressDetails_Get ?, ?', [ $customerID, $addressID ]);

        return $result;
    }

    public static function saveCustomer($data)
    {
        $result  = DB::connection('dbSqlSrv')->select('EXEC sp_Customer_Insert ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', [ $data['lastName'], $data['firstName'], $data['email'], $data['contactNum'], $data['password'], $data['storeCode'], $data['regFrom'], $data['regDate'], $data['address'], $data['city'], $data['province'], $data['landmarks'], $data['addType'], $data['code'], $data['customerID'], $data['addressID'], $data['isConfirm'], $data['isActive'] ]);
        
        return $result;
    }

    public static function saveCustomerAddress($data)
    {
        $query  = DB::connection('dbSqlSrv')->select('EXEC sp_CustomerAddress_Insert ?, ?, ?, ?, ?, ?', [ $data['customerID'], $data['address'], $data['city'], $data['province'], $data['landmarks'], $data['addType'] ]);
        $result = $query[0]->_RETURN;

        return $result;
    }


    public static function updateConfirmationStatus($data)
    {
        $query = DB::connection('dbSqlSrv')->select('EXEC sp_CustomerConfirmationStatus_Update ?, ?, ?, ?', [ $data['customerID'], $data['isConfirm'], $data['addressID'], $data['remarks'] ]);
        $result = $query[0]->_RETURN;

        return $result;
    }

    public static function checkNewCustomer($storeCode)
    {
        $result  = DB::connection('dbSqlSrv')->select('EXEC sp_CheckNewCustomer_Get ?', [ $storeCode ]);
        
        return $result;  
    }

    public static function tagNewCustomer($customerID)
    {
        $query = DB::connection('dbSqlSrv')->select('EXEC sp_CustomerTag_Insert ?', [ $customerID ]);
        $result = $query[0]->_RETURN;

        return $result;
    } 

    public static function checkConfirmationCode($data)
    {
        $query = DB::connection('dbSqlSrv')->select('EXEC sp_ConfirmationCode_Get ?, ?', [ $data['code'], $data['storeCode'] ]);
        $result = $query[0]->_RETURN;

        return $result;
    } 

    public static function getAllCustomers()
    {
        $result  = DB::connection('dbSqlSrv')->select('EXEC sp_AllCustomers_Get');
        
        return $result;  
    }

    public static function customerDelete($customerID)
    {
        $query = DB::connection('dbSqlSrv')->select('EXEC sp_Customers_Delete ?', [ $customerID ]);
        $result = $query[0]->_RETURN;

        return $result;
    }

    public static function countTagCustomer($ids)
    {
        $query = DB::connection('dbSqlSrv')->select('EXEC sp_CustomerTagCount_Get ?', [ $ids ]);
        $result = $query[0]->tagCount;

        return $result;
    }
}