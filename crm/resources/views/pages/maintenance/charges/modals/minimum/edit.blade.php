<form id="editMinChargeForm">
    <div class="form-group">
        <label class="text-semibold">MINIMUM CHARGE AMOUNT :</label>
        <input type="text" name="minAmt" id="minAmt" class="form-control" value="{{ $details[0]->amount }}" />
        <label class="error" id="minAmtError" style="margin-top: 7px; color: red; font-size: 11px; font-style: italic; display: none;">* Minimum charge amount is required.</label>
    </div>
    <div class="form-group">
        <label class="text-semibold">EFFECTIVE DATE :</label>
        <input type="text" name="mineffDate" id="mineffDate" class="form-control" value="{{ $details[0]->date_from }}" />
        <label class="error" id="mineffDateError" style="margin-top: 7px; color: red; font-size: 11px; font-style: italic; display: none;">* Effective date is required.</label>
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

    <input type="hidden" id="minChargeID" value="{{ $details[0]->id }}">
</form>