
@extends('layout.base_tpl')
    @section('contents')  
    <!-- Start profile content -->
    @include('pages.partials.headers')

    <section class="account-page section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="row no-gutters">
                        <div class="col-md-4">
                            <div class="card account-left">
                                <div class="user-profile-header">
                                    <img alt="logo" src="{{ asset('img/no-image-icon.fw.png') }}">
                                    <h5 class="mb-1 text-secondary"><strong>Hi, </strong> {{ $details[0]->firstname .' '. $details[0]->lastname }}</h5>
                                    <p>{{ $details[0]->contact_num }}</p>
                                </div>
                                <div class="list-group">
                                    <a href="{{ url('account/profile') }}" class="list-group-item list-group-item-action active"><i aria-hidden="true" class="mdi mdi-account-outline"></i> My Profile</a>
                                    <a href="{{ url('account/address') }}" class="list-group-item list-group-item-action"><i aria-hidden="true" class="mdi mdi-map-marker-circle"></i> My Address</a>
                                    <a href="{{ url('account/orders') }}" class="list-group-item list-group-item-action"><i aria-hidden="true" class="mdi mdi-format-list-bulleted"></i> Order History</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card card-body account-right">
                                <div class="widget">
                                    <div class="section-header">
                                        <h5 class="heading-design-h5">My Profile</h5>
                                    </div>
                                    <div class="alert alert-danger no-border" id="regErrorMsg" style="line-height: 18px; display: none">
                                        <div class="error-msg" id="update_profile_error_message">Oh snap!</div>
                                    </div>
                                    <div class="alert alert-success no-border" id="regSuccessMsg" style="line-height: 18px; display: none">
                                        <div class="success-msg" id="update_profile_success_message">Oh Well!</div>
                                    </div>
                                    <div id="profileForm">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="control-label">First Name <span class="required">*</span></label>
                                                    <input class="form-control border-form-control" value="{{ $details[0]->firstname }}" id="firstname" type="text">
                                                    <label class="form-error-message" id="firstnameError">First name is required</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="control-label">Last Name <span class="required">*</span></label>
                                                    <input class="form-control border-form-control" value="{{ $details[0]->lastname }}" id="lastname" type="text">
                                                    <label class="form-error-message" id="lastnameError">Last name is required</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="control-label">Mobile Number <span class="required">*</span></label>
                                                    <input class="form-control border-form-control" value="{{ $details[0]->contact_num }}" id="mobile_num" type="text">
                                                    <label class="form-error-message" id="mobileError">Mobile number is required</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="control-label">Email Address</label>
                                                    <input class="form-control border-form-control " value="{{ $details[0]->email_address }}" disabled="" type="email">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-12">
                                            <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="checkChangePass">
                                                    <label class="custom-control-label" style="display: block; font-size: 12px;" for="checkChangePass">Check this box to change password!</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="control-label">New Password <span class="required">*</span></label>
                                                    <input class="form-control border-form-control " id="newPassword" disabled="" type="password">
                                                    <label class="form-error-message" id="newPassError">New password is required</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="control-label">Confirm New Password <span class="required">*</span></label>
                                                    <input class="form-control border-form-control " id="reNewPassword" disabled="" type="password">
                                                    <label class="form-error-message" id="reNewPassError">Password did not match.</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 text-right" style="margin-top: 20px;">
                                                <input type="hidden" id="curPassword" value="{{ $details[0]->password }}">
                                                <input type="hidden" id="isChangePass" value="">
                                                <input type="hidden" id="customerID" value="{{ base64_encode($details[0]->customer_id) }}">
                                                <button type="button" class="btn btn-secondary btn-lg" id="btnProfileCancel"> Cancel </button>
                                                <button type="button" class="btn btn-primary btn-lg" id="btnProfileUpdate"> Save Changes </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('pages.partials.footers')
    <!-- End profile content -->
    @endsection


