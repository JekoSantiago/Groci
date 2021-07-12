<form id="addSliderForm">
    <div class="form-group">
        <label for="role" class="text-semibold">SLIDER NAME</label>
        <input type="text" class="form-control" name="sliderName" id="sliderName">
        <label class="error form-error-message" id="sliderNameError" >* Please enter slider name.</label>
     </div>

    <div class="form-group">
        <label for="role" class="text-semibold display-block">SLIDER FILE : <span style="font-size: 11px; color: red">[ Accepted formats: gif, png, jpg, jpeg ]</span></label>
        <div class="display-block">
            <input type="file" class="file-styled" name="img_file" id="img_file">
        </div>
        <label class="error form-error-message" id="imgFileError">* Slider file must be an image and of type: gif, png, jpg, jpeg.</label>
    </div>
</form>