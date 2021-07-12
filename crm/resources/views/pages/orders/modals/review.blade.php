<div class="col-md-8" style="padding-right: 40px;">
    <div class="row">
        <h6 class="text-semibold col-md-12">Order Summary - ({{ count($data['items']) }} items)</h6>
    </div>    
    <div class="row">
        <div class="table-responsive pre-scrollable" style="max-height: 740px;">
            <table class="table">
                <tbody>
                @foreach($data['items'] as $i)
                    <tr>
                        <td>{{ $i['item_name'] }}</td>
                        <td class="col-md-1">{{ $i['item_price'] }}</td>
                        <td class="col-md-1">{{ $i['qty'] }}</td>
                        <td class="col-md-1">{{ $i['total_amount'] }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="panel-body bg-teal" style="padding: 12px 20px;">
            <div class="col-md-12 text-right text-semibold">Delivery Charge : PhP {{ number_format($data['charges'], 2) }}</div>
            <div class="col-md-12 text-right text-semibold">Amount Due : PhP {{ number_format($data['amount'], 2) }}</div>
            <div class="col-md-12 text-right text-semibold">TOTAL AMOUNT : PhP {{ number_format($data['amountDue'], 2) }}</div>
        </div>
    </div>
</div>

<div class="col-md-4">
    <form id="addItemForm">
        <div class="row">
            <p class="text-semibold">Please ensure that all the information is correct to avoid incorrect information and/or non-existent recipient during time of delivery.</p>
            <small class="help-block text-semibold" style="color: red; font-style: italic; font-size: 11px;">Fields with * (asterisk) are required</small>
        </div>
        <div class="row">
            <h6 class="text-semibold" style="margin-top: 0px;">Transaction Type</h6>
        </div> 

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="col-md-6" style="padding-left: 0px">
                        <button type="button" class="btn btn-raised btn-block btn-default btnDelivery" id="btnTransType" data-value="delivery" style="font-weight: 600;"> Worry free Delivery</button>
                    </div>
                    
                    <div class="col-md-6" style="padding-right: 0px">
                        <button type="button" class="btn btn-raised btn-block bg-grey btnPickUp" id="btnTransType" data-value="pick-up" style="font-weight: 600"> Worry free Pickup</button>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>

            <div id="deliveryTrans">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="radio-inline">
                            <input type="radio" name="deliveryType" value="Delivery Now" checked="checked">
                            Delivery Now
                        </label>

                        <label class="radio-inline">
                            <input type="radio" name="deliveryType" value="Delivery Later">
                            Delivery Later
                        </label>
                    </div>
                </div>
                <div class="col-md-12">
                    <label class="display-block text-semibold">* Delivery Date & Time</label>
                    <p id="delNowDate">
                        Today ({{ date('F j, Y') }} Philippine local date & time) expected delivery between 1pm and 3pm upon confirmation of orders. 
                    </p>
                    <div id="delLaterDate" style="display: none">
                        <div class="form-group">
                            <div class="col-md-6" style="padding-left: 0px;">
                                <input type="text" class="form-control" id="dLaterDate">
                            </div>
                            <div class="col-md-2">
                                <select class="select" id="dLaterHour">
                                    @for($x=1; $x<=12; $x++)
                                    <option value="{{ $x }}">{{ $x }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="select" id="dLaterMin">
                                    <option value="00">00</option>
                                    <option value="15">15</option>
                                    <option value="30">30</option>
                                    <option value="45">45</option>
                                </select>
                            </div>
                            <div class="col-md-2" style="padding-right: 0px;">
                                <select class="select" id="dLaterAMPM">
                                    <option value="AM">AM</option>
                                    <option value="PM">PM</option>
                                </select>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

            <div id="pickUpTrans" style="display: none">
                <label class="display-block text-semibold">* Pick-up Date & Time</label>
                <div class="form-group">
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="pickDate">
                    </div>
                    <div class="col-md-2">
                        <select class="select" id="pickHour">
                            @for($x=1; $x<=12; $x++)
                            <option value="{{ $x }}">{{ $x }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="select" id="pickMin">
                            <option value="00">00</option>
                            <option value="15">15</option>
                            <option value="30">30</option>
                            <option value="45">45</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="select" id="pickAMPM">
                            <option value="AM">AM</option>
                            <option value="PM">PM</option>
                        </select>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>

            <input type="hidden" id="transactionType" value="delivery">
        </div>
        
        <div class="row">
            <h6 class="text-semibold" style="margin-top: 0px;">Customer Details</h6>
        </div>    
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <select name="storeCustomer" class="selectCustomer" data-placeholder="Select customer..." id="storeCustomer">
                        <option value="">Choose One</option>
                        @foreach($customers as $row)
                        <option value="{{ $row->customer_id.'@@'.$row->address_id }}">{{ $row->firstname.' '.$row->lastname.' | '. $row->address.', '. $row->city.', '.$row->province_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group" id="firstNameInput">
                    <input type="text" class="form-control" placeholder="* First name" name="firstName" id="firstName">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group" id="lastNameInput">
                    <input type="text" class="form-control" placeholder="* Last name" name="lastName" id="lastName">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group" id="emailInput">
                    <input type="text" class="form-control" placeholder="* Email address" name="emailAdd" id="emailAdd">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group" id="mobileInput">
                    <input type="text" class="form-control" placeholder="* Mobile number" maxlength="11" minlegth="11" name="mobileNum" id="mobileNum">
                </div>
            </div>
        </div>
        <div class="row">
            <h6 class="text-semibold" style="margin-top: 0px;">Billing Address</h6>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group" id="selectAddType">
                    <select name="addressType" class="select" data-placeholder="* Address Type" id="addressType">
                        <option value=""></option>
                        <option value="Home">HOME</option>
                        <option value="Office">OFFICE</option>
                        <option value="Others">OTHERS</option>
                    </select>
                </div>
            </div>
            <div class="col-md-8">
                <div class="form-group" id="addressInput">
                    <input type="text" class="form-control" placeholder="* Floor / Dept / House No. / Street / Barangay" name="address" id="address">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group" id="cityInput">
                    <input type="text" class="form-control" placeholder="* City" name="city" id="city">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group" id="selectProvince">
                    <select name="province" class="select" data-placeholder="* Select a province" id="province">
                        <option value=""></option>
                        @foreach($province as $p)
                        <option value="{{ $p->province_id }}">{{ $p->province_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <textarea name="landmark" id="landmark" class="form-control" placeholder="Landmark" cols="30" rows="2"></textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <textarea name="orderRemarks" id="orderRemarks" class="form-control" placeholder="Order Remarks" cols="30" rows="2"></textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group" id="changeForInput">
                    <input type="text" class="form-control" placeholder="Change for" name="change_for" id="change_for">
                    <label for="change_for" id="changeError" style="margin-top: 5px; color: red; font-style: italic;"></label>
                </div>
            </div>
        </div>
        <input type="hidden" id="customerID" value="">
        <input type="hidden" id="addressID" value="">
        <input type="hidden" id="totAmount" value="{{ $data['amountDue'] }}">
        <input type="hidden" id="orderID" value="{{ $orderID }}">
    </form>
</div>
<div class="clearfix"></div>


