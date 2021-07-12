@extends('layout.base_tpl')
    @section('contents') 
    <nav class="navbar navbar-light navbar-expand-lg bg-faded" style="background-color: transparent;">
        <div class="container">
            <a class="navbar-brand mr-auto mt-5 mt-lg-2 margin-auto top-categories-search-main" href="{{ url('/') }}"><img src="{{ asset('img/logo.png')}}" alt="logo"></a>
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
                                <h5 class="heading-design-h5" style="margin-top: 5px;">Change Password</h5>
                                <div class="alert alert-danger no-border" id="loginErrorMsg" style="display: none; text-align: center">
                                    <span id="login_error_message">Oh snap!</span>
                                </div>
                                <fieldset class="form-group">
                                    <label class="control-label">New Password</label>
                                    <input class="form-control border-form-control " id="newPasswordOne" type="password">
                                    <label class="form-error-message" id="newPasswordOneError">New password is required</label>
                                </fieldset>

                                <fieldset class="form-group">
                                    <label class="control-label">Confirm New Password</label>
                                    <input class="form-control border-form-control" id="reNewPasswordOne" type="password">
                                    <label class="form-error-message" id="newPasswordOneError">Password did not match.</label>
                                    <input type="hidden" id="customerEmail" value="{{ $email }}">
                                    <input type="hidden" id="customerID" value="{{ $custID }}">
                                </fieldset>
                                
                                <fieldset class="form-group">
                                    <button type="button" id="btnChangePassword" class="btn btn-lg btn-secondary btn-block">CHANGE PASSWORD</button>
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