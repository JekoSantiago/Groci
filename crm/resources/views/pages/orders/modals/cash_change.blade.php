<form id="addCashChangeForm">
    <div class="form-group">
        <label class="text-semibold">AMOUNT CHANGE FOR :</label>
        <input type="text" name="cashChange" id="cashChange" class="form-control" />
        <label class="error" id="cashChangeError" style="margin-top: 7px; color: red; font-size: 11px; font-style: italic; display: none;">* Amount change  is required.</label>
    </div>
    
    <input type="hidden" id="orderID" value="{{ $orderID }}">
</form>