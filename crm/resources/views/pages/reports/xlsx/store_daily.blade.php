<table>
	<tr>
		<td colspan="10" style="font-weight: 500; font-size: 17px; font-family: 'Calibri Light'; text-align: left;">STORE DAILY SALES REPORT</td>
	</tr>

	<tr>
		<td colspan="10">&nbsp;</td>
	</tr>

	<tr>
        <td style="font-weight: 500; font-size: 10px; font-family: 'Calibri Light'; text-align: left; width: 35px;">STORE</td>
		<td style="font-weight: 500; font-size: 10px; font-family: 'Calibri Light'; text-align: left; width: 22px;">DATE</td>
		<td style="font-weight: 500; font-size: 10px; font-family: 'Calibri Light'; text-align: left;">WD</td>
		<td style="font-weight: 500; font-size: 10px; font-family: 'Calibri Light'; text-align: left;">CS</td>
        <td style="font-weight: 500; font-size: 10px; font-family: 'Calibri Light'; text-align: left;">QTY</td>
		<td style="font-weight: 500; font-size: 10px; font-family: 'Calibri Light'; text-align: left;">NET SALES</td>
		<td style="font-weight: 500; font-size: 10px; font-family: 'Calibri Light'; text-align: left;">TC</td>
		<td style="font-weight: 500; font-size: 10px; font-family: 'Calibri Light'; text-align: left;">SPD</td>
		<td style="font-weight: 500; font-size: 10px; font-family: 'Calibri Light'; text-align: left;">STD</td>
		<td style="font-weight: 500; font-size: 10px; font-family: 'Calibri Light'; text-align: left;">APC</td>
	</tr>
	@foreach($data as $i) :
	<tr>
		<td style="text-align: left; font-size: 10px; font-family: 'Calibri';">{{ $i['store_name'] }}</td>
		<td style="text-align: left; font-size: 10px; font-family: 'Calibri';">{{ $i['trans_date'] }}</td>
		<td style="text-align: left; font-size: 10px; font-family: 'Calibri';">{{ $i['work_days'] }}</td>
		<td style="text-align: left; font-size: 10px; font-family: 'Calibri';">{{ $i['items']['CS'] }}</td>
		<td style="text-align: left; font-size: 10px; font-family: 'Calibri';">{{ (is_null($i['items']['QTY'])) ? 0 : $i['items']['QTY'] }}</td>
        <td style="text-align: left; font-size: 10px; font-family: 'Calibri';">{{ number_format($i['items']['AMT'], 2, '.', '') }}</td>
		<td style="text-align: left; font-size: 10px; font-family: 'Calibri';">{{ $i['items']['TC'] }}</td>
		<td style="text-align: left; font-size: 10px; font-family: 'Calibri';">{{ $i['items']['SPD'] }}</td>
		<td style="text-align: left; font-size: 10px; font-family: 'Calibri';">{{ $i['items']['STD'] }}</td>
		<td style="text-align: left; font-size: 10px; font-family: 'Calibri';">{{ number_format($i['items']['APC'], 3, '.', '') }}</td>
	</tr>
	@endforeach

	<tr>
		<td style="text-align: left; font-weight: 500; font-size: 10px; font-family: 'Calibri Light';">GRAND TOTAL</td>
		<td style="text-align: left; font-weight: 500; font-size: 10px; font-family: 'Calibri Light';">{{ $dateRange }}</td>
		<td style="text-align: left; font-weight: 500; font-size: 10px; font-family: 'Calibri Light';">{{ $countDays }}</td>
		<td style="text-align: left; font-weight: 500; font-size: 10px; font-family: 'Calibri Light';">{{ $total['CS'] }}</td>
        <td style="text-align: left; font-weight: 500; font-size: 10px; font-family: 'Calibri Light';">{{ (is_null($total['QTY'])) ? 0 : $total['QTY'] }}</td>
		<td style="text-align: left; font-weight: 500; font-size: 10px; font-family: 'Calibri Light';">{{ number_format($total['AMT'], 2, '.', '') }}</td>
        <td style="text-align: left; font-weight: 500; font-size: 10px; font-family: 'Calibri Light';">{{ $total['TC'] }}</td>
		<td style="text-align: left; font-weight: 500; font-size: 10px; font-family: 'Calibri Light';">{{ $total['SPD'] }}</td>
		<td style="text-align: left; font-weight: 500; font-size: 10px; font-family: 'Calibri Light';">{{ $total['STD'] }}</td>
		<td style="text-align: left; font-weight: 500; font-size: 10px; font-family: 'Calibri Light';">{{ number_format(($total['SPD'] / $total['STD']), 3, '.', '') }}</td>
	</tr>

	<tr>
		<td colspan="10">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="10" style="font-weight: 500; font-size: 10px; font-family: 'Calibri Light'; text-align: left;">LEGENDS</td>
	</tr>
	<tr>
		<td colspan="10" style="font-weight: 500; font-size: 10px; font-family: 'Calibri Light'; text-align: left;">WD : Working Days</td>
	</tr>
	<tr>
		<td colspan="10" style="font-weight: 500; font-size: 10px; font-family: 'Calibri Light'; text-align: left;">CS : Store Count</td>
	</tr>
	<tr>
		<td colspan="10" style="font-weight: 500; font-size: 10px; font-family: 'Calibri Light'; text-align: left;">TC : Transaction Count</td>
	</tr>
	<tr>
		<td colspan="10" style="font-weight: 500; font-size: 10px; font-family: 'Calibri Light'; text-align: left;">SPD : Average sales per day</td>
	</tr>
	<tr>
		<td colspan="10" style="font-weight: 500; font-size: 10px; font-family: 'Calibri Light'; text-align: left;">QTY : No. of item sold</td>
	</tr>
	<tr>
		<td colspan="10" style="font-weight: 500; font-size: 10px; font-family: 'Calibri Light'; text-align: left;">NET SALES : Total Sales</td>
	</tr>
	<tr>
		<td colspan="10" style="font-weight: 500; font-size: 10px; font-family: 'Calibri Light'; text-align: left;">STD : Average sales transaction per day</td>
	</tr>
	<tr>
		<td colspan="10" style="font-weight: 500; font-size: 10px; font-family: 'Calibri Light'; text-align: left;">APC : Average per customer</td>
	</tr>
</table>
