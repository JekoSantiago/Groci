
@extends('layout.base_tpl')
    @section('contents')
    <!-- Start Address content -->
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
                                    <a href="{{ url('account/profile') }}" class="list-group-item list-group-item-action">
                                        <i aria-hidden="true" class="mdi mdi-account-outline"></i> My Profile
                                    </a>
                                    <a href="{{ url('account/address') }}" class="list-group-item list-group-item-action active">
                                        <i aria-hidden="true" class="mdi mdi-map-marker-circle"></i> My Address
                                    </a>
                                    <a href="{{ url('account/orders') }}" class="list-group-item list-group-item-action">
                                        <i aria-hidden="true" class="mdi mdi-format-list-bulleted"></i> Order History
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card card-body account-right">
                                <div class="widget">
                                    <div class="section-header">
                                        <h5 class="heading-design-h5">
                                            My Address
                                        </h5>
                                    </div>
                                    <div class="row" style="margin-bottom: 20px" id="addrlist">
                                        {{-- @foreach($address as $row)
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="control-label" style="width: 100%">{{ $row->type }} Address </label>
                                                <textarea class="form-control border-form-control" style="text-align: left">Address : {{ $row->address.' '.$row->city.' '.$row->province_name }}&#13;&#10;Landmark : {{ $row->landmarks }}</textarea>
                                            </div>
                                        </div>
                                        @endforeach --}}
                                    </div>

                                    <div class="section-header">
                                        <h5 class="heading-design-h5">
                                            Add new address
                                        </h5>
                                    </div>

                                    <div class="alert alert-danger no-border" id="addAddressErrorMsg" style="line-height: 18px; display: none">
                                        <div class="error-msg" id="error_message">Oh snap!</div>
                                    </div>

                                    <div class="alert alert-success no-border" id="addAddressSuccessMsg" style="line-height: 18px; display: none">
                                        <div class="success-msg" id="success_message">Oh Well!</div>
                                    </div>

                                    <div class="alert alert-warning no-border" id="addAddressWarningMsg" style="line-height: 18px; display: none">
                                        <div class="warning-msg" id="warning_message">Warning!</div>
                                    </div>

                                    <form id="addAddressForm">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <select class="select2 form-control border-form-control" name="addressType" id="addressType">
                                                        <option value=""></option>
                                                        <option value="Home">HOME</option>
                                                        <option value="Office">OFFICE</option>
                                                        <option value="Others">OTHERS</option>
                                                    </select>
                                                    <label class="form-error-message" id="addressTypeError">Address type is required</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="address" id="address" placeholder="* Floor/Dept/House No./Street/Barangay">
                                                    <label class="form-error-message" id="addressError">Address is required</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <select class="select2 form-control border-form-control" name="selProvince" id="selProvince">
                                                        {!! App\Services\AccountServices::provinceOption() !!}
                                                    </select>
                                                    <label class="form-error-message" id="provinceError">Please select province</label>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <select class="select2 form-control border-form-control" name="city" id="city">
                                                    </select>
                                                    <label class="form-error-message" id="cityError">City is required</label>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <select class="select2 form-control border-form-control" name="myStore" id="myStore">
                                                    <option value=""></option>
                                                </select>
                                                <label class="form-error-message" id="selectStoreError">Please select your preferred store.</label>
                                            </div>
                                        </div>
                                    </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label class="control-label">Landmark</label>
                                                    <textarea class="form-control border-form-control" id="landmarks"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 text-right" style="margin-top: 20px;">
                                                <input type="hidden" id="customerID" value="{{ Session::get('CustomerID') }}">
                                                <input type="hidden" id="customerEmail" value="{{ Session::get('email') }}">
                                                <button type="button" class="btn btn-secondary btn-lg" id="btnProfileCancel"> Cancel </button>
                                                <button type="button" id="btnSaveAddress" class="btn btn-primary btn-lg"> Save </button>
                                            </div>
                                        </div>
                                    </form>
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

