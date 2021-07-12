<form id="addItemForm">
    <div class="form-group">
        <label for="role" class="text-semibold">PRODUCT CATEGORY</label>
        <select name="category" class="select" id="category">
            <option value="">SELECT ONE</option>
            @foreach($category as $c)
            <option value="{{ $c->category_id }}" {{ ($detail[0]->category_id == $c->category_id) ? 'selected=selected' : '' }} >{{ strtoupper($c->category_name) }}</option>
            @endforeach
        </select>
        <label class="error form-error-message" id="categoryError" >* Please select category.</label>
    </div>
    
    <div class="form-group">
        <label for="role" class="text-semibold">SKU</label>
        <input type="text" class="form-control" name="sku" id="sku" value="{{ $detail[0]->sku }}">
        <label class="error form-error-message" id="skuError" >* Please enter SKU.</label>
    </div>
    <div class="form-group">
        <label for="role" class="text-semibold">ITEM NAME</label>
        <input type="text" class="form-control" name="itemName" id="itemName" value="{{ $detail[0]->item_name }}">
        <label class="error form-error-message" id="itemNameError">* Please enter item name.</label>
    </div>

    <div class="form-group">
        <label for="role" class="text-semibold display-block">EXCLUDED STORES</label>
        <select name="exStores" multiple="multiple" class="bootstrap-select" data-width="100%" id="exStores">
            @php 
            $code = explode(',', trim($detail[0]->store_code));
            foreach($stores as $s) :
            @endphp
            <option value="{{ $s->store_code }}" {{ (in_array($s->store_code, $code)) ? 'selected=selected' : '' }} >{{ $s->store_code }} - {{ strtoupper($s->store_name) }}</option>
            @php
            endforeach;
            @endphp
        </select>
    </div>

    <div class="form-group">
        <label for="role" class="text-semibold display-block">IMAGE FILE : <span style="font-size: 11px; color: red">[ Accepted formats: gif, png, jpg, jpeg ]</span></label>
        <div class="media no-margin-top display-block">
		    <div class="media-left">
                @php 
			        $imgFile = ($detail[0]->img_pic == NULL) ? 'no-image-available.png' : $detail[0]->img_pic;
			    @endphp
			    <img src="{{ url('storage/products/item/'.$imgFile) }}" style="border-radius: 2px;" alt="">
			</div>
			<div class="media-body">
                <input type="file" class="file-styled" name="img_file" id="img_file">
			</div>
		</div>
        <label class="error form-error-message" id="imgFileError">* Banner file must be an image and of type: gif, png, jpg, jpeg.</label>
    </div>
    <input type="hidden" id="itemID" value="{{ $detail[0]->item_id }}">
    <input type="hidden" id="curFile" value="{{ $detail[0]->img_pic }}">
</form>