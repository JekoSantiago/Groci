<form id="addReceiptForm">
    <div class="form-group">
        <label class="text-semibold">CONFIRMATION CODE :</label>
        <input type="text" name="cCode" id="cCode" class="form-control" />
        <label class="error" id="cCodeError" style="margin-top: 7px; color: red; font-size: 11px; font-style: italic; display: none;">* Confirmation code is required.</label>
    </div>
    
    <input type="hidden" id="customerID" value="{{ $customerID }}">
    <input type="hidden" id="addressID" value="{{ $addressID }}">
</form>