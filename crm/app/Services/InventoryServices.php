<?php
namespace App\Services;
use App\Inventory;
use App\Cms;

class InventoryServices
{
    public static function inventoryItems($storeCode)
    {
        $items = Inventory::storeItemsInventory($storeCode);
        $encode = [];

        foreach($items as $row) :
            if(is_null($row->reg_price)) :
                if($row->nat_is_promo == 1) :
                    $row->text   = 'Yes';
                    $row->bg     = 'bg-success-800';
                else :
                    $row->text   = 'No';
                    $row->bg     = 'bg-danger';
                endif;
                $row->regular_price = $row->nat_price;
                $row->promo_price   = ($row->nat_promo_price == '.00') ? '0.00' : $row->nat_promo_price;
            else :
                if($row->reg_is_promo == 1) :
                    $row->text   = 'Yes';
                    $row->bg     = 'bg-success-800';
                else :
                    $row->text   = 'No';
                    $row->bg     = 'bg-danger';
                endif;
                $row->regular_price = $row->reg_price;
                $row->promo_price   = ($row->reg_promo_price == '.00') ? '0.00' : $row->reg_promo_price;
            endif;

            $row->stocks_on_hand = (is_null($row->stocks_on_hand)) ? 0 : $row->stocks_on_hand;
            $row->pre_order_qty = (is_null($row->pre_order_qty)) ? 0 : $row->pre_order_qty;

			$encode[] = array_map("utf8_encode", (array)$row);

        endforeach;

        return $encode;
    }

    public static function replenishInventory($scode, $logUser)
    {
        $result = Inventory::storeItemsInventory($scode);
        $dataFromAPI = static::itemsFromPOSApi($scode);
        // dd($dataFromAPI);

        $skus = $dataFromAPI['skus'];
        $arrData = $dataFromAPI['data'];

        foreach($result as $row) :
            if(in_array($row->sku, $skus)) :
                $items = $arrData[$row->sku];
                $qty = ($items['quantity'] == 0) ? 0 : ($items['quantity'] * (config('app.percent_qty_sell') / 100));
                $qtyData = [
                    'itemID'     => $row->item_id,
                    'qty'        => ROUND($qty),
                    'storeCode'  => $scode,
                    'modifiedBy' => $logUser,
                ];

                $priceData = [
                    'itemID'      => $row->item_id,
                    'price'       => $items['price'],
                    'promo_price' => $items['promo_price'],
                    'effDate'     => date('Y-m-d', strtotime($items['eff_date'])),
                    'createdBy'   => $logUser,
                    'promo'       => $items['is_promo'],
                    'storeCode'   => $scode
                ];

                Inventory::updateStoreInventory($qtyData);
                Inventory::saveRegionalPrice($priceData);
            endif;
        endforeach;

        $response = [
            'message' => 'Inventory successfully replenish.'
        ];

        return $response;
    }

    public static function itemsFromPOSApi($code)
    {
        $branchCode = static::getStoreBranch($code);
        $data = array(
            'varBranch' => $branchCode,
            'varStore'  => $code,
            'varType'   => 'store',
            'varKey'    => 'MlMNtBnSxZe2hhjy49WkTw=='
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://10.245.65.232/atpss/api/GrociStore.php');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if($err) :
            $response = [
                'data' => NULL,
                'skus' => NULL
            ];
        else :
            $result = json_decode($response);
            $items = [];
            $skus  = [];
            foreach($result->Data as $row) :
                $items[$row->SKU] = [
                    'category' => $row->CATEGORYNAME,
                    'sku'      => $row->SKU,
                    'item'     => $row->ITEMNAME,
                    'price'    => $row->NORMALPRICE,
                    'promo_price' => $row->PROMOPRICE,
                    'quantity' => $row->QUANTITY,
                    'eff_date' => $row->EFFECTIVEDATE,
                    'is_promo' => $row->ISPROMO,
                    'scode'    => $row->STORECODE
                ];

                $skus[] = $row->SKU;
            endforeach;

            $response = [
                'data' => $items,
                'skus' => $skus,
            ];
        endif;

        return $response;
    }

    public static function getStoreBranch($code)
    {
        $result = Cms::storeDetails($code);

        return $result[0]->branch_code;
    }
}
