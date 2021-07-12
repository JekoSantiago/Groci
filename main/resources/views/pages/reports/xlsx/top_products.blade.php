<table>
	<tr>
		<td colspan="10" style="font-weight: 500; font-size: 17px; font-family: 'Calibri Light'; text-align: left;">TOP 15 PRODUCTS REPORT</td>
	</tr>
	<tr>
		<td colspan="10">&nbsp;</td>
	</tr>
    <tr>
        <td colspan="3">&nbsp;</td>
        @foreach($dateRange as $date)
		<td style="font-weight: 500; font-size: 10px; font-family: 'Calibri Light'; text-align: left; text-align: center" colspan="2">{{ date('M j, Y', strtotime($date)) }}</td>
        @endforeach
	</tr>
	<tr>
        <td style="font-weight: 500; font-size: 10px; font-family: 'Calibri Light'; text-align: center;">RANK</td>
		<td style="font-weight: 500; font-size: 10px; font-family: 'Calibri Light'; text-align: left; width: 40px;">PRODUCT NAME</td>
		<td style="font-weight: 500; font-size: 10px; font-family: 'Calibri Light'; text-align: center;">TOTAL</td>

        @for($i=1; $i<=$countDays; $i++)
		<td style="font-weight: 500; font-size: 10px; font-family: 'Calibri Light'; text-align: center;">QTY</td>
		<td style="font-weight: 500; font-size: 10px; font-family: 'Calibri Light'; text-align: center;">AMT</td>
        @endfor
	</tr>
    @foreach($data as $i)
    <tr>
        <td style="text-align: left; font-size: 10px; font-family: 'Calibri'; text-align: center;">{{ $i['rank'] }}</td>
		<td style="text-align: left; font-size: 10px; font-family: 'Calibri'; text-align: left; width: 40px;">{{ $i['item_name'] }}</td>
		<td style="text-align: left; font-size: 10px; font-family: 'Calibri'; text-align: center;">{{ $i['total_qty'] }}</td>
        @php 
        $items = json_decode($i['items']);
        $x = 0;
        foreach($dateRange as $d) :
        $dd = date('Ymd', strtotime($d));
        @endphp
		<td style="text-align: left; font-size: 10px; font-family: 'Calibri'; text-align: center;">{{ $items[$x]->$dd->QTY }}</td>
		<td style="text-align: left; font-size: 10px; font-family: 'Calibri'; text-align: center;">{{ number_format($items[$x]->$dd->AMT, 2, '.', '') }}</td>
        @php 
        $x++;
        endforeach;
        @endphp
	</tr>  
    @endforeach
</table>