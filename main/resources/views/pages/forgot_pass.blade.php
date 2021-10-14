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
                <div class="col-lg-5 mx-auto" style="border: none; background: none; padding-left: 20px">
                    <a href="{{ url('/') }}" style="font-weight: 500; font-size: 14px; color: #111"><i class="mdi mdi-arrow-left-bold-circle"></i> Back to home page</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Start profile content -->
    <section class="account-page section-padding" style="padding: 10px 0px 40px">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 mx-auto">
                    <div class="row no-gutters">
                        <div class="col-md-12">
                            <div class="card card-body">
                                <h5 class="heading-design-h5" style="margin-top: 5px;">What you want us to do?</h5>
                                <p style="font-weight: 400; font-size: 12px;">We'll send you instructions in your email.</p>
                                <div class="alert alert-danger no-border" id="forgotPassErrorMsg" style="display: none; text-align: center">
                                    <span id="forgot_error_message">Oh snap!</span>
                                </div>
                                <div class="alert alert-success no-border" id="forgotPassSuccessMsg" style="line-height: 18px; display: none">
                                    <div id="forgot_success_message">Oh Well!</div>
                                </div>
                                <fieldset class="form-group">
                                    <div class="radio-holder">
                                        <input type="radio" id="test2" value="password" name="radio-group" checked>
                                        <label for="test2">Reset Password</label>
                                    </div>
                                    <!--
                                    <div class="radio-holder">
                                        <input type="radio" id="test1" value="code" name="radio-group" >
                                        <label for="test1">Resend Code</label>
                                    </div> -->
                                </fieldset>

                                <fieldset class="form-group">
                                    <input type="text" class="form-control" id="emailChangePass" name="emailChangePass" placeholder="Your email address">
                                    <label class="form-error-message" id="emailChangePassError">Invalid email address</label>
                                </fieldset>

                                <fieldset class="form-group">
                                    <button type="button" id="btnSendMail" class="btn btn-lg btn-secondary btn-block">SEND</button>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="clock" style="display:none"></div>
    @endsection
