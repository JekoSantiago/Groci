<form id="editDelChargeForm">
    <div class="form-group">
        <label class="text-semibold">DELIVERY CHARGE AMOUNT :</label>
        <input type="text" name="delAmt" id="delAmt" class="form-control" value="{{ ($details[0]->dc_amount == '.00') ? '0.00' : $details[0]->dc_amount }}" />
        <label class="error" id="delAmtError" style="margin-top: 7px; color: red; font-size: 11px; font-style: italic; display: none;">* Delivery charge amount is required.</label>
    </div>
    <div class="form-group">
        <label class="text-semibold">EFFECTIVE DATE :</label>
        <input type="text" name="deleffDate" id="deleffDate" class="form-control" value="{{ $details[0]->edate_from }}" />
        <label class="error" id="deleffDateError" style="margin-top: 7px; color: red; font-size: 11px; font-style: italic; display: none;">* Effective date is required.</label>
    </div>

    <select name="exStores" multiple="multiple" class="bootstrap-select" data-width="100%" id="exStores">
        @php 
        $code = explode(',', trim($details[0]->store_code));
        foreach($stores as $s) :
        @endphp
        <option value="{{ $s->store_code }}" {{ (in_array($s->store_code, $code)) ? 'selected=selected' : '' }} >{{ $s->store_code }} - {{ strtoupper($s->store_name) }}</option>
        @php
        endforeach;
        @endphp
    </select>

    <input type="hidden" id="dcID" value="{{ $details[0]->dc_id }}">
</form>