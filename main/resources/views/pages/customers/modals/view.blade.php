<form id="addItemForm">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group" id="firstNameInput">
                <label for="firstName" class="text-semibold">FIRSTNAME :</label>
                <input type="text" class="form-control" placeholder="* First name" name="firstName" id="firstName" value="{{ $detail[0]->firstname }}" readonly>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group" id="lastNameInput">
                <label for="lastName" class="text-semibold">LASTNAME :</label>
                <input type="text" class="form-control" value="{{ $detail[0]->lastname }}" readonly>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group" id="emailInput">
                <label for="email" class="text-semibold">EMAIL ADDRESS :</label>
                <input type="text" class="form-control" value="{{ $detail[0]->email_address }}" readonly>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group" id="mobileInput">
                <label for="mobile" class="text-semibold">MOBILE NO. :</label>
                <input type="text" class="form-control" value="{{ $detail[0]->contact_num }}" readonly>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group" id="selectAddType">
                <label for="atype" class="text-semibold">ADDRESS TYPE :</label>
                <input type="text" class="form-control" value="{{ $detail[0]->type }}" readonly>
            </div>
        </div>
        <div class="col-md-8">
            <div class="form-group" id="addressInput">
                <label for="address" class="text-semibold">ADDRESS :</label>
                <input type="text" class="form-control" value="{{ $detail[0]->address }}" readonly>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group" id="cityInput">
                <label for="city" class="text-semibold">CITY :</label>
                <input type="text" class="form-control" value="{{ $detail[0]->city }}" readonly>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group" id="selectProvince">
                <label for="provicne" class="text-semibold">PROVINCE :</label>
                <input type="text" class="form-control" value="{{ $detail[0]->province_name }}" readonly>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <textarea class="form-control" cols="30" rows="2" readonly>{{ $detail[0]->landmarks }}</textarea>
            </div>
        </div>
    </div>
    <input type="hidden" id="customerID" value="{{ $detail[0]->customer_id }}">
    <input type="hidden" id="addressID" value="{{ $detail[0]->address_id }}">
</form>