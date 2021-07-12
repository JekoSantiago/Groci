<form id="addItemForm">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group" id="firstNameInput">
                <input type="text" class="form-control" placeholder="* First name" name="firstName" id="firstName" value="{{ $detail[0]->firstname }}">
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group" id="lastNameInput">
                <input type="text" class="form-control" placeholder="* Last name" name="lastName" id="lastName" value="{{ $detail[0]->lastname }}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group" id="emailInput">
                <input type="text" class="form-control" placeholder="* Email address" name="emailAdd" id="emailAdd" value="{{ $detail[0]->email_address }}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group" id="mobileInput">
                <input type="text" class="form-control" placeholder="* Mobile number" maxlength="11" minlegth="11" name="mobileNum" id="mobileNum" value="{{ $detail[0]->contact_num }}">
            </div>
        </div>
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
    <input type="hidden" id="customerID" value="">
    <input type="hidden" id="addressID" value="">
</form>