<form id="uploadItems" enctype="multipart/form-data">
	<div class="form-group">
		<label for="role" class="text-semibold display-block">UPLOAD FILE : <span style="font-size: 11px; color: red">[ Accepted formats: xlsx ]</span></label>
		<div class="display-block">
			<input type="file" class="file-styled" name="docs" id="docs">
		</div>
		<label class="error form-error-message" id="docFileError">* Please select file to upload.</label>
	</div>
</form>

<script type="text/javascript">
$(document).ready(function() {

	$.ajaxSetup({
		headers:
		{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
	});

	$('body').on('click', '#btnUploadPrice', function(e) {
		e.preventDefault();
		var error = false;
		var docs = $('#docs').prop('files')[0];
		var docsFile  = $('#docs').val();

		if(docsFile.length == 0)
		{
			var error = true;
			$('#docFileError').show();
		}
		else
		{
			if (!docs.type.match('application.*')) {
				var error = true;
				$('#docFileError').html('* Documents file must be of type: xlsx.');
				$('#docFileError').show();
			} 
			else
			{
				var fileExtension = ['xlsx'];
				var ext = $('#docs').val().split('.').pop().toLowerCase();

				if($.inArray(ext, fileExtension) == -1)
				{
					var error = true;
					$('#docFileError').html('* Invalid document file extension. Allowed type: xlsx.');
					$('#docFileError').show();
				}
			}
		}

		if(error == false)
		{
			$('#btnUploadPrice').attr('disabled', 'disabled');
			var form_data = new FormData();
			form_data.append('doc_file', docs);
			
			$.ajax({
				url: webURL + '/inventory/edit',
				type: 'POST',
				dataType: 'json',
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,
				success: function (response) {
					if(response.status == 'ok')
					{
						swal({
							title: "Success!",
							text: response.message,
							confirmButtonColor: "#EF5350",
							type: "success"
						},
						function(isConfirm){
							if (isConfirm) {
								$(window.location).attr('href', webURL + '/inventory');
							}
						});
					}
					else
					{
						swal({
							title: "Error!",
							text: response.message,
							confirmButtonColor: "#EF5350",
							type: "error"
						},
						function(isConfirm){
							if (isConfirm) {
								$('#btnUploadPrice').removeAttr('disabled');
							}
						});
					}
				}
			});
		}

	});
});

</script>
