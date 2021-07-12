<form id="editBannerForm">
    <div class="form-group">
        <label for="role" class="text-semibold">SLIDER NAME</label>
        <input type="text" class="form-control" name="sliderName" id="sliderName" value="{{ $detail[0]->slider_name }}">
        <label class="error form-error-message" id="sliderNameError" >* Please enter slider name.</label>
    </div>

    <div class="form-group">
        <label for="role" class="text-semibold display-block">SLIDER FILE : <span style="font-size: 11px; color: red">[ Accepted formats: gif, png, jpg, jpeg ]</span></label>        
        <div class="media no-margin-top display-block">
		    <div class="media-left">
                @php 
			        $imgFile = ($detail[0]->img_file == NULL) ? 'cover.jpg' : $detail[0]->img_file;
			    @endphp
			    <img src="{{ url('storage/slider/'.$imgFile) }}" style="border-radius: 2px;" alt="">
			</div>
			<div class="media-body">
                <input type="file" class="file-styled" name="img_file" id="img_file">
			</div>
		</div>
        <label class="error form-error-message" id="imgFileError">* Banner file must be an image and of type: gif, png, jpg, jpeg.</label>
    </div>

    <input type="hidden" id="sliderID" value="{{ $detail[0]->slider_id }}">
    <input type="hidden" id="curFile" value="{{ $detail[0]->img_file }}">
</form>