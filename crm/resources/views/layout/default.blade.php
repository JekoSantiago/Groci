@php
	if(Session::get('isLogged') == false) :
        Redirect::to('/')->send();
    endif
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<title>Alfanda Express - CRM | {{ $page }}</title>

	<!-- Global stylesheets -->
	<link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600&display=swap" rel="stylesheet">
	<link href="{{ asset('assets/css/icons/icomoon/styles.css')}}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/bootstrap.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/core.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/components.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('assets/css/colors.css') }}" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<!--<script type="text/javascript" src="{{ asset('assets/js/plugins/loaders/pace.min.js') }}"></script> -->
	<script type="text/javascript" src="{{ asset('assets/js/core/libraries/jquery.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/core/libraries/bootstrap.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/plugins/loaders/blockui.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/plugins/ui/nicescroll.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/plugins/ui/drilldown.js') }}"></script>
	<!-- /core JS files -->

	<!-- Theme JS files -->
	<script type="text/javascript" src="{{ asset('assets/js/plugins/visualization/d3/d3.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/visualization/d3/d3_tooltip.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/switchery.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/plugins/notifications/jgrowl.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/plugins/ui/moment/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/daterangepicker.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/anytime.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/pickadate/picker.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/pickadate/picker.date.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/pickadate/picker.time.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/pickadate/legacy.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/plugins/notifications/sweet_alert.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/select2.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/plugins/notifications/pnotify.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/bootstrap_select.min.js') }}"></script>

    <script type="text/javascript" src="{{ asset('assets/js/core/app.js') }}"></script>
    <script type="text/javascript">
    	var webURL = '{!! url('/') !!}';
    </script><!--
	<script type="text/javascript" src="{{ asset('assets/js/pages/dashboard.js') }}"></script> -->
	<script type="text/javascript" src="{{ asset('assets/js/cms.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/notification.js') }}"></script>

	<script type="text/javascript" src="{{ asset('assets/js/plugins/ui/ripple.min.js') }}"></script>
	<!-- /theme JS files -->

</head>

<body>

	<!-- Main navbar -->
	<div class="navbar navbar-inverse bg-danger-800">
		<div class="navbar-header">
			<a class="navbar-brand" href="#">
				<img src="{{ asset('assets/images/Alfamart-logo.png') }}" alt="Alfanda Express">
			</a>

			<ul class="nav navbar-nav pull-right visible-xs-block">
				<li><!--
					<a data-toggle="collapse" data-target="#navbar-mobile">
						<i class="icon-tree5"></i>
					</a> -->
					<a href="{{ url('logout') }}"><i class="icon-switch2"></i></a>
				</li>
			</ul>
		</div>

		<div class="navbar-collapse collapse" id="navbar-mobile">
			<ul class="nav navbar-nav navbar-right">

				<li class="dropdown dropdown-user">
					<a class="dropdown-toggle" data-toggle="dropdown">
						<img src="{{ asset('assets/images/no_photo.fw.png') }}" alt="">
						<span>{{ base64_decode(Session::get('Emp_Name')) }}</span>
						<i class="caret"></i>
					</a>

					<ul class="dropdown-menu dropdown-menu-right">
						<li><a href="{{ url('logout') }}"><i class="icon-switch2"></i> Logout</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
	<!-- /main navbar -->


	<!-- Second navbar -->
	<div class="navbar navbar-default" id="navbar-second">
		<ul class="nav navbar-nav no-border visible-xs-block">
			<li>
				<a class="text-center collapsed" data-toggle="collapse" data-target="#navbar-second-toggle">
					<i class="icon-menu7"></i>
				</a>
			</li>
		</ul>

		<div class="navbar-collapse collapse" id="navbar-second-toggle">
			<ul class="nav navbar-nav navbar-nav-material">

                <li {{ (request()->segment(1) == 'orders') ? 'class=active' : '' }}>
                    <a href="{{ url('orders') }}">
                        <i class="icon-cart5 position-left"></i> Orders <span id="orderCount" class="label label-inline position-right bg-danger">{{ App\Services\OrderServices::countNewOrders(base64_decode(Session::get('LocationCode'))) }}</span>
                    </a>
				</li>
				<li id="customer" {{ (request()->segment(1) == 'customer') ? 'class=active' : '' }}>
                    <a href="{{ url('customer') }}">
                        <i class="icon-users4 position-left"></i> Customers <span id="custCount" class="label label-inline position-right bg-primary">{{ App\Services\CustomerServices::countNewCustomer(base64_decode(Session::get('LocationCode'))) }}</span>
                    </a>
				</li>
				<li id="customer" {{ (request()->segment(1) == 'inventory') ? 'class=active' : '' }}>
                    <a href="{{ url('inventory') }}">
                        <i class="icon-list3 position-left"></i> Store Inventory
                    </a>
				</li>
			</ul>
		</div>
	</div>
	<!-- /second navbar -->


	<!-- Page header -->
	<div class="page-header">
		<div class="page-header-content">
			<div class="page-title">
				<h4>
                    <i class="icon-arrow-left52 position-left"></i>
                    <span class="text-semibold">Home</span> - {{ (request()->segment(1) == 'cms') ? 'Content Management' : ucwords(request()->segment(1)) }}
					<small class="display-block">{{ App\Services\HelperServices::greetings() }} WELCOME TO {{ base64_decode(Session::get('Location')) }}</small>
				</h4>
			</div>
		</div>
	</div>
	<!-- /page header -->

	<!-- Page container -->
	<div class="page-container">

		<!-- Page content -->
		<div class="page-content">

            <!-- Main content -->
            <div class="content-wrapper">

                @yield('contents')

            </div>
            <!-- /main content -->

		</div>
		<!-- /page content -->

	</div>
	<!-- /page container -->

	<input type="hidden" id="customer_notification" value="{{ asset('assets/audio/text_notification.mp3') }}">
	<input type="hidden" id="order_notification" value="{{ asset('assets/audio/press_my_doorbell.wav') }}">

	<!-- Footer -->
	<div class="footer text-muted">
		&copy; {{ date('Y') }}. <a href="{{ url(config('app.web_url')) }}" target="_blank">Alfanda Express</a> - CRM. All Rights Reserved.
	</div>
	<!-- /footer -->

</body>
</html>
