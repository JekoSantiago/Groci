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
                                <h5 class="heading-design-h5">Register Now!</h5>
                                <p class="reg-txt-header">Please ensure that all the information is correct. The company will not be liable for incorrect information and/or non-existent recipient during time of delivery.</p>
                                <div class="alert alert-danger no-border" id="regErrorMsg" style="line-height: 18px; display: none">
                                    <div class="error-msg" id="resgister_error_message">Oh snap!</div>
                                </div>
                                <div class="alert alert-success no-border" id="regSuccessMsg" style="line-height: 18px; display: none">
                                    <div class="success-msg" id="register_success_message">Oh Well!</div>
                                </div>
                                <div class="alert alert-warning no-border" id="regWarningMsg" style="line-height: 18px; display: none">
                                    <div class="warning-msg" id="register_warning_message">Warning!</div>
                                </div>
                                <small>Fields marked with an asterisk(*) are required</small>

                                <h6 class="h6-reg-text">Customer Information</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" value="{{ (!Session::get('userEmail')) ? '' : Session::get('userEmail') }}" name="emailAddress" id="emailAddress" placeholder="* Email address">
                                            <label class="form-error-message" id="emailAddressError">Invalid email address</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" maxlength="11" name="mobile_num" id="mobile_num" placeholder="* Mobile number">
                                            <label class="form-error-message" id="mobileNumError">Mobile no. is required</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="firstName" id="firstName" placeholder="* First name" >
                                            <label class="form-error-message" id="firstNameError">First name is required</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="lastName" id="lastName" placeholder="* Last name">
                                            <label class="form-error-message" id="lastNameError">Last name is required</label>
                                        </div>
                                    </div>
                                </div>

                                <h6 class="h6-reg-text">Delivery Address</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <select class="select2 form-control border-form-control" {{ (request()->segment(2) != "") ? 'disabled=disabled' : '' }} name="province" id="province" style="width: 100%">
                                                <option value=""></option>
                                                @php
                                                foreach($province as $p) :
                                                $selected = ($p->province_id == $provinceID) ? 'selected=selected' : '';
                                                @endphp
                                                <option value="{{ $p->province_id.'-'.$p->province_name }}" {{ $selected }}>{{ $p->province_name }}</option>
                                                @php
                                                endforeach;
                                                @endphp
                                            </select>
                                            <label class="form-error-message" id="provinceError">Please select province</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <select class="select2 form-control border-form-control" {{ (request()->segment(2) != "") ? 'disabled=disabled' : '' }} name="city" id="city" style="width: 100%">
                                                <option value=""></option>
                                                @php
                                                if(request()->segment(2) != "") :
                                                foreach(App\Models\Content::getCityMunicipalOption($provinceID) as $c) :
                                                $default = ($c->municipal_id == $cityID) ? 'selected=selected' : '';
                                                @endphp
                                                <option value="{{ $c->municipal_name }}" {{ $default }}>{{ $c->municipal_name }}</option>
                                                @php
                                                endforeach;
                                                endif;
                                                @endphp
                                            </select>
                                            <label class="form-error-message" id="cityError">City is required</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="address" id="address" placeholder="* Floor/Dept/House No./Street/Barangay">
                                            <label class="form-error-message" id="addressError">Address is required</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <select class="select2 form-control border-form-control" name="addType" id="addType" style="width: 100%">
                                                <option value=""></option>
                                                <option value="Home">HOME</option>
                                                <option value="Office">OFFICE</option>
                                                <option value="Others">OTHERS</option>
                                            </select>
                                            <label class="form-error-message" id="typeError">Address type is required</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <select class="select2 form-control border-form-control" {{ (request()->segment(2) != "") ? 'disabled=disabled' : '' }} name="selectStore" id="selectStore" style="width: 100%">
                                                <option value=""></option>
                                                @php
                                                if(request()->segment(2) != "") :
                                                    foreach(App\Models\Content::getStores(NULL, 1) as $s) :
                                                    $select = ($s->store_code == base64_decode(request()->segment(2))) ? 'selected=selected' : '';
                                                @endphp
                                                <option value="{{ $s->store_code }}" {{ $select }}>{{ $s->store_name }}</option>
                                                @php
                                                endforeach;
                                                endif;
                                                @endphp
                                            </select>
                                            <label class="form-error-message" id="selectStoreError">Please select your preferred store.</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <textarea name="landmarks" id="landmarks" class="form-control" placeholder="Landmarks"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <h6 class="h6-reg-text">Password</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group" style="padding-right: 0px;">
                                            <input type="password" class="form-control" name="passwordReg" id="passwordReg" placeholder="* Password">
                                            <label class="form-error-message" id="passwordRegError">Password is required</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="password" class="form-control" name="re-pass" id="repass" placeholder="* Confirm Password">
                                            <label class="form-error-message" id="rePassError">Password did not match.</label>
                                        </div>
                                    </div>
                                </div>
                                <h6 class="h6-reg-text">Remarks</h6>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <textarea name="remarks" id="remarks" class="form-control" placeholder="Remarks"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="chkTerms" id="customCheck2">
                                    <label class="custom-control-label" style="display: block; font-size: 11px;" for="customCheck2">I Agree with <a href="#">Term and Conditions</a></label>
                                    <label class="form-error-message" id="termsError">Please check terms and condtions</label>
                                </div>
                                <div class="row" style="margin-top: 20px">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <button type="button" id="btnRegister" class="btn btn-lg btn-secondary btn-block">CREATE YOUR ACCOUNT</button>
                                        </div>
                                        <input type="hidden" id="customerID">
                                        <input type="hidden" id="addressID">
                                        <input type="hidden" id="storeCode" value="{{ $code }}" >
                                        <input type="hidden" id="isSocial" value="{{ Session::get('isSocial') }}" >
                                        <input type="hidden" id="action" value="{{ Session::get('action') }}" >
                                    </div>
                                </div><!--
                                <div class="login-with-sites text-center">
                                    <p style="font-weight: 500;">or Sign up with your social media profile:</p>
                                    <a href="{{ (is_null(request()->segment(2))) ? url('account/redirect/facebook/register') : url('account/redirect/facebook/register/'.request()->segment(2)) }}" class="btn-facebook login-icons btn-lg btn-block">
                                        <i class="mdi mdi-facebook"></i> Facebook
                                    </a>
                                    <a href="{{ (is_null(request()->segment(2))) ? url('account/redirect/google/register') : url('account/redirect/google/register/'.request()->segment(2)) }}" class="btn-google login-icons btn-lg btn-block">
                                        <i class="mdi mdi-google"></i> Google
                                    </a>
                                </div> -->
                                <div class="text-center login-footer-tab">
                                    <button data-href="{{ (is_null(request()->segment(2))) ? url('redirect/login') : url('redirect/login/'.request()->segment(2)) }}" type="button" class="btn btn-lg btn-block" id="btnRedirectLogin" style="font-weight: 500; background-color: #EEEEEE">
                                        <i class="mdi mdi-lock"></i> Log me in
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
            registerData($('#emailAddress').val(), $('#storeCode').val());
        }
    </script>
    @endsection


