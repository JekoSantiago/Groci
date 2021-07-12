<form id="addRemarksForm">
    <div class="form-group">
        <label class="text-semibold">ENTER REMARKS :</label>
        <textarea name="cancelRemarks" id="cancelRemarks" class="form-control"></textarea>
        <label class="error" id="cancelRemarksError" style="margin-top: 7px; color: red; font-size: 11px; font-style: italic; display: none;">* Remarks is required.</label>
    </div>
    
    <input type="hidden" id="orderID" value="{{ $orderID }}">
</form>