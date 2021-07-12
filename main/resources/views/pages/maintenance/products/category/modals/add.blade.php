<form id="addCategoryForm">
    <div class="form-group">
        <label for="role" class="text-semibold">CATEGORY NAME</label>
        <input type="text" class="form-control" name="categoryName" id="categoryName">
        <label class="error form-error-message" id="categoryNameError" >* Please enter category name.</label>
    </div>
    
    <div class="form-group">
        <label for="role" class="text-semibold display-block">ICON FILE : <span style="font-size: 11px; color: red">[ Accepted formats: gif, png, jpg, jpeg ]</span></label>
        <div class="display-block">
            <input type="file" class="file-styled" name="img_file" id="img_file">
        </div>
        <label class="error form-error-message" id="imgFileError">* Banner file must be an image and of type: gif, png, jpg, jpeg.</label>
    </div>
</form>