<form id="editStoreForm">
    <div class="form-group">
        <label for="role" class="text-semibold">BRANCH</label>
        <select name="dcCode" class="select" id="dcCode">
            <option value="">SELECT ONE</option>
            @foreach($branch as $b)
            <option value="{{ $b->branch_code }}" {{ ($detail[0]->branch_code == $b->branch_code) ? 'selected=selected' : '' }} >{{ $b->branch_code.' - '.strtoupper($b->branch_name) }}</option>
            @endforeach
        </select>
        <label class="error form-error-message" id="dcCodeError" >* Please enter branch code.</label>
     </div>
    <div class="form-group">
        <label for="role" class="text-semibold">STORE CODE</label>
        <input type="text" class="form-control" name="storeCode" id="storeCode" value="{{ $detail[0]->store_code }}">
        <label class="error form-error-message" id="storeCodeError">* Please enter store code.</label>
    </div>

    <div class="form-group">
        <label for="role" class="text-semibold">STORE NAME</label>
        <input type="text" class="form-control" name="storeName" id="storeName" value="{{ $detail[0]->store_name }}">
        <label class="error form-error-message" id="storeNameError">* Please enter store name.</label>
    </div>

    <div class="form-group">
        <label for="role" class="text-semibold">ADDRESS</label>
        <textarea class="form-control" name="address" id="address">{{ $detail[0]->address }}</textarea>
        <label class="error form-error-message" id="addressError">* Please enter store address.</label>
    </div>

    <div class="form-group">
        <label for="role" class="text-semibold">PROVINCE</label>
        <input type="text" class="form-control" name="province" id="province" value="{{ $detail[0]->province }}">
        <label class="error form-error-message" id="provinceError">* Please enter province.</label>
    </div>

    <div class="form-group">
        <label for="role" class="text-semibold">LATITUDE</label>
        <input type="text" class="form-control" name="latitude" id="latitude" value="{{ $detail[0]->latitude }}">
        <label class="error form-error-message" id="latitudeError">* Please enter latitude.</label>
    </div>

    <div class="form-group">
        <label for="role" class="text-semibold">LONGITUDE</label>
        <input type="text" class="form-control" name="longitude" id="longitude" value="{{ $detail[0]->longitude }}">
        <label class="error form-error-message" id="longitudeError">* Please enter longitude.</label>
    </div>
    <input type="hidden" id="storeID" value="{{ $detail[0]->store_id }}">
</form>