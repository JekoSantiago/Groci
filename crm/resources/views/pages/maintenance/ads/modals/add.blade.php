<form id="addBannerForm">
    <div class="form-group">
        <label for="role" class="text-semibold">BANNER NAME</label>
        <input type="text" class="form-control" name="bannerName" id="bannerName">
        <label class="error form-error-message" id="bannerNameError" >* Please enter banner name.</label>
     </div>
    <div class="form-group">
        <label for="role" class="text-semibold">PAGE LOCATION</label>
        <input type="text" class="form-control" name="pageLocation" id="pageLocation">
        <label class="error form-error-message" id="pageLocationError">* Please enter what page location.</label>
    </div>

    <div class="form-group">
        <label for="role" class="text-semibold display-block">BANNER FILE : <span style="font-size: 11px; color: red">[ Accepted formats: gif, png, jpg, jpeg ]</span></label>
        <div class="display-block">
            <input type="file" class="file-styled" name="img_file" id="img_file">
        </div>
        <label class="error form-error-message" id="imgFileError">* Banner file must be an image and of type: gif, png, jpg, jpeg.</label>
    </div>
</form>