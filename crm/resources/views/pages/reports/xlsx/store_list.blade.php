<table>
	<tr>
		<td colspan="10" style="font-weight: 500; font-size: 17px; font-family: 'Calibri Light'; text-align: left;">STORE LIST</td>
	</tr>

	<tr>
		<td colspan="10">&nbsp;</td>
	</tr>
	<tr>
        <td style="font-weight: 500; font-size: 10px; font-family: 'Calibri Light'; text-align: left;">BRANCH CODE</td>
		<td style="font-weight: 500; font-size: 10px; font-family: 'Calibri Light'; text-align: left;">BRANCH NAME</td>
		<td style="font-weight: 500; font-size: 10px; font-family: 'Calibri Light'; text-align: left;">STORE CODE</td>
		<td style="font-weight: 500; font-size: 10px; font-family: 'Calibri Light'; text-align: left;">STORE NAME</td>
        <td style="font-weight: 500; font-size: 10px; font-family: 'Calibri Light'; text-align: left;">ADDRESS</td>
		<td style="font-weight: 500; font-size: 10px; font-family: 'Calibri Light'; text-align: left;">PROVINCE</td>
		<td style="font-weight: 500; font-size: 10px; font-family: 'Calibri Light'; text-align: left;">LATITUDE</td>
		<td style="font-weight: 500; font-size: 10px; font-family: 'Calibri Light'; text-align: left;">LONGITUDE</td>
		<td style="font-weight: 500; font-size: 10px; font-family: 'Calibri Light'; text-align: left;">IS ACTIVE</td>
	</tr>
    @foreach($stores as $store)
    <tr>
        <td style="text-align: left; font-size: 10px; font-family: 'Calibri'; text-align: left;">{{ $store->branch_code }}</td>
        <td style="text-align: left; font-size: 10px; font-family: 'Calibri'; text-align: left;">{{ $store->branch_name }}</td>
        <td style="text-align: left; font-size: 10px; font-family: 'Calibri'; text-align: left;">{{ $store->store_code }}</td>
        <td style="text-align: left; font-size: 10px; font-family: 'Calibri'; text-align: left;">{{ $store->store_name }}</td>
        <td style="text-align: left; font-size: 10px; font-family: 'Calibri'; text-align: left;">{{ $store->address }}</td>
        <td style="text-align: left; font-size: 10px; font-family: 'Calibri'; text-align: left;">{{ $store->province }}</td>
        <td style="text-align: left; font-size: 10px; font-family: 'Calibri'; text-align: left;">{{ $store->latitude }}</td>
        <td style="text-align: left; font-size: 10px; font-family: 'Calibri'; text-align: left;">{{ $store->longitude }}</td>
        <td style="text-align: left; font-size: 10px; font-family: 'Calibri'; text-align: left;">{{ $store->is_active }}</td></tr>
    @endforeach



</table>
