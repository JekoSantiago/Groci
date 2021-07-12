<form id="addDelChargeForm">
    <div class="form-group">
        <label class="text-semibold">DELIVERY CHARGE AMOUNT :</label>
        <input type="text" name="delAmt" id="delAmt" class="form-control" />
        <label class="error" id="delAmtError" style="margin-top: 7px; color: red; font-size: 11px; font-style: italic; display: none;">* Delivery charge amount is required.</label>
    </div>
    <div class="form-group">
        <label class="text-semibold">EFFECTIVE DATE :</label>
        <input type="text" name="deleffDate" id="deleffDate" class="form-control" />
        <label class="error" id="deleffDateError" style="margin-top: 7px; color: red; font-size: 11px; font-style: italic; display: none;">* Effective date is required.</label>
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