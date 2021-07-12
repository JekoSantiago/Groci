/* ------------------------------------------------------------------------------
*
*  # Customer Module
*
*  Specific JS code additions for customer page
*
*  Version: 1.0
*  Latest update: Oct. 16, 2020
*
* ---------------------------------------------------------------------------- */

$(function() {
    $.ajaxSetup({
        headers:
        { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    /* ---- View details Form Modal  --------- */
    $('#modal_validation_form').on('show.bs.modal', function(e) {
        var customerID = $(e.relatedTarget).data('cid');
        var addressID  = $(e.relatedTarget).data('aid');
        var remoteLink = webURL + '/customer/validate/'+customerID+'/'+addressID;
        $(this).find('.modal-body').load(remoteLink, function() {});
    });

    $('body').on('click', '#btnValidate', function(e) {
        e.preventDefault();
        var error = false;
        var customerID = $('#customerID').val();
        var addressID  = $('#addressID').val();
        var cCode = $('#cCode').val();

        if(cCode.length == 0)
        {
            var error = true;
            $('#cCodeError').show();
        }

        if(error == false)
        {
            $('#btnCloseValidationForm').attr('disabled', 'disabled');
            $('#btnValidate').attr('disabled', 'disabled');
            $('#btnValidate').html('<i class="icon-spinner4"></i> Processing');
            var form_data = new FormData();
            form_data.append('cID', customerID);
            form_data.append('aID', addressID);
            form_data.append('code', cCode);

            $.ajax({
                url: webURL + '/validate/code',
                type: 'POST',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                success: function (response) {
                    if(response.status == 'ok')
                    {
                        $('#modal_validation_form').modal('hide');
                        $('#modal_view_customer').modal('toggle');
                        var remoteLink = webURL + '/customer/view/details/'+customerID+'/'+addressID;
                        $('#modal_view_customer').find('.modal-body').load(remoteLink, function() {
                            
                            $('.select').select2({
                                minimumResultsForSearch: Infinity
                            });
                        });
                    }
                    else
                    {
                        $('#cCodeError').html('* Invalid confirmation code');
                        $('#cCodeError').show();
                        $('#btnCloseValidationForm').removeAttr('disabled');
                        $('#btnValidate').removeAttr('disabled');
                        $('#btnValidate').html('VALIDATE');
                    }
                }
            });
        }
    });

    $('body').on('keyup', '#cCode', function(){
        $('#cCodeError').hide();
    });

    
    $('body').on('click', '#btnConfirm', function(e){
        e.preventDefault()
        var customerID = $('#customerID').val();
        var addressID  = $('#addressID').val();
        var action = 'confirm';
        var remarks = '';

        updateConfirmationStatus(customerID, addressID, action, remarks);
    });

    $('body').on('click', '#btnCancel', function(e){
        e.preventDefault();
        var customerID = $('#customerID').val();
        var addressID  = $('#addressID').val();
        var action = 'cancel';

        $('#modal_view_customer').modal('hide');
        $('#modal_reject_remarks_form').modal('toggle');
        var remoteLink = webURL + '/customer/remarks/'+customerID+'/'+addressID+'/'+action;
        $('#modal_reject_remarks_form').find('.modal-body').load(remoteLink, function() {});
        
    });

    $('body').on('click', '#btnSubmitRemarks', function(e){
        e.preventDefault();
        var error = false;
        var remarks = $('#rejectRemarks').val();
        var customerID = $('#customerID').val();
        var addressID  = $('#addressID').val();
        var action = $('#action').val();

        if(remarks.length == 0)
        {
            var error = true;
            $('#rejectRemarksError').show();
        }

        if(error == false)
        {
            updateConfirmationStatus(customerID, addressID, action, remarks);
        }
    });

    $('body').on('keyup', '#rejectRemarks', function(){
        $('#rejectRemarksError').hide();
    });

    /* ---- Edit Form Modal  --------- */
    $('#modal_view_address').on('show.bs.modal', function(e) {
        var customerID = $(e.relatedTarget).data('cid');
        var addressID  = $(e.relatedTarget).data('aid');
        var remoteLink = webURL + '/customer/view/address/'+customerID+'/'+addressID;
        $(this).find('.modal-body').load(remoteLink, function() {});
    });
});


function updateConfirmationStatus(customerID, addressID, action, remarks)
{
    var form_data = new FormData();
    form_data.append('customerID', customerID);
    form_data.append('addressID', addressID);
    form_data.append('action', action);
    form_data.append('remarks', remarks);

    $('#btnCancel').attr('disabled', 'disabled');
    $('#btnConfirm').attr('disabled', 'disabled');
    if(action == 'cancel')
    {
        $('#btnCancel').html('<i class="icon-spinner4"></i> Processing');
    }
    else 
    {
        $('#btnConfirm').html('<i class="icon-spinner4"></i> Processing');
    }
            
    $.ajax({
        url: webURL + '/customer/confirm/status',
        type: 'POST',
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        success: function (response) {
         	if(response.status == 'success')
        	{
                swal({
                	title: "Success!",
                    text: response.message,
                    confirmButtonColor: "#EF5350",
                    type: "success"
                },
                function(isConfirm){
                	if (isConfirm) {
                    	$(window.location).attr('href', webURL + '/customer');
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
                    	$('#btnCancel').removeAttr('disabled');
                        $('#btnConfirm').removeAttr('disabled');
                        if(action == 'cancel')
                        {
                            $('#btnCancel').html('CANCEL');
                        }
                        else 
                        {
                            $('#btnConfirm').html('CONFIRM');
                        }
                     }
                });
            }
        }
    });
}

function deleteCustomer(id)
{
    var form_data = new FormData();
    form_data.append('id', id);

    $.ajax({
        url: webURL + '/customer/delete',
        type: 'POST',
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        success: function (response) {
         	if(response.status == 'success')
        	{
                swal({
                	title: "Success!",
                    text: response.message,
                    confirmButtonColor: "#EF5350",
                    type: "success"
                },
                function(isConfirm){
                	if (isConfirm) {
                    	$(window.location).attr('href', webURL + '/customer/all');
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
                });
            }
        }
    });
}
