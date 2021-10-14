<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8"> <!-- utf-8 works for most cases -->
    <meta name="viewport" content="width=device-width"> <!-- Forcing initial-scale shouldn't be necessary -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- Use the latest (edge) version of IE rendering engine -->
    <title>Shop Alfamart | Order Confirmation Notification</title> <!-- The title tag shows in email notifications, like Android 4.4. -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Web Font / @font-face : BEGIN -->
    <!-- NOTE: If web fonts are not required, lines 9 - 26 can be safely removed. -->

    <!-- Desktop Outlook chokes on web font references and defaults to Times New Roman, so we force a safe fallback font. -->
    <!--[if mso]>
        <style>
            * {
                font-family: sans-serif !important;
            }
        </style>
    <![endif]-->

    <!-- All other clients get the webfont reference; some will render the font and others will silently fail to the fallbacks. More on that here: http://stylecampaign.com/blog/2015/02/webfont-support-in-email/ -->
    <!--[if !mso]><!-->
        <!-- insert web font reference, eg: <link href='https://fonts.googleapis.com/css?family=Roboto:400,700' rel='stylesheet' type='text/css'> -->
    <!--<![endif]-->

    <!-- Web Font / @font-face : END -->

    <!-- CSS Reset -->
    <style type="text/css">

        /* What it does: Remove spaces around the email design added by some email clients. */
        /* Beware: It can remove the padding / margin and add a background color to the compose a reply window. */
        html,
        body {
            margin: 0 auto !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
            font-family: 'Poppins', sans-serif;
        }

        /* What it does: Stops email clients resizing small text. */
        * {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        /* What is does: Centers email on Android 4.4 */
        div[style*="margin: 16px 0"] {
            margin:0 !important;
        }

        /* What it does: Stops Outlook from adding extra spacing to tables. */
        table,
        td {
            mso-table-lspace: 0pt !important;
            mso-table-rspace: 0pt !important;
        }

        /* What it does: Fixes webkit padding issue. Fix for Yahoo mail table alignment bug. Applies table-layout to the first 2 tables then removes for anything nested deeper. */
        table {
            border-spacing: 0 !important;
            border-collapse: collapse !important;
            table-layout: fixed !important;
            Margin: 0 auto !important;
        }
        table table table {
            table-layout: auto;
        }

        /* What it does: Uses a better rendering method when resizing images in IE. */
        img {
            -ms-interpolation-mode:bicubic;
        }

        /* What it does: Overrides styles added when Yahoo's auto-senses a link. */
        .yshortcuts a {
            border-bottom: none !important;
        }

        /* What it does: A work-around for iOS meddling in triggered links. */
        .mobile-link--footer a,
        a[x-apple-data-detectors] {
            color:inherit !important;
            text-decoration: underline !important;
		}

		table th {
			text-align: left;
			font-weight: 500;
			padding: 5px 10px;
			background-color: #FB031B;
			color: #FFF;
			font-size: 12px;
		}

		table td {
			padding: 5px 10px;
			font-size: 12px;
		}

		.main-content {
			width: 800px;
			margin: auto;
		}

		@media (max-width: 768px) {
			.main-content {
				width: 100%;
				margin: auto;
			}
		}

    </style>

    <!-- Progressive Enhancements -->
    <style>

        /* What it does: Hover styles for buttons */
        .button-td,
        .button-a {
            transition: all 100ms ease-in;
        }
        .button-td:hover,
        .button-a:hover {
            background: #C40000 !important;
            border-color: #C40000 !important;
        }

    </style>

</head>
<body width="100%" height="100%" bgcolor="#ffffff" style="margin: 0;">
	<table cellpadding="0" cellspacing="0" border="0" height="100%" width="100%" bgcolor="#ffffff" style="border-collapse:collapse;">
		<tr>
			<td valign="top">
    			<center style="width: 100%;">
					<!-- Visually Hidden Preheader Text : BEGIN -->
        			<div style="display:none;font-size:1px;line-height:1px;max-height:0px;max-width:0px;opacity:0;overflow:hidden;mso-hide:all;font-family: sans-serif;"></div>
       				<!-- Visually Hidden Preheader Text : END -->
       				<!--
			            Set the email width. Defined in two places:
			            1. max-width for all clients except Desktop Windows Outlook, allowing the email to squish on narrow but never go wider than 750px.
			            2. MSO tags for Desktop Windows Outlook enforce a 750px width.
			        -->
			        <div class="main-content">
						<!--[if (gte mso 9)|(IE)]>
			            <table cellspacing="0" cellpadding="0" border="0" width="750" align="center">
			            	<tr>
			            		<td>
					            <![endif]-->
					            	<!-- Email Header : BEGIN -->
						            <table cellspacing="0" cellpadding="0" border="0" align="center" width="100%">
						                <tr>
						                    <td style="padding-top: 50px; text-align: center">
						                       <img src="{{ url('img/Alfamart-logo.fw.png') }}" alt="Shop Alfamart" border="0" align="center" style="max-height: 80px;">
						                    </td>
						                </tr>
						            </table>
						            <!-- Email Header : END -->
					            	<!-- Email Body : BEGIN -->
           							<table cellspacing="0" cellpadding="0" border="0" align="center" bgcolor="#ffffff" width="100%">
					            		<tr>
					            			<td style="padding: 20px 20px 10px" colspan="2">
					            				<p style="margin:0px; border-radius: 5px; padding: 15px; font-size: 13px; background-color: #F8DA00;">
					            					<span style="margin-bottom: 10px; display: block;">Thank you for ordering at Shop Alfamart, {{ $name }}! Please see the order summary below with reference number <span style="font-weight: 600">{{ $orderID }}</span> </span>
												</p>
					            			</td>
					            		</tr>

					            		<tr>
					            			<td style="padding: 0px 20px 20px;" colspan="2">
					            				<p style="margin: 0px; height: 3px; background-color: #005AAA;">&nbsp;</p>
					            			</td>
					            		</tr>

					            		<tr>
					            			<td style="padding: 0px 20px 20px; width: 65%" valign="top">
					            				<p style="margin: 0px; font-size: 13px;">
					            					<span style="font-weight: 600; display: block;">Delivery Details</span>
												</p>

												<table cellspacing="0" cellpadding="0" border="0" width="100%" valign="top">
					            					<tr>
					            						<td style="width: 30%; font-size: 12px;">Name : </td>
					            						<td style="width: 70%; font-size: 12px;">{{ $detail[0]->customer_name }}</td>
					            					</tr>
					            					<tr>
					            						<td style="width: 30%; font-size: 12px;">Mobile No. : </td>
					            						<td style="width: 70%; font-size: 12px;">{{ $detail[0]->contact_num }}</td>
													</tr>
													<tr>
					            						<td style="width: 30%; font-size: 12px;">Address : </td>
					            						<td style="width: 70%; font-size: 12px;">{{ $detail[0]->address }} {{ $detail[0]->city }}, {{ $detail[0]->province_name }}</td>
													</tr>

													<tr>
					            						<td style="width: 30%; font-size: 12px;">Landmark : </td>
					            						<td style="width: 70%; font-size: 12px;">{{ $detail[0]->landmarks }}</td>
													</tr>
					            				</table>
											</td>
											<td style="padding: 0px 20px 20px; width: 35%" valign="top">
					            				<p style="margin: 0px; font-size: 13px;">
					            					<span style="font-weight: 600; display: block;">Payment Details</span>
												</p>
												<table cellspacing="0" cellpadding="0" border="0" width="100%" valign="top">
					            					<tr>
					            						<td style="width: 35%; font-size: 12px;">Payment Option : </td>
					            						<td style="width: 65%; font-size: 12px;">{{ $detail[0]->payment_option }}</td>
					            					</tr>
					            					<tr>
					            						<td style="font-size: 12px;">Amount Due : </td>
					            						<td style="font-size: 12px;">{{ $detail[0]->order_amount }}</td>
													</tr>
													<tr>
					            						<td style="font-size: 12px;">Change For : </td>
					            						<td style="font-size: 12px;">{{ $detail[0]->change_for }}</td>
													</tr>

													<tr>
					            						<td style="font-size: 12px;">Total Amount : </td>
					            						<td style="font-size: 12px;">{{ $detail[0]->order_amount }}</td>
					            					</tr>
					            				</table>
					            			</td>
					            		</tr>

					            		<tr>
					            			<td style="padding: 0px 20px 20px;" colspan="2">
					            				<p style="margin: 0px; font-size: 13px;">
					            					<span style="font-weight: 600; display: block;">Transaction Details</span>
												</p>
												<table cellspacing="0" cellpadding="0" border="0" width="100%">
					            					<tr>
					            						<td style="width: 20%; font-size: 12px;">Transaction Type : </td>
					            						<td style="width: 80%; font-size: 12px;">{{ $detail[0]->order_type }}</td>
					            					</tr>
					            					<tr>
					            						<td style="font-size: 12px;">Delivery Time : </td>
					            						<td style="font-size: 12px;">{{ ($detail[0]->delivery_time == 'PROMISE TIME') ? date('F j, Y', strtotime($detail[0]->order_date)).' between 1pm-3pm' : $detail[0]->delivery_time  }}</td>
													</tr>
													<tr>
					            						<td style="font-size: 12px;">Remarks : </td>
					            						<td style="font-size: 12px;">{{ $detail[0]->remarks }}</td>
													</tr>
					            				</table>
					            			</td>
					            		</tr>

					            		<tr>
					            			<td style="padding: 0px 20px 10px;" colspan="2">
					            				<p style="margin: 0px; font-size: 13px;">
					            					<span style="font-weight: 600; display: block;">Order Summary</span>
												</p>
											</td>
										</tr>

										<tr>
					            			<td style="padding: 0px 20px 20px;" colspan="2">
												<table cellspacing="0" cellpadding="0" border="0" width="100%">
													<thead>
														<tr>
															<th style="width: 55%">Item Name</th>
															<th style="width: 15%">Item Price</th>
															<th style="width: 10%; text-align: center">Qty</th>
															<th style="width: 20%">Sub Total</th>
														</tr>
													</thead>
					            					<tbody>
													@foreach($data['data']['items'] as $row)
														<tr>
															<td>{{ $row['item_name'] }}</td>
															<td>{{ $row['item_price'] }}</td>
															<td style="text-align: center">{{ $row['qty'] }}</td>
															<td>{{ $row['total_amount'] }}</td>
														</tr>
													@endforeach
													</tbody>
													<tfoot>
														<tr>
															<td colspan="3" style="text-align: right; font-weight: 500">Sub Total :</td>
															<td>Php {{ number_format($data['data']['amount'], 2) }}</td>
														</tr>
														<tr>
															<td colspan="3" style="text-align: right; font-weight: 500">Delivery Charge :</td>
															<td>Php {{ number_format($charges, 2) }}</td>
														</tr>
														<tr>
															<td colspan="3" style="text-align: right; font-weight: 500">Amount Due :</td>
															<td style="font-weight: 500">Php {{ number_format($data['data']['amount'] + $charges, 2) }}</td>
														</tr>
													</tfoot>
					            				</table>
					            			</td>
					            		</tr>

					            		<tr>
					            			<td style="padding: 0px 20px 10px;" colspan="2">
					            				<p style="margin: 0px; height: 3px; background-color: #005AAA;">&nbsp;</p>
					            			</td>
										</tr>

										<tr>
                                            <td style="font-size: 11px; font-weight: 500; color: #555555; text-align: center" colspan="2">
                                                &copy; Copyright 2020 Shop Alfamart. All Rights Reserved
                                            </td>
                                        </tr>

					            		<!-- Email Body : END -->
					            	</table>
            					<!--[if (gte mso 9)|(IE)]>
			            		</td>
			            	</tr>
			            </table>
			            <![endif]-->
			        </div>
    			</center>
			</td>
		</tr>
	</table>
</body>
</html>
