<form id="addItemCostForm">
    <div class="form-group">
        <label class="text-semibold">SKU :</label>
        <input type="text" value="{{ $detail[0]->sku }}" class="form-control" readonly  />
    </div>
    <div class="form-group">
        <label class="text-semibold">ITEM NAME :</label>
        <input type="text" value="{{ $detail[0]->item_name }}" class="form-control" readonly />
    </div>
    <div class="form-group">
        <label class="text-semibold">ITEM PRICE :</label>
        <input type="text" name="itemPrice" id="itemPrice" class="form-control" />
        <label class="error" id="itemPriceError" style="margin-top: 7px; color: red; font-size: 11px; font-style: italic; display: none;">* Item price is required.</label>
    </div>
    <div class="form-group">
        <label class="text-semibold">EFFECTIVE DATE :</label>
        <input type="text" name="effectiveDate" id="effectiveDate" class="form-control" />
        <input type="hidden" name="item_ID" id="item_ID" value="{{ $detail[0]->item_id }}" />
        <label class="error" id="effectiveDateError" style="margin-top: 7px; color: red; font-size: 11px; font-style: italic; display: none;">* Effective date is required.</label>
    </div>

    <div class="form-group">
        <label class="display-block text-semibold">IS PROMO? </label>
        <label class="radio-inline">
		    <input type="radio" name="radIsPromo" value="1"> YES
		</label>

		<label class="radio-inline">
		    <input type="radio" name="radIsPromo" value="0" checked="checked"> NO
        </label>
    </div>
</form>