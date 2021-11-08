<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Alfamart Delivery">
    <meta name="author" content="Alfamart Delivery">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Shop Alfamart - {{ $page }}</title>
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/icons/css/materialdesignicons.min.css') }}" media="all" rel="stylesheet" type="text/css" />
    <link href="{{ asset('vendor/select2/css/select2-bootstrap.css') }}" />
    <link href="{{ asset('vendor/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/datatables/datatables.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/osahan.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/owl-carousel/owl.carousel.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/owl-carousel/owl.theme.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/plugin/notifications/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('vendor/plugin/daterangepicker/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}" type="text/javascript"></script>

</head>
<body>

    @yield('contents')

    @include('pages.partials.announcement')

    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/plugin/moments/js/moment.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/plugin/daterangepicker/daterangepicker.min.js') }}" type="text/javascript" ></script>
    <script src="{{ asset('vendor/plugin/notifications/bootbox.min.js') }}" type="text/javascript" ></script>
    <script src="{{ asset('vendor/plugin/notifications/sweetalert2.min.js') }}" type="text/javascript" ></script>
    <script src="{{ asset('vendor/select2/js/select2.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/owl-carousel/owl.carousel.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/datatables/datatables.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        var webURL = '{!! url('/') !!}';
        var sessBasket = '{!! Session::get('transType') !!}';
        var isLogged = '{!! Session::get('isLogged') !!}';
        var maxtime = '{!! env('MAX_DEL_TIME') !!}';
        var addtomin = '{!! env('MIN_DEL_TIME') !!}';

        function updateClock ( )
        {
            var today = "{{ date('F j, Y') }}";
            var clientTime = new Date();
            var currentTime = new Date ();
            var timeOffset = 8 * 60 * 60 * 1000;
            currentTime.setTime(clientTime.getTime() + timeOffset);

            var currentHours = currentTime.getUTCHours ( );
            var currentMinutes = currentTime.getUTCMinutes ( );
            var currentSeconds = currentTime.getUTCSeconds ( );
            var currentDay = currentTime.getUTCDay();
            var day = currentTime.getDate();
            var currentMonth = currentTime.getMonth();
            var currentYear = currentTime.getFullYear();

            var dayArr = new Array("Sun","Mon","Tue","Wed","Thu","Fri","Sat");
            var monthArr = new Array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");

            currentMinutes = ( currentMinutes < 10 ? "0" : "" ) + currentMinutes;
            currentSeconds = ( currentSeconds < 10 ? "0" : "" ) + currentSeconds;

            var timeOfDay = ( currentHours < 12 ) ? "AM" : "PM";
            currentHours = ( currentHours > 12 ) ? currentHours - 12 : currentHours;
            currentHours = ( currentHours == 0 ) ? 12 : currentHours;

            var currentTimeString = dayArr[currentDay] + ", " + today + " " + currentHours + ":" + currentMinutes + " " + timeOfDay;
            document.getElementById("clock").innerHTML = currentTimeString;
        }

        setInterval("updateClock()", 1000);

        if(isLogged == 1)
        {
            var SESSION_EXP_TIME = parseInt("{{ config('app.session_exp_time') }}");
            var activityTimeout = setTimeout(inActive, SESSION_EXP_TIME);

            function resetActive(){
                clearTimeout(activityTimeout);
                activityTimeout = setTimeout(inActive, SESSION_EXP_TIME);
            }

            function inActive() {
                swal.fire({
                    title: "For your information!",
                    text: "Session already expired. Please login again!.",
                    icon: "warning",
                    timer: 10000,
                    showConfirmButton: false
                });

                $(window.location).attr('href', webURL + '/logout');
            }

            $(document).bind('mousemove keyup click keypress blur change scroll',function(){
                resetActive();
            });
        }
    </script>
    <script src="{{ asset('js/custom.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/account.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/cart.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/locator.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            //console.log(getCookie("GDPR"));
			if(!getCookie("GDPR")){
	        	$('#home-modal').modal('toggle');

			} else {
				$('#home-modal').modal('hide');
			}

		    $("#okCookies").click(function(){
                $('#home-modal').modal('hide');
			    var d = new Date();
			    d.setTime(d.getTime() + (7*24*60*60*1000));
			    var expires = "expires="+ d.toUTCString();
			    document.cookie = "GDPR" + "=" + "TRUE" + ";" + expires + ";path=/";
            });

            $('.order-list-tabel').DataTable({
                "bFilter": false,
                "bLengthChange": false,
                "bAutoWidth": false,
                "ordering": false,
                "bInfo": false,
            });
        });

        function getCookie(cname) {
		    var name = cname + "=";
		    var ca = document.cookie.split(';');
		    for(var i = 0; i < ca.length; i++) {
		        var c = ca[i];
		        while (c.charAt(0) == ' ') {
		            c = c.substring(1);
		        }
		        if (c.indexOf(name) == 0) {
		            return c.substring(name.length, c.length);
		        }
		    }

		    return "";
		}
    </script>
</body>
</html>
