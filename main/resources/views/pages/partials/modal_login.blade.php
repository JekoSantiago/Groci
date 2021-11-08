    <div class="modal fade login-modal-main" id="trans-type-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="login-modal">
                        <form>
                            <div class="text-center login-footer-tab">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="delivery-m" style="cursor: pointer">
                                            <img src="{{ asset('img/delivery.fw.png') }}" />
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="pickup-m" style="cursor: pointer">
                                            <img src="{{ asset('img/pickup.fw.png') }}" />
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="login-footer-tab">
                                <div class="col-md-12" style="padding-top: 20px;">
                                    <div id="deliveryContent-m">
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="display-block">* Select type of delivery</label>
                                                <div class="radio-holder">
                                                    <input type="radio" id="test8" value="Delivery Now" name="radioDeliver" disabled>
                                                    <label for="test8">Delivery Now</label>
                                                </div>
                                                <div class="radio-holder">
                                                    <input type="radio" id="test9" value="Delivery Later" name="radioDeliver" checked>
                                                    <label for="test9">Delivery</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <label style="display: block; width: 100%">* Delivery Date & Time</label>
                                            <p class="reg-txt-header" id="deliveryNow-m">{{ (strtotime(date('HH:mm')) < strtotime('18:00') ) ? date('Y-m-d', strtotime('+1 day')) :date('Y-m-d') }} Philippine local date & time) expect delivery 2.5 - 3hours upon order confirmation. <br>
<b>Note:</b> "Orders received after 5:30 pm will be delivered the following day" </p>
                                            <div class="col-md-12" id="deliveryLater-m" style="display: none; padding-left: 0px; padding-right: 0px;">
                                                <div style="width: 50%; float: left; padding-right: 10px;">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="deliverLaterDate-m">
                                                    </div>
                                                </div>
                                                <div style="width: 50%; float: left; padding-right: 10px;">
                                                    <input type="text" name="dlTimeHour-m" id="dlTimeHour-m" class="form-control" value="{{ date('hh:mm A'), strtotime('+2 hours') }}" />
                                                    <label class="form-error-message" style="width: 100%; text-align: right;" id="timeForError">Time should be two hours from now and not after 6PM</label>

                                                    {{-- <div class="form-group">
                                                        <select class="select2 form-control border-form-control" id="dlTimeHour-m" style="width: 100%">
                                                            @foreach(App\Services\ContentServices::hourOption() as $h)
                                                            <option value="{{ $h['hour'] }}">{{ $h['hour'] }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div> --}}
                                                </div>
                                                {{-- <div style="width: 20%; float: left; padding-right: 10px;">
                                                    <div class="form-group">
                                                        <select class="select2 form-control border-form-control" id='dlTimeMin-m' style="width: 100%">
                                                            <option value="00">00</option>
                                                            <option value="15">15</option>
                                                            <option value="30">30</option>
                                                            <option value="45">45</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div style="width: 20%; float: left;">
                                                    <div class="form-group">
                                                        <select class="select2 form-control border-form-control" id="dlTimeAMPM-m" style="width: 100%">
                                                            <option value="AM" {{ (strtoupper(date('A')) == 'AM') ? 'selected=selected' : '' }}>AM</option>
                                                            <option value="PM" {{ (strtoupper(date('A')) == 'PM') ? 'selected=selected' : '' }}>PM</option>
                                                        </select>
                                                    </div>
                                                </div> --}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" id="pickupContent-m" style="display: none">
                                        <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                                            <label class="display-block">* Pick-up Date & Time</label>
                                            <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                                                <div style="width: 50%; float: left; padding-right: 10px;">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="pickDate-m" >
                                                    </div>
                                                </div>
                                                <div style="width: 50%; float: left; padding-right: 10px;">
                                                    <div class="form-group">
                                                        <input type="text" name="pickTimeHour-m" id="pickTimeHour-m" class="form-control" value="{{ date('hh:mm A'), strtotime('+2 hours') }}" />
                                                        <label class="form-error-message" style="width: 100%; text-align: right;" id="timeForError">Time should be two hours from now and not after 6PM</label>

                                                        {{-- <select class="select2 form-control border-form-control" id="pickTimeHour-m" style="width: 100%">
                                                        @foreach(App\Services\ContentServices::hourOption() as $h)
                                                            <option value="{{ $h['hour'] }}">{{ $h['hour'] }}</option>
                                                        @endforeach
                                                        </select> --}}
                                                    </div>
                                                </div>
                                                {{-- <div style="width: 20%; float: left; padding-right: 10px;">
                                                    <div class="form-group">
                                                        <select class="select2 form-control border-form-control" id='pickTimeMin-m' style="width: 100%">
                                                            <option value="00">00</option>
                                                            <option value="15">15</option>
                                                            <option value="30">30</option>
                                                            <option value="45">45</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div style="width: 20%; float: left;">
                                                    <div class="form-group">
                                                        <select class="select2 form-control border-form-control" id="pickTimeAMPM-m" style="width: 100%">
                                                            <option value="AM" {{ (strtoupper(date('A')) == 'AM') ? 'selected=selected' : '' }}>AM</option>
                                                            <option value="PM" {{ (strtoupper(date('A')) == 'PM') ? 'selected=selected' : '' }}>PM</option>
                                                        </select>
                                                    </div>
                                                </div> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" id="transactionType-m" value="Delivery">
                                <input type="hidden" id="itemVal" value="">
                                <input type="hidden" id="itemID" value="">
                                <input type="hidden" id="Qty" value="">
                                <input type="hidden" id="params" value="{{ request()->segment(2) }}">
                                <input type="hidden" id="searched" value="{{ request()->segment(3) }}">
                            </div>
                            <div class="clearfix"></div>
                            <fieldset class="form-group">
                                <label>Delivery Address</label>
                                <select class="select2 form-control border-form-control" name="deliveryAddress" id="deliveryAddress" style="width: 100%">
                                    <option value=""></option>
                                    @foreach(App\Models\Account::getCustomerAddress(NULL, Session::get('email')) as $i)
                                    <option value="{{ $i->address_id }}" {{ (Session::get('addressID') == $i->address_id) ? 'selected=selected' : '' }}>{{ $i->store_name.' - '.$i->address.' '.$i->city.' '.$i->province_name }}</option>
                                    @endforeach
                                </select>
                                <label class="form-error-message" id="deliveryAddressError">Please select your delivery address.</label>
                            </fieldset>

                            <div class="login-footer-tab">
                                <button type="button" type="button" id="btnSubmit" class="btn btn-primary btn-block btn-lg">SUBMIT</button>
                            </div>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
