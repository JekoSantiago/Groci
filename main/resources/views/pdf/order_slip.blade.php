<!DOCTYPE html>
<html lang="en" >
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<title>Shop Alfamart</title>

	<!-- Global stylesheets -->
	<style type="text/css" media="all">
		@page{ margin: 0; }
		html,
        body {
            margin: 0 auto !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
            font-family: 'Arial';
        }

        .page_wrapper {
        	width: 500px;
        	margin: 0 auto 20px auto;
			padding: 40px 20px;
        }

		.row {
			margin-left: 0px;
			margin-right: 0px;
		}

        table {
        	background-color: #ffffff;
        	width: 100%;
        	max-width: 500px;
        }
	</style>
</head>
<body>
<div class="page_wrapper">
	<div class="row">
		<div style="margin-bottom: 20px">
			<table cellspacing="0" cellpadding="0" border="0" align="center">
				<tr>
					<td style="width: 50% padding: 0px;">
						<img src="{{ url('assets/images/Alfamart-logo.fw.png') }}" alt="Shop Alfamart" style="height: 45px">
					</td>
					<td style="width: 50% padding: 0px; text-align: right; font-family: 'Arial';">
						<h3>Order Slip</h3>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="row">
		<div style="margin-bottom: 15px">
			<table class="table" cellspacing="0" cellpadding="0" border="0" align="center">
				<tr>
					<td style="width: 66.66666667%; font-size: 11px; font-weight: bold; font-family: 'Arial'; text-align: right; padding: 6px 10px;">OS NO. :</td>
					<td style="border-bottom: 1px solid #000; font-size: 14px; font-weight: bold; font-family: 'Arial'; padding: 6px;">{{ $details[0]->order_id }}</td>
				</tr>
			</table>
		</div>
	</div>
	<div style="font-size: 9px; font-family: 'Arial'; font-weight: bold; margin-bottom: 5px">Fill out the following information as reference for delivery :</div>
	<div class="row">
		<div style="margin-bottom: 3px">
			<table cellspacing="0" cellpadding="0" border="0" align="center">
				<tr>
					<td style="font-size: 11px; font-weight: bold; font-family: 'Arial'; padding: 6px 6px 6px 0px;">CUSTOMER NAME :</td>
					<td colspan="3" style="border-bottom: 1px solid #000; font-size: 11px; font-family: 'Arial'; padding: 6px;">{{ $details[0]->customer_name }}</td>
				</tr>
				<tr>
					<td style="font-size: 11px; font-weight: bold; font-family: 'Arial'; padding: 6px 6px 6px 0px;">ADDRESS :</td>
					<td colspan="3" style="border-bottom: 1px solid #000; font-size: 11px; font-family: 'Arial'; padding: 6px;">{{ $details[0]->address }} {{ $details[0]->city }}, {{ $details[0]->province_name }}</td>
				</tr>
				<tr>
					<td style="font-size: 11px; font-weight: bold; font-family: 'Arial'; padding: 6px 6px 6px 0px;">EMAIL ADDRESS :</td>
					<td colspan="3" style="border-bottom: 1px solid #000; font-size: 11px; font-family: 'Arial'; padding: 6px;">{{ $details[0]->email_address }}</td>
				</tr>
				<tr>
					<td style="font-size: 11px; font-weight: bold; font-family: 'Arial'; padding: 6px 6px 6px 0px;">CONTACT NO. :</td>
					<td colspan="3" style="border-bottom: 1px solid #000; font-size: 11px; font-family: 'Arial'; padding: 6px;">{{ $details[0]->contact_num }}</td>
				</tr>
				<tr>
					<td style="font-size: 11px; font-weight: bold; font-family: 'Arial'; padding: 6px 6px 6px 0px;">DELIVERY DATE :</td>
					<td style="border-bottom: 1px solid #000; font-size: 11px; font-family: 'Arial'; padding: 6px;">{{ $dDate }}</td>
					<td style="font-size: 11px; font-weight: bold; font-family: 'Arial'; text-align: right; padding: 6px 6px 6px 0px;">DELIVERY TIME :</td>
					<td style="border-bottom: 1px solid #000; font-size: 11px; font-family: 'Arial'; padding: 6px;">{{ $dTime }}</td>
				</tr>
				<tr>
					<td style="font-size: 11px; font-weight: bold; font-family: 'Arial'; padding: 6px 6px 6px 0px;">CHANGE FOR :</td>
					<td style="border-bottom: 1px solid #000000; font-size: 11px; font-family: 'Arial'; padding: 6px;">{{ $details[0]->change_for }}</td>
					<td colspan="2" style="padding: 6px;"></td>
				</tr>
			</table>
		</div>
	</div>
	<div class="row">
		<div style="margin-bottom: 20px">
			<table cellspacing="0" cellpadding="0" border="0" align="center">
				<tr>
					<td style="font-size: 11px; font-weight: bold; font-family: 'Arial'; padding: 6px 6px 6px 0px;">CUSTOMER REMARKS : </td>
				</tr>
				<tr>
					<td colspan="4" style="border-bottom: 1px solid #000; font-size: 11px; font-family: 'Arial'; height:40px; padding: 0px 6px;">{{ $details[0]->remarks }}</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="row">
		<div style="margin-bottom: 20px">
			<table cellspacing="0" cellpadding="0" border="1" align="center">
				<thead style="background-color: #ddd;">
					<tr>
						<th style="width: 8.33333333%; font-size: 11px; font-weight: bold; font-family: 'Arial'; text-align: center; padding: 10px 6px;">NO.</th>
						<th style="width: 75%; font-size: 11px; font-weight: bold; font-family: 'Arial'; text-align: left; padding: 10px 6px;">ITEM DESCRIPTION</th>
						<th style="width: 16.66666667%; font-size: 11px; font-weight: bold; font-family: 'Arial'; text-align: center; padding: 10px 6px;">QUANTITY</th>
					</tr>
				</thead>
				<tbody>
				@foreach($data['items'] as $i)
					<tr>
						<td style="font-size: 11px; font-family: 'Arial'; text-align: center; padding: 6px;">{{ $i['num'] }}</td>
						<td style="font-size: 11px; font-family: 'Arial'; padding: 6px;">{{ $i['item_name'] }}</td>
						<td style="font-size: 11px; font-family: 'Arial'; text-align: center; padding: 6px;">{{ $i['qty'] }}</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</div>
	<div class="row">
		<div style="margin-bottom: 20px">
			<table cellspacing="0" cellpadding="0" border="0" align="center">
				<tr>
					<td style="width: 20.66666667%; 0px; font-size: 11px; font-weight: bold; font-family: 'Arial'; padding: 6px 6px 6px 0px;">RECEIPT NO :</td>
					<td style="width: 39.66666667%; border-bottom: 1px solid #000; font-size: 14px; font-weight: bold; font-family: 'Arial'; padding: 6px;">{{ $details[0]->receipt_num }}</td>
					<td style="width: 39.66666667%;"></td>
				</tr>
			</table>
		</div>
	</div>
	<div style="font-size: 9px; font-family: 'Arial'; font-weight: bold; margin-bottom: 30px">Please affix your signature as proof of receiving your receipt and orders completely and in good condition :</div>
	<div class="row">
		<div style="margin-bottom: 20px">
			<table cellspacing="0" cellpadding="0" border="0" align="center">
				<tr>
					<td style="width: 25%; padding: 6px;"></td>
					<td style="width: 50%; font-size: 11px font-family: 'Arial'; border-bottom: 1px solid #000; padding: 6px;"></td>
					<td style="width: 25%; padding: 6px;"></td>
				</tr>
				<tr>
					<td style="width: 25%; padding: 6px;"></td>
					<td style="width: 50%; font-size: 11px; font-weight: bold; font-family: 'Arial'; text-align: center; padding: 6px;"">CUSTOMER SIGNATURE</td>
					<td style="width: 25%; padding: 6px;"></td>
				</tr>
			</table>
		</div>
	</div>
</div>
</body>
</html>
