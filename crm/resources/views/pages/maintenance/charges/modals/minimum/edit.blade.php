<form id="editMinChargeForm">
    <div class="form-group">
        <label class="text-semibold">STORE :</label>
        <input type="text" name="minAmtStore" id="minAmtStore" class="form-control" value="{{ $details[0]->store_code. ' - ' . $details[0]->store_name }}"  readonly/>
    </div>
    <div class="form-group">
        <label class="text-semibold">MINIMUM CHARGE AMOUNT :</label>
        <input type="text" name="minAmt" id="minAmt" class="form-control" value="{{ $details[0]->MinimumCharge }}" />
        <label class="error" id="minAmtError" style="margin-top: 7px; color: red; font-size: 11px; font-style: italic; display: none;">* Minimum charge amount is required.</label>
    </div>

    <input type="hidden" id="minChargeID" value="{{ $details[0]->store_id}}">
</form>

