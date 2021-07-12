<form id="addReceiptForm">
    <div class="form-group">
        <label class="text-semibold">REMARKS :</label>
        <textarea name="rejectRemarks" id="rejectRemarks" class="form-control"></textarea>
        <label class="error" id="rejectRemarksError" style="margin-top: 7px; color: red; font-size: 11px; font-style: italic; display: none;">* Remarks is required.</label>
    </div>
    
    <input type="hidden" id="customerID" value="{{ $customerID }}">
    <input type="hidden" id="addressID" value="{{ $addressID }}">
    <input type="hidden" id="action" value="{{ $action }}">
</form>