<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Inventory extends Model
{
    public static function storeItemsInventory($storeCode)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC sp_StoreItemsInventory_Get ?', [ $storeCode ]);

        return $result;
    }

    public static function updateStoreInventory($data)
    {
        $query = DB::connection('dbSqlSrv')->select('EXEC sp_ReplenishStoreInventory_Update ?, ?, ?, ?', [ $data['itemID'], $data['qty'], $data['storeCode'], $data['modifiedBy'] ]);
        $result = $query[0]->_RETURN;

        return $result;
    }

    public static function saveRegionalPrice($data)
    {
        $query = DB::connection('dbSqlSrv')->select('EXEC sp_RegionalPrice_Insert ?, ?, ?, ?, ?, ?, ?', [ $data['itemID'], $data['price'], $data['promo_price'], $data['effDate'], $data['createdBy'], $data['promo'], $data['storeCode'] ]);
        $result = $query[0]->_RETURN;

        return $result;
    }


}
