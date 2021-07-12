<form id="addMinChargeForm">
    <div class="form-group">
        <label class="text-semibold">MINIMUM CHARGE AMOUNT :</label>
        <input type="text" name="minAmt" id="minAmt" class="form-control" />
        <label class="error" id="minAmtError" style="margin-top: 7px; color: red; font-size: 11px; font-style: italic; display: none;">* Minimum charge amount is required.</label>
    </div>
    <div class="form-group">
        <label class="text-semibold">EFFECTIVE DATE :</label>
        <input type="text" name="mineffDate" id="mineffDate" class="form-control" />
        <label class="error" id="mineffDateError" style="margin-top: 7px; color: red; font-size: 11px; font-style: italic; display: none;">* Effective date is required.</label>
    </div>
    <div class="form-group">
        <label for="role" class="text-semibold display-block">EXCLUDED STORES</label>
        <select name="exStores" multiple="multiple" class="bootstrap-select" data-width="100%" id="exStores">
            @foreach($stores as $s)
            <option value="{{ $s->store_code }}" >{{ $s->store_code }} - {{ strtoupper($s->store_name) }}</option>
            @endforeach
        </select>
    </div>

</form>