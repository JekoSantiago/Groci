<form id="addReceiptForm">
    <div class="form-group">
        <label class="text-semibold">ENTER RECEIPT :</label>
        <input type="text" name="receiptNum" id="receiptNum" class="form-control" />
        <label class="error" id="receiptNumError" style="margin-top: 7px; color: red; font-size: 11px; font-style: italic; display: none;">* Receipt number is required.</label>
    </div>
    
    <input type="hidden" id="orderID" value="{{ $orderID }}">
</form>