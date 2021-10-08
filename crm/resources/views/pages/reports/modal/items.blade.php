<div class="table-responsive">
    <table class="table no-wrap">
        <thead>
            <tr class="bg-teal">
                <th style="width: 21%">RECEIPT NO.</th>
                <th style="width: 20%">ITEM</th>
                <th style="width: 10%">ITEM PRICE</th>
                <th style="width: 10%">QTY</th>
                <th style="width: 10%">VOIDED</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item )
            <tr>
                <td>{{ $receipt }}</td>
                <td>{{ $item->item_name}}</td>
                <td>{{ number_format($item->item_price, 2, '.', '') }}</td>
                <td>{{ $item->qty }}</td>
                <td>{{ ($item->void == 0)  ? 'NO' : 'YES' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
