<form id="editBannerForm">
    <div class="form-group">
        <label for="role" class="text-semibold">BANNER NAME</label>
        <input type="text" class="form-control" name="bannerName" id="bannerName" value="{{ $detail[0]->ad_name }}">
        <label class="error form-error-message" id="bannerNameError" >* Please enter banner name.</label>
     </div>
    <div class="form-group">
        <label for="role" class="text-semibold">PAGE LOCATION</label>
        <input type="text" class="form-control" name="pageLocation" id="pageLocation" value="{{ $detail[0]->location }}">
        <label class="error form-error-message" id="pageLocationError">* Please enter what page location.</label>
    </div>

    <div class="form-group">
        <label for="role" class="text-semibold display-block">BANNER FILE : <span style="font-size: 11px; color: red">[ Accepted formats: gif, png, jpg, jpeg ]</span></label>        
        <div class="media no-margin-top display-block">
		    <div class="media-left">
                @php 
			        $imgFile = ($detail[0]->img_file == NULL) ? 'placeholder.jpg' : $detail[0]->img_file;
			    @endphp
			    <img src="{{ url('storage/ad/'.$imgFile) }}" style="border-radius: 2px;" alt="">
			</div>
			<div class="media-body">
                <input type="file" class="file-styled" name="img_file" id="img_file">
			</div>
		</div>
        <label class="error form-error-message" id="imgFileError">* Banner file must be an image and of type: gif, png, jpg, jpeg.</label>
    </div>

    <input type="hidden" id="adID" value="{{ $detail[0]->ad_id }}">
    <input type="hidden" id="curFile" value="{{ $detail[0]->img_file }}">
</form>