<table>
	<tr>
        <td style="font-weight: 500; font-size: 11px; font-family: 'Calibri'; text-align: left;">ID</td>
		<td style="font-weight: 500; font-size: 11px; font-family: 'Calibri'; text-align: left;">Category Name</td>
		<td style="font-weight: 500; font-size: 11px; font-family: 'Calibri'; text-align: left;">SKU</td>
		<td style="font-weight: 500; font-size: 11px; font-family: 'Calibri'; text-align: left;">Item Name</td>
        <td style="font-weight: 500; font-size: 11px; font-family: 'Calibri'; text-align: left;">Price</td>
		<td style="font-weight: 500; font-size: 11px; font-family: 'Calibri'; text-align: left;">Effective Date</td>
		<td style="font-weight: 500; font-size: 11px; font-family: 'Calibri'; text-align: left;">Is Promo</td>
	</tr>
	@foreach($data as $i) :
	<tr>
		<td style="text-align: left; font-family: 'Calibri';">{{ trim($i->item_id) }}</td>
		<td style="text-align: left; font-family: 'Calibri';">{{ trim($i->category_name) }}</td>
		<td style="text-align: left; font-family: 'Calibri';">{{ trim($i->sku) }}</td>
		<td style="text-align: left; font-family: 'Calibri';">{{ trim($i->item_name) }}</td>
		<td style="text-align: left; font-family: 'Calibri';">0.00</td>
        <td style="text-align: left; font-family: 'Calibri';">{{ date('Y-m-d') }}</td>
		<td style="text-align: left; font-family: 'Calibri';">0</td>
	</tr>
	@endforeach
</table>