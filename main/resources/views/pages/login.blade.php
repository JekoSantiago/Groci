@extends('layout.base_tpl')
    @section('contents')
    <nav class="navbar navbar-light navbar-expand-lg bg-faded" style="background-color: transparent;">
        <div class="container">
            <a class="navbar-brand mr-auto mt-5 mt-lg-2 margin-auto top-categories-search-main" href="{{ url('/') }}"><img src="{{ asset('img/Alfamart-logo.fw.png') }}" alt="logo"></a>
        </div>
    </nav>

    <section class="account-page section-padding" style="padding: 0px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mx-auto" style="border: none; background: none; padding-left: 20px">
                    <a href="{{ url('/') }}" style="font-weight: 500; font-size: 14px; color: #111"><i class="mdi mdi-arrow-left-bold-circle"></i> Back to home page</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Start profile content -->
    <section class="account-page section-padding" style="padding: 10px 0px 40px">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mx-auto">
                    <div class="row no-gutters">
                        <div class="col-md-12">
                            <div class="card card-body">
                                <div class="text-center login-footer-tab">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="delivery" style="cursor: pointer">
                                                <img src="{{ asset('img/delivery.fw.png') }}" />
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="pickup" style="cursor: pointer">
                                                <img src="{{ asset('img/pickup.fw.png') }}" />
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="login-footer-tab">
                                    <div class="col-md-12" style="padding-top: 20px;">
                                        <div id="deliveryContent">
                                            <div class="row">
                                                <div class="form-group">
                                                    <label class="display-block">* Select type of delivery</label>
                                                    <div class="radio-holder">
                                                        <input type="radio" id="test1" value="Delivery Now" name="radio-group" checked>
                                                        <label for="test1">Delivery Now</label>
                                                    </div>
                                                    <div class="radio-holder">
                                                        <input type="radio" id="test2" value="Delivery Later" name="radio-group">
                                                        <label for="test2">Delivery Later</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <label style="display: block; width: 100%">* Delivery Date & Time</label>
                                                <p class="reg-txt-header" id="deliveryNow">Today ({{ date('F j, Y') }} Philippine local date & time) expected delivery between 1pm and 3pm upon confirmation of orders. </p>
                                                <div class="col-md-12" id="deliveryLater" style="display: none; padding-left: 0px; padding-right: 0px;">
                                                    <div style="width: 40%; float: left; padding-right: 10px;">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" name="deliverLaterDate" id="deliverLaterDate">
                                                        </div>
                                                    </div>
                                                    <div style="width: 20%; float: left; padding-right: 10px;">
                                                        <div class="form-group">
                                                            <select class="select2 form-control border-form-control" id="dlTimeHour" style="width: 100%">
                                                                @foreach(App\Services\ContentServices::hourOption() as $h)
                                                                <option value="{{ $h['hour'] }}" }}>{{ $h['hour'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div style="width: 20%; float: left; padding-right: 10px;">
                                                        <div class="form-group">
                                                            <select class="select2 form-control border-form-control" id='dlTimeMin' style="width: 100%">
                                                                <option value="00">00</option>
                                                                <option value="15">15</option>
                                                                <option value="30">30</option>
                                                                <option value="45">45</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div style="width: 20%; float: left">
                                                        <div class="form-group">
                                                            <select class="select2 form-control border-form-control" id="dlTimeAMPM" style="width: 100%">
                                                                <option value="AM" {{ (strtoupper(date('A')) == 'AM') ? 'selected=selected' : '' }}>AM</option>
                                                                <option value="PM" {{ (strtoupper(date('A')) == 'PM') ? 'selected=selected' : '' }}>PM</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row" id="pickupContent" style="display: none">
                                            <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                                                <label class="display-block">* Pick-up Date & Time</label>
                                                <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                                                    <div style="width: 38%; float: left; padding-right: 10px;">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" name="pickDate" id="pickDate" >
                                                        </div>
                                                    </div>
                                                    <div style="width: 20%; float: left; padding-right: 10px;">
                                                        <div class="form-group">
                                                            <select class="select2 form-control border-form-control" id="pickTimeHour" style="width: 100%">
                                                                @foreach(App\Services\ContentServices::hourOption() as $h)
                                                                <option value="{{ $h['hour'] }}" >{{ $h['hour'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div style="width: 20%; float: left; padding-right: 10px;">
                                                        <div class="form-group">
                                                            <select class="select2 form-control border-form-control" id='pickTimeMin' style="width: 100%">
                                                                <option value="00">00</option>
                                                                <option value="15">15</option>
                                                                <option value="30">30</option>
                                                                <option value="45">45</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div style="width: 20%; float: left">
                                                        <div class="form-group">
                                                            <select class="select2 form-control border-form-control" id="pickTimeAMPM" style="width: 100%">
                                                                <option value="AM" {{ (strtoupper(date('A')) == 'AM') ? 'selected=selected' : '' }}>AM</option>
                                                                <option value="PM" {{ (strtoupper(date('A')) == 'PM') ? 'selected=selected' : '' }}>PM</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" id="transactionType" value="Delivery">
                                    <input type="hidden" id="storeCode" value="{{ $code }}" >
                                    <input type="hidden" id="isSocial" value="{{ Session::get('isSocial') }}" >
                                    <input type="hidden" id="action" value="{{ Session::get('isAction') }}" >
                                </div>
                                <div class="clearfix"></div>
                                <h5 class="heading-design-h5" style="margin-top: 5px;">Login to your account</h5>
                                <div class="alert alert-danger no-border" id="loginErrorMsg" style="display: none; text-align: center">
                                    <span id="login_error_message">Oh snap!</span>
                                </div>
                                <fieldset class="form-group">
                                    <label>Enter Email</label>
                                    <input type="text" class="form-control" value="{{ (!Session::get('userEmail')) ? '' : Session::get('userEmail') }}" id="email" name="email" placeholder="eg. johndoe@example.com">
                                    <label class="form-error-message" id="emailError">Invalid email address</label>
                                </fieldset>
                                <fieldset class="form-group">
                                    <label>Enter Password</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="********">
                                    <label class="form-error-message" id="passwordError">Password is required</label>
                                </fieldset>
                                <fieldset class="form-group">
                                    <label>Delivery Address</label>
                                    <select class="select2 form-control border-form-control" name="selectAddress" id="selectAddress">
                                        <option value=""></option>
                                    </select>
                                    <label class="form-error-message" id="selectAddressError">Please select your delivery address.</label>
                                </fieldset>
                                <div class="login-with-sites text-right">
                                    <a href="{{ url('send/mail') }}" style="font-weight: 500; padding-bottom: 10px; text-decoration: underline !important;">Forgot Password?</a>
                                </div>
                                <fieldset class="form-group">
                                    <button type="button" id="btnLogin" class="btn btn-lg btn-secondary btn-block">LOG IN</button>
                                </fieldset>
                                <!--
                                <div class="login-with-sites text-center">
                                    <p style="font-weight: 500;">or Login with your social profile:</p>
                                    <button type="button" id="btnRedirectFB" data-href="{{ (is_null(request()->segment(2))) ? url('account/redirect/facebook/login') : url('account/redirect/facebook/login/'.request()->segment(2)) }}" class="btn-facebook login-icons btn-lg btn-block"><i class="mdi mdi-facebook"></i> Facebook</button>
                                    <button type="button" id="btnRedirectGoogle" data-href="{{ (is_null(request()->segment(2))) ? url('account/redirect/google/login') : url('account/redirect/google/login/'.request()->segment(2)) }}" class="btn-google login-icons btn-lg btn-block"><i class="mdi mdi-google"></i> Google</button>
                                </div>
                                -->
                                <div class="login-footer-tab">
                                    <button type="button" class="btn btn-lg btn-block" id="btnRedirectRegister" data-href="{{ (is_null(request()->segment(2))) ? url('redirect/register') : url('redirect/register/'.request()->segment(2)) }}"style="font-weight: 500; background-color: #EEEEEE">
                                        Not a member yet? Click here to register!
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="clock" style="display:none"></div>
    <script type="text/javascript">
        var isSocial = $('#isSocial').val();

        if(isSocial.length > 0)
        {
            loginData($('#email').val(), $('#storeCode').val());
        }
    </script>
    @endsection
