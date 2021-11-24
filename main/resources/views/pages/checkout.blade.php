@extends('layout.base_tpl')
    @section('contents')
    <!-- Checkout content -->
    @include('pages.partials.headers')

    <section class="checkout-page section-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="checkout-step">
                        <div class="accordion" >
                            <div class="card checkout-step-two">
                                <div id="collapseTwo" class="collapse show">
                                    <div class="card-body">
                                        <div class="alert alert-danger no-border" id="regErrorMsg" style="display: none">
                                            <div class="error-msg" id="checkoutError">Oh snap!</div>
                                        </div>
                                        <form>
                                            <div class="heading-part">
                                                <h5 class="sub-heading">Transaction Type</h5>
                                            </div>
                                            <hr>
                                            <div class="text-center login-footer-tab">
                                                <ul class="nav nav-tabs" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link {{ ($serviceType == 'Delivery') ? 'active' : '' }} @if (in_array($details['store_code'],config('app.nodel_stores'))) disabled @endif" id="delivery" style="cursor: pointer" >
                                                            <img src="{{ asset('img/delivery.fw.png') }}" />
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link {{ ($serviceType == 'Pick-up') ? 'active' : '' }}" id="pickup" style="cursor: pointer">
                                                            <img src="{{ asset('img/pickup.fw.png') }}" />
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-md-12" style="margin-top: 10px">
                                                <div id="deliveryContent" {{ ($serviceType == 'Delivery') ? '' : 'style=display:none' }}>
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label class="display-block">* Select type of delivery</label>
                                                            <div class="radio-holder">
                                                                <input type="radio" id="test1" value="Delivery Now" name="radio-group" {{ ((Session::get('transType') == 'Delivery Now') || (Session::get('transType') == 'Pick-up')) ? 'checked=checked' : '' }}>
                                                                <label for="test1">Delivery Now</label>
                                                            </div>
                                                            <div class="radio-holder">
                                                                <input type="radio" id="test2" value="Delivery Later" name="radio-group" {{ (Session::get('transType') == 'Delivery Later') ? 'checked=checked' : '' }}>
                                                                <label for="test2">Delivery Later</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <label style="{{ (Session::get('transType') == 'Delivery Now') ? 'display: block;' : 'display:none;' }} width: 100%">* Delivery Date & Time</label>
                                                        <p class="reg-txt-header" id="deliveryNow">{{ (strtotime(date('H:i')) > strtotime('18:00') ) ? 'Tomorrow (' . date('F j, Y', strtotime('+1 day')) : 'Today (' . date('F j, Y') }} Philippine local date & time) expect delivery 2.5 - 3hours upon order confirmation. <br>
                                                            <b>Note:</b> "Orders received after 5:30 pm will be delivered the following day" </p>
                                                        <div class="col-md-12" id="deliveryLater" style="{{ (Session::get('transType') == 'Delivery Later') ? 'display: block;' : 'display:none;' }} padding-left: 0px; padding-right: 0px;">
                                                            <div style="width: 50%; float: left; padding-right: 10px;">
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control daterangepick" name="deliverLaterDate" id="deliverLaterDate" value="{{ (strtotime(date('H:i')) < strtotime('18:00')) ? $items['sdate'] : date('Y-m-d' , strtotime('+1 day'))}}">
                                                                </div>
                                                            </div>
                                                            <div style="width: 50%; float: left; padding-right: 10px;">
                                                                <div class="form-group">
                                                                    <input type="text" name="dlTimeHour" id="dlTimeHour" class="form-control" value="{{ (strtotime(date('H:i')) < strtotime('18:00') ) ? $items['shour'] + 2 . $items['ampm'] : date('H:i A')}}" />
                                                                    {{-- <select class="select2 form-control border-form-control" id="dlTimeHour" style="width: 100%">
                                                                        @foreach(App\Services\ContentServices::hourOption() as $h)
                                                                        <option value="{{ $h['hour'] }}" {{ ($h['hour'] == $items['shour']) ? 'selected=selected' : '' }}>{{ $h['hour'] }}</option>
                                                                        @endforeach
                                                                    </select> --}}
                                                                    <label class="form-error-message" style="width: 100%; text-align: right;" id="timeForError">Time should be two hours from now and not after 8PM</label>
                                                                </div>
                                                            </div>
                                                            {{-- <div style="width: 20%; float: left; padding-right: 10px;">
                                                                <div class="form-group">
                                                                    <select class="select2 form-control border-form-control" id='dlTimeMin' style="width: 100%">
                                                                        <option value="00" {{ ($items['smin'] == '00') ? 'selected=selected' : '' }}>00</option>
                                                                        <option value="15" {{ ($items['smin'] == '15') ? 'selected=selected' : '' }}>15</option>
                                                                        <option value="30" {{ ($items['smin'] == '30') ? 'selected=selected' : '' }}>30</option>
                                                                        <option value="45" {{ ($items['smin'] == '45') ? 'selected=selected' : '' }}>45</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div style="width: 20%; float: left">
                                                                <div class="form-group">
                                                                    <select class="select2 form-control border-form-control" id="dlTimeAMPM" style="width: 100%">
                                                                        <option value="AM" {{ ($items['ampm'] == 'AM') ? 'selected=selected' : '' }}>AM</option>
                                                                        <option value="PM" {{ ($items['ampm'] == 'PM') ? 'selected=selected' : '' }}>PM</option>
                                                                    </select>
                                                                </div>
                                                            </div> --}}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row" id="pickupContent" {{ ($serviceType == 'Pick-up') ? '' : 'style=display:none' }}>
                                                    <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                                                        <label class="display-block">* Pick-up Date & Time</label>
                                                        <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                                                            <div style="width: 50%; float: left; padding-right: 10px;">
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control daterangepick" name="pickDate" id="pickDate" value="{{ (strtotime(date('H:i')) < strtotime('18:00') ) ?  $items['sdate'] : date('Y-m-d', strtotime('+1 day'))}}">
                                                                </div>
                                                            </div>
                                                            <div style="width: 50%; float: left; padding-right: 10px;">
                                                                <div class="form-group">
                                                                    <input type="text" name="pickTimeHour" id="pickTimeHour" class="form-control" value="{{ (strtotime(date('H:i')) < strtotime('18:00') ) ? $items['shour'] + 2 . $items['ampm'] : $items['shour'] }}" />
                                                                    {{-- <select class="select2 form-control border-form-control" id="pickTimeHour" style="width: 100%">
                                                                        @foreach(App\Services\ContentServices::hourOption() as $h)
                                                                        <option value="{{ $h['hour'] }}" {{ ($h['hour'] == $items['shour']) ? 'selected=selected' : '' }}>{{ $h['hour'] }}</option>
                                                                        @endforeach
                                                                    </select> --}}
                                                                    <label class="form-error-message" style="width: 100%; text-align: right;" id="ptimeForError">Time should be two hours from now and not after 8PM</label>
                                                                </div>
                                                            </div>
                                                            {{-- <div style="width: 20%; float: left; padding-right: 10px;">
                                                                <div class="form-group">
                                                                    <select class="select2 form-control border-form-control" id='pickTimeMin' style="width: 100%">
                                                                        <option value="00" {{ ($items['smin'] == '00') ? 'selected=selected' : '' }}>00</option>
                                                                        <option value="15" {{ ($items['smin'] == '15') ? 'selected=selected' : '' }}>15</option>
                                                                        <option value="30" {{ ($items['smin'] == '30') ? 'selected=selected' : '' }}>30</option>
                                                                        <option value="45" {{ ($items['smin'] == '45') ? 'selected=selected' : '' }}>45</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div style="width: 20%; float: left">
                                                                <div class="form-group">
                                                                    <select class="select2 form-control border-form-control" id="pickTimeAMPM" style="width: 100%">
                                                                        <option value="AM" {{ ($items['ampm'] == 'AM') ? 'selected=selected' : '' }}>AM</option>
                                                                        <option value="PM" {{ ($items['ampm'] == 'PM') ? 'selected=selected' : '' }}>PM</option>
                                                                    </select>
                                                                </div>
                                                            </div> --}}
                                                        </div>
                                                    </div>
                                                </div>

                                                <input type="hidden" id="transactionType" value="{{ $serviceType }}">
                                            </div>

                                            <div class="heading-part">
                                                <h5 class="sub-heading">Customer Details</h5>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="control-label">First Name</label>
                                                        <input class="form-control border-form-control" id="firstName" value="{{ $details['firstname'] }}" disabled type="text">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Last Name</label>
                                                        <input class="form-control border-form-control" id="lastName" value="{{ $details['lastname'] }}" disabled type="text">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Email Address</label>
                                                        <input class="form-control border-form-control" id="emailAdd" value="{{ $details['email'] }}" disabled type="email">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Mobile Number</label>
                                                        <input class="form-control border-form-control" id="mobileNum" value="{{ $details['mobile_no'] }}" disabled type="text">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="heading-part" style="margin-top: 15px;">
                                                <h5 class="sub-heading">Delivery Address</h5>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label class="control-label"><span class="required">*</span> Address Type</label>
                                                        <select class="select2 form-control border-form-control" id="addType" disabled >
                                                            <option value="Home" {{ ($details['type'] == 'Home') ? 'selected=selected' : '' }}>HOME</option>
                                                            <option value="Office" {{ ($details['type'] == 'Office') ? 'selected=selected' : '' }}>OFFICE</option>
                                                            <option value="Others" {{ ($details['type'] == 'Others') ? 'selected=selected' : '' }}>OTHERS</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-9">
                                                    <div class="form-group">
                                                        <label class="control-label"><span class="required">*</span> Floor/Dept/House No./Street/Barangay</label>
                                                        <input class="form-control border-form-control" id="address" value="{{ $details['address'] }}" disabled  type="text">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="control-label"><span class="required">*</span> City</label>
                                                        <select class="select2 form-control border-form-control" id="city" disabled >
                                                            <option value=""></option>
                                                            @foreach($cityOption as $c)
                                                            <option value="{{ $c->municipal_name }}" {{ ($c->municipal_id == $cityID) ? 'selected=selected' : '' }}>{{ $c->municipal_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="control-label"><span class="required">*</span> Province</label>
                                                        <select class="select2 form-control border-form-control" id="province" disabled>
                                                            <option value="">SELECT PROVINCE</option>
                                                            @foreach(App\Models\Content::getProvince() as $p)
                                                                <option value="{{ $p->province_id }}" {{ ($details['province_id'] == $p->province_id) ? 'selected=selected' : '' }}>{{ $p->province_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Landmark</label>
                                                        <textarea class="form-control border-form-control" {{ (is_null($details['landmarks'])) ? '' : 'disabled=disabled' }} id="landmark">{{ $details['landmarks'] }}</textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="heading-part" style="margin-top: 15px;">
                                                <h5 class="sub-heading">Others</h5>
                                            </div>
                                            <hr>

                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label class="control-label">SMAC Number</label>
                                                        <input class="form-control border-form-control" id="SMAC"  name="SMAC" type="text" maxlength="16">
                                                    </div>
                                                    <label class="form-error-message" style="width: 100%; text-align: right;" id="smacForError">Invalid SMAC format</label>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Order Remarks</label>
                                                        <textarea class="form-control border-form-control" name="orderRemarks" id="orderRemarks"></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" name="chkTermsConditions" id="customCheck3">
                                                        <label class="custom-control-label" style="display: block; font-size: 11px;" for="customCheck3">I Agree with the <a href="#" class="text-underline">Term and Conditions</a></label>
                                                        <label class="form-error-message" id="chkTermsConditionsError">Please check terms and condtions</label>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="heading-part" style="margin-top: 15px;">
                                                <h5 class="sub-heading">Payment Method</h5>
                                            </div>
                                            <hr>

                                            <div class="row">
                                                <div class="col-lg-9 col-md-9" style="margin: 0 auto;">
                                                    <div class="form-group">
                                                        <div class="radio-holder">
                                                            <input type="radio" id="test3" value="Cash on Delivery" name="radio-group-payment" checked>
                                                            <label for="test3">CASH ON DELIVERY</label>
                                                        </div>
                                                        <div class="radio-holder">
                                                            <input type="radio" id="test4" value="Payment Online" name="radio-group-payment" disabled="disabled">
                                                            <label for="test4" style="color: grey">CREDIT CARD</label>
                                                        </div>
                                                        <div class="radio-holder">
                                                            <input type="radio" id="test5" value="G-Cash" name="radio-group-payment" disabled="disabled">
                                                            <label for="test5" style="color: grey">G-CASH</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="COD">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <h6 class="sub-heading float-right">Amount Due : Php <span id='amtText'> {{ number_format($result['amount'] + (($serviceType == 'Pick-up') ? 0 : Session::get('deliveryCharge')), 2) }}</span></h6>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-12" style="margin-top: 9px">
                                                        <h6 class="sub-heading" style="width: 100%; text-align: right;">Change For : </h6>
                                                        <fieldset style="width: 100%; display: block;">
                                                            <input class="form-control border-form-control float-right" style="width: 35%;" type="text" id="amtChange">
                                                        </fieldset>
                                                        <label class="form-error-message" style="width: 100%; text-align: right;" id="changeForError">Enter amount to change for.</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--
                                            <div class="col-lg-8 col-md-8 mx-auto"">
                                                <div class="form-group">
                                                    <label class="control-label">Card Number</label>
                                                    <input class="form-control border-form-control" value="" placeholder="0000 0000 0000 0000" type="text">
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label class="control-label">Month</label>
                                                            <input class="form-control border-form-control" value="" placeholder="01" type="text">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label class="control-label">Year</label>
                                                            <input class="form-control border-form-control" value="" placeholder="15" type="text">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3"></div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <label class="control-label">CVV</label>
                                                            <input class="form-control border-form-control" value="" placeholder="135" type="text">
                                                        </div>
                                                    </div>
                                                    </div>
                                            </div> -->
                                            <div class="clearfix"></div>

                                            <div class="float-right" style="margin: 20px 0px 10px">
                                                <button type="button" type="button" id="processLoaders" class="btn mb-2 btn-lg" style="cursor: default; display: none;"><i class="mdi mdi-spin mdi-loading mdi-16px"></i>&nbsp;</i>&nbsp;Please wait as system is processing your request</button>
                                                <button type="button" type="button" id="btnCancelOrders" class="btn btn-secondary mb-2 btn-lg">CANCEL</button>
                                                <button type="button" type="button" id="btnSubmitOrder" class="btn btn-primary mb-2 btn-lg">SAVE</button>
                                                <button type="button" type="button" id="btnBackHome" class="btn mb-2 btn-lg" style="background-color: #EEEEEE">ADD MORE PRODUCTS</button>
                                                <input type="hidden" id="addressID" value="{{ $details['address_id'] }}">
                                                <input type="hidden" id="storeCode" value="{{ $details['store_code'] }}">
                                                <input type="hidden" id="amtDue" value="{{ $result['amount'] }}">
                                                <input type="hidden" id="delCharge" value=" {{ (Session::get('deliveryCharge')) ? : 0 }}">
                                                <input type="hidden" id="orderID" value="{{ $orderID }}">
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @include('pages.checkout_cart')
            </div>
        </div>
    </section>

    @include('pages.partials.footers')
    <!-- End checkout content -->
    @endsection






