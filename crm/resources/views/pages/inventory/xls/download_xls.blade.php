<table>
	<tr>
        <td style="font-weight: 500; font-size: 11px; font-family: 'Calibri'; text-align: left;">ID</td>
		<td style="font-weight: 500; font-size: 11px; font-family: 'Calibri'; text-align: left;">Category Name</td>
		<td style="font-weight: 500; font-size: 11px; font-family: 'Calibri'; text-align: left;">SKU</td>
		<td style="font-weight: 500; font-size: 11px; font-family: 'Calibri'; text-align: left;">Item Name</td>
        <td style="font-weight: 500; font-size: 11px; font-family: 'Calibri'; text-align: left;">Price</td>
		<td style="font-weight: 500; font-size: 11px; font-family: 'Calibri'; text-align: left;">Quantity</td>
		<td style="font-weight: 500; font-size: 11px; font-family: 'Calibri'; text-align: left;">Effective Date</td>
		<td style="font-weight: 500; font-size: 11px; font-family: 'Calibri'; text-align: left;">Promo</td>
		<td style="font-weight: 500; font-size: 11px; font-family: 'Calibri'; text-align: left;">Store Code</td>
	</tr>
	@foreach($data as $i) :
	<tr>
		<td style="text-align: left; font-family: 'Calibri';">{{ trim($i['item_id']) }}</td>
		<td style="text-align: left; font-family: 'Calibri';">{{ trim($i['category_name']) }}</td>
		<td style="text-align: left; font-family: 'Calibri';">{{ trim($i['sku']) }}</td>
		<td style="text-align: left; font-family: 'Calibri';">{{ trim($i['item_name']) }}</td>
		<td style="text-align: left; font-family: 'Calibri';">{{ (empty($i['reg_price'])) ? $i['price'] : $i['reg_price'] }}</td>
        <td style="text-align: left; font-family: 'Calibri';">{{ trim($i['stocks_on_hand']) }}</td>
		<td style="text-align: left; font-family: 'Calibri';">{{ trim(date('Y-m-d')) }}</td>
		<td style="text-align: left; font-family: 'Calibri';">{{ (empty($i['reg_price'])) ? $i['is_promo'] : $i['reg_is_promo'] }}</td>
		<td style="text-align: left; font-family: 'Calibri';">{{ trim($scode) }}</td>
	</tr>
	@endforeach
</table>