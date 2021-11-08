<table>
	<tr>
        <td style="font-weight: 500; font-size: 11px; font-family: 'Calibri'; text-align: left;">ID</td>
		<td style="font-weight: 500; font-size: 11px; font-family: 'Calibri'; text-align: left;">Category Name</td>
		<td style="font-weight: 500; font-size: 11px; font-family: 'Calibri'; text-align: left;">SKU</td>
		<td style="font-weight: 500; font-size: 11px; font-family: 'Calibri'; text-align: left;">Item Name</td>
        <td style="font-weight: 500; font-size: 11px; font-family: 'Calibri'; text-align: left;">Price</td>
		<td style="font-weight: 500; font-size: 11px; font-family: 'Calibri'; text-align: left;">Effective Date</td>
		<td style="font-weight: 500; font-size: 11px; font-family: 'Calibri'; text-align: left;">Is Promo</td>
        <td style="font-weight: 500; font-size: 11px; font-family: 'Calibri'; text-align: left;">Is Active</td>

	</tr>
	@foreach($data as $i) :
	<tr>
		<td style="text-align: left; font-family: 'Calibri';">{{ trim($i['item_id']) }}</td>
		<td style="text-align: left; font-family: 'Calibri';">{{ trim($i['category_name']) }}</td>
		<td style="text-align: left; font-family: 'Calibri';">{{ trim($i['sku']) }}</td>
		<td style="text-align: left; font-family: 'Calibri';">{{ trim($i['item_name']) }}</td>
		<td style="text-align: left; font-family: 'Calibri';">{{ ($i['is_promo'] == 1) ? $i['promo_price'] : $i['price'] }}</td>
        <td style="text-align: left; font-family: 'Calibri';">{{ $i['effective_date'] }}</td>
		<td style="text-align: left; font-family: 'Calibri';">{{ $i['is_promo'] }}</td>
        <td style="text-align: left; font-family: 'Calibri';">{{ $i['is_active'] }}</td>

	</tr>
	@endforeach
</table>
