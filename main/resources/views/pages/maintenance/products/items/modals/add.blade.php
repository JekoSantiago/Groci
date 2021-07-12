<form id="addItemForm">
    <div class="form-group">
        <label for="role" class="text-semibold">PRODUCT CATEGORY</label>
        <select name="category" class="select" id="category">
            <option value="">SELECT ONE</option>
            @foreach($category as $c)
            <option value="{{ $c->category_id }}" >{{ strtoupper($c->category_name) }}</option>
            @endforeach
        </select>
        <label class="error form-error-message" id="categoryError" >* Please select category.</label>
    </div>
    
    <div class="form-group">
        <label for="role" class="text-semibold">SKU</label>
        <input type="text" class="form-control" name="sku" id="sku">
        <label class="error form-error-message" id="skuError" >* Please enter SKU.</label>
    </div>
    <div class="form-group">
        <label for="role" class="text-semibold">ITEM NAME</label>
        <input type="text" class="form-control" name="itemName" id="itemName">
        <label class="error form-error-message" id="itemNameError">* Please enter item name.</label>
    </div>

    <div class="form-group">
        <label for="role" class="text-semibold display-block">EXCLUDED STORES</label>
        <select name="exStores" multiple="multiple" class="bootstrap-select" data-width="100%" id="exStores">
            @foreach($stores as $s)
            <option value="{{ $s->store_code }}" >{{ $s->store_code }} - {{ strtoupper($s->store_name) }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="role" class="text-semibold display-block">IMAGE FILE : <span style="font-size: 11px; color: red">[ Accepted formats: gif, png, jpg, jpeg ]</span></label>
        <div class="display-block">
            <input type="file" class="file-styled" name="img_file" id="img_file">
        </div>
        <label class="error form-error-message" id="imgFileError">* Banner file must be an image and of type: gif, png, jpg, jpeg.</label>
    </div>
</form>