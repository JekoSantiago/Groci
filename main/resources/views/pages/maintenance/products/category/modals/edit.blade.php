<form id="editBannerForm">
    <div class="form-group">
        <label for="role" class="text-semibold">CATEGORY NAME</label>
        <input type="text" class="form-control" name="categoryName" id="categoryName" value="{{ $detail[0]->category_name }}">
        <label class="error form-error-message" id="categoryNameError" >* Please enter category name.</label>
    </div>
    
    <div class="form-group">
        <label for="role" class="text-semibold display-block">ICON FILE : <span style="font-size: 11px; color: red">[ Accepted formats: gif, png, jpg, jpeg ]</span></label>        
        <div class="media no-margin-top display-block">
		    <div class="media-left">
                @php 
			        $imgFile = ($detail[0]->icons == NULL) ? 'no-image-available.png' : $detail[0]->icons;
			    @endphp
			    <img src="{{ url('storage/products/small/'.$imgFile) }}" style="border-radius: 2px;" alt="">
			</div>
			<div class="media-body">
                <input type="file" class="file-styled" name="img_file" id="img_file">
			</div>
		</div>
        <label class="error form-error-message" id="imgFileError">* Banner file must be an image and of type: gif, png, jpg, jpeg.</label>
    </div>

    <input type="hidden" id="catID" value="{{ $detail[0]->category_id }}">
    <input type="hidden" id="curFile" value="{{ $detail[0]->icons }}">
</form>