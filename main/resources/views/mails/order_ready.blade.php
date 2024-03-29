<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> <!-- utf-8 works for most cases -->
	<meta name="viewport" content="width=device-width"> <!-- Forcing initial-scale shouldn't be necessary -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- Use the latest (edge) version of IE rendering engine -->
	<title>Shop Alfamart | Registration Confirmation</title> <!-- The title tag shows in email notifications, like Android 4.4. -->
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
<body width="100%" height="100%" bgcolor="#ffffff" style="Margin: 0;">
    <table cellpadding="0" cellspacing="0" border="0" height="100%" width="100%" bgcolor="#ffffff" style="border-collapse:collapse;">
        <tr>
            <td valign="top">
                <center style="width: 100%;">
                    <!-- Visually Hidden Preheader Text : BEGIN -->
                    <div style="display:none;font-size:1px;line-height:1px;max-height:0px;max-width:0px;opacity:0;overflow:hidden;mso-hide:all;font-family: sans-serif;"></div>
                    <!-- Visually Hidden Preheader Text : END -->
                    <!--
                        Set the email width. Defined in two places:
                        1. max-width for all clients except Desktop Windows Outlook, allowing the email to squish on narrow but never go wider than 600px.
                        2. MSO tags for Desktop Windows Outlook enforce a 600px width.
                    -->
                    <div style="max-width: 500px; margin: auto;" >
                    <!--[if (gte mso 9)|(IE)]>
                        <table cellspacing="0" cellpadding="0" border="0" width="600" align="center">
                            <tr>
                                <td>
                                    <![endif]-->

                                    <!-- Email Header : BEGIN -->
                                    <table cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 600px;">
                                        <tr>
                                            <td style="padding-top: 50px; text-align: center">
                                                <img src="{{ url('assets/images/Alfamart-logo.fw.png') }}" alt="Shop Alfamart" border="0" align="center" style="max-height: 80px;">
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- Email Header : END -->

                                    <!-- Email Body : BEGIN -->
                                    <table cellspacing="0" cellpadding="0" border="0" align="center" bgcolor="#ffffff" width="100%" style="max-width: 600px;">
                                        <!-- 1 Column Text + Button : BEGIN -->
                                        <tr>
                                            <td>
                                                <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                                    <tr>
                                                        <td style="text-align: left; padding: 40px 0px; font-family: 'Poppins', sans-serif; font-size: 13px; mso-height-rule: exactly; line-height: 20px; color: #555555;">
                                                            Dear {{ $name }},
                                                            <br><br>
                                                            This is a quick update to let you know that your order is now {{ ($status == 'pick-up') ? 'ready for pickup at '.$store : 'out for delivery' }}.
                                                            <br><br>
                                                            To see the status of your order, kindly login to our website at <a href="http://Shop Alfamart.atp.ph">http://Shop Alfamart.atp.ph</a>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <!-- 1 Column Text + Button : BEGIN -->
                                    </table>
                                    <!-- Email Body : END -->

                                    <!-- Email Footer : BEGIN -->
                                    <table cellspacing="0" cellpadding="0" border="0">
                                        <tr>
                                            <td style="width: 100%; font-size: 11px; font-weight: 500; font-family: 'Poppins', sans-serif; color: #555555;">
                                                &copy; Copyright 2021 Shop Alfamart. All Rights Reserved
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- Email Footer : END -->
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
