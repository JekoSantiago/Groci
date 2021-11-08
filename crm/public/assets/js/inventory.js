/* ------------------------------------------------------------------------------
*
*  # Inventory Module
*
*  Specific JS code additions for inventory page
*
*  Version: 1.0
*  Latest update: Nov. 19, 2020
*
* ---------------------------------------------------------------------------- */

$(function() {

    $.ajaxSetup({
        headers:
        { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $('body').on('click', '#btnUpdateInventory', function() {
		$(this).prop('disabled', true);
		$(this).html('System is processing your request. Do not click anywhere unless finished.');
        var scode = $('#storeCode').val();
		var form_data = new FormData();
		form_data.append('code', scode);

		$.ajax({
			url: webURL + '/inventory/update',
			type: 'POST',
			dataType: 'json',
			cache: false,
			contentType: false,
			processData: false,
			data: form_data,
			success: function (response) {
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
		});
    });

    $('body').on('click','#btnExtractItemsList',function(){
        $(window.location).attr('href', webURL + '/inventory/download');
    })

});
