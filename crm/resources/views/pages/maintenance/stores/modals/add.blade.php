<form id="addStoreForm">
    <div class="form-group">
        <label for="role" class="text-semibold">BRANCH</label>
        <select name="dcCode" class="select" id="dcCode">
            <option value="">SELECT ONE</option>
            @foreach($branch as $b)
            <option value="{{ $b->branch_code }}" >{{ $b->branch_code.' - '.strtoupper($b->branch_name) }}</option>
            @endforeach
        </select>
        <label class="error form-error-message" id="dcCodeError" >* Please select branch.</label>
     </div>
    <div class="form-group">
        <label for="role" class="text-semibold">STORE CODE</label>
        <input type="text" class="form-control" name="storeCode" id="storeCode">
        <label class="error form-error-message" id="storeCodeError">* Please enter store code.</label>
    </div>

    <div class="form-group">
        <label for="role" class="text-semibold">STORE NAME</label>
        <input type="text" class="form-control" name="storeName" id="storeName">
        <label class="error form-error-message" id="storeNameError">* Please enter store name.</label>
    </div>

    <div class="form-group">
        <label for="role" class="text-semibold">ADDRESS</label>
        <textarea class="form-control" name="address" id="address"></textarea>
        <label class="error form-error-message" id="addressError">* Please enter store address.</label>
    </div>

    <div class="form-group">
        <label for="role" class="text-semibold">PROVINCE</label>
        <input type="text" class="form-control" name="province" id="province">
        <label class="error form-error-message" id="provinceError">* Please enter province.</label>
    </div>

    <div class="form-group">
        <label for="role" class="text-semibold">LATITUDE</label>
        <input type="text" class="form-control" name="latitude" id="latitude">
        <label class="error form-error-message" id="latitudeError">* Please enter latitude.</label>
    </div>

    <div class="form-group">
        <label for="role" class="text-semibold">LONGITUDE</label>
        <input type="text" class="form-control" name="longitude" id="longitude">
        <label class="error form-error-message" id="longitudeError">* Please enter longitude.</label>
    </div>
</form>