<div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title">Select items below</h5>
    </div>

    <table class="table datatable-basic" id='dt-order'>
        <thead>
            <tr>
                <th>CATEGORY</th>
                <th>SKU</th>
                <th>ITEM NAME</th>
                <th>PRICE</th>
                <th style="width: 100px !important" class="text-center">QUANTITY</th>
            </tr>
        </thead>
        <tbody>
        @foreach($items as $row)
            @php
                $price = (empty($row['reg_price'])) ? $row['nat_price'] : $row['reg_price'];
                $promo = (empty($row['reg_price'])) ? $row['is_promo'] : $row['reg_is_promo'];
                $value = $orderID.'@@'.$row['item_id'].'@@'.$row['item_name'].'@@'.$price.'@@'.$promo;
            @endphp
            <tr>
                <td>{{ strtoupper($row['category_name']) }}</td>
                <td>{{ $row['sku'] }}</td>
                <td>{{ $row['item_name'] }}</td>
                <td>{{ (empty($row['reg_price'])) ? $row['nat_price'] : $row['reg_price'] }}</td>
                <td>
                    <input type="text" class="form-control text-center" data-params="{{ $value }}" id="Quant" name="Quant_{{ $row['item_id'] }}" min="1" max="{{ $row['stocks_on_hand'] - $row['pre_order_qty'] }}">
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <input type="hidden" id="selectedItems">
</div>
