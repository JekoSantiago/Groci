/* ------------------------------------------------------------------------------
*
*  # CMS Module
*
*  Specific JS code additions for cms page
*
*  Version: 1.0
*  Latest update: Oct. 5, 2020
*
* ---------------------------------------------------------------------------- */

$(function() {
    // Table setup
    // ------------------------------

    // Setting datatable defaults
    $.extend( $.fn.dataTable.defaults, {
        autoWidth: false,
        columnDefs: [{
            orderable: false,
        }],
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        },
        drawCallback: function () {
            $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').addClass('dropup');
        },
        preDrawCallback: function() {
            $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').removeClass('dropup');
        }
    });


    // Basic datatable
    $('.datatable-basic').DataTable({
        "ordering": false
    });

    $('.datatable-stores').DataTable({
        "ordering": true,
        scrollX: true,
    });

    // Basic initialization
    $('.daterange-basic').daterangepicker({
        applyClass: 'bg-slate-600',
        cancelClass: 'btn-default',
    });

    $('.bootstrap-select').selectpicker();

    $('.select-store').select2();

    $.ajaxSetup({
        headers:
        { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    /* ---- Add Form Modal  --------- */
    $('#modal_add_items').on('show.bs.modal', function() {
        var remoteLink = webURL + '/cms/product/items/add';
        $(this).find('.modal-body').load(remoteLink, function() {
            // Init Select2 when loaded
            $('.select').select2({
                minimumResultsForSearch: Infinity
            });

            // Styled file input
            $(".file-styled").uniform({
                fileButtonClass: 'action btn bg-primary'
            });

            $('.bootstrap-select').selectpicker();
        });
    });

    $('body').on('click', '#btnSaveItems', function(e) {
        e.preventDefault();
    	var error = false;
        var category = $('#category').val();
        var sku      = $('#sku').val();
        var itemName = $('#itemName').val();
        var iconFile = $('#img_file').prop('files')[0];
        var imgFile  = $('#img_file').val();
        var exStores = $('#exStores').val();

    	if(category.length == 0)
    	{
    		var error = true;
    		$('#categoryError').show();
        }

        if(sku.length == 0)
    	{
    		var error = true;
    		$('#skuError').show();
        }
        else
        {
            if(isNaN(sku))
            {
                var error = true;
                $('#skuError').html('SKU must be a number.');
    		    $('#skuError').show();
            }
        }

        if(itemName.length == 0)
    	{
    		var error = true;
    		$('#itemNameError').show();
        }

        if(imgFile.length != 0)
        {
            if (!iconFile.type.match('image.*')) {
                var error = true;
                $('#imgFileError').show();
            }
            else
            {
                var fileExtension = ['gif', 'png', 'jpg', 'jpeg'];
                var ext = $('#img_file').val().split('.').pop().toLowerCase();

                if($.inArray(ext, fileExtension) == -1)
                {
                    var error = true;
                    $('#imgFileError').html('* Invalid image file extension. Allowed type: gif, png, jpg, jpeg.');
                    $('#imgFileError').show();
                }
            }
        }

    	if(error == false)
    	{
    		$('#btnSaveItems').attr('disabled', 'disabled');
            var form_data = new FormData();
            form_data.append('category', category);
            form_data.append('sku', sku);
            form_data.append('itemName', itemName);
            form_data.append('img_file', iconFile);
            form_data.append('codes', exStores);

            $.ajax({
                url: webURL + '/cms/product/items/save',
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
                                $(window.location).attr('href', webURL + '/cms/product/items');
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
                                $('#btnSaveItems').removeAttr('disabled');
                            }
                        });
                    }
                }
            });
    	}
    });

    /* ---- Edit Form Modal  --------- */
    $('#modal_edit_items').on('show.bs.modal', function(e) {
    	var itemID  = $(e.relatedTarget).data('itemid');
        var remoteLink = webURL + '/cms/product/items/edit/'+itemID;
        $(this).find('.modal-body').load(remoteLink, function() {
            // Init Select2 when loaded
            $('.select').select2({
                minimumResultsForSearch: Infinity
            });

            // Styled file input
            $(".file-styled").uniform({
                fileButtonClass: 'action btn bg-primary'
            });

            $('.bootstrap-select').selectpicker();
        });
    });

    $('body').on('click', '#btnEditItems', function(e) {
        e.preventDefault();
    	var error = false;
        var category = $('#category').val();
        var sku      = $('#sku').val();
        var itemName = $('#itemName').val();
        var iconFile = $('#img_file').prop('files')[0];
        var imgFile  = $('#img_file').val();
        var exStores = $('#exStores').val();

    	if(category.length == 0)
    	{
    		var error = true;
    		$('#categoryError').show();
        }

        if(sku.length == 0)
    	{
    		var error = true;
    		$('#skuError').show();
        }
        else
        {
            if(isNaN(sku))
            {
                var error = true;
                $('#skuError').html('SKU must be a number.');
    		    $('#skuError').show();
            }
        }

        if(itemName.length == 0)
    	{
    		var error = true;
    		$('#itemNameError').show();
        }

        if(imgFile.length != 0)
        {
            if (!iconFile.type.match('image.*')) {
                var error = true;
                $('#imgFileError').show();
            }
            else
            {
                var fileExtension = ['gif', 'png', 'jpg', 'jpeg'];
                var ext = $('#img_file').val().split('.').pop().toLowerCase();

                if($.inArray(ext, fileExtension) == -1)
                {
                    var error = true;
                    $('#imgFileError').html('* Invalid image file extension. Allowed type: gif, png, jpg, jpeg.');
                    $('#imgFileError').show();
                }
            }
        }

    	if(error == false)
    	{
    		$('#btnEditItems').attr('disabled', 'disabled');
            var form_data = new FormData();
            form_data.append('category', category);
            form_data.append('sku', sku);
            form_data.append('itemName', itemName);
            form_data.append('img_file', iconFile);
            form_data.append('itemID', $('#itemID').val());
            form_data.append('codes', exStores);
	    form_data.append('curFile', $('#curFile').val());

            $.ajax({
                url: webURL + '/cms/product/items/update',
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
                                $(window.location).attr('href', webURL + '/cms/product/items');
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
                                $('#btnEditItems').removeAttr('disabled');
                            }
                        });
                    }
                }
            });
    	}
    });


    $('body').on('change', '#category', function() {
        $('#categoryError').hide();
    });

    $('body').on('keyup', '#sku', function() {
        $('#skuError').hide();
    });

    $('body').on('keyup', '#itemName', function() {
        $('#itemNameError').hide();
    });

    $('body').on('keyup', '#price', function() {
        $('#priceError').hide();
    });

    /* ---- Edit Form Modal  --------- */
    $('#modal_view_item_cost').on('show.bs.modal', function(e) {
    	var itemID  = $(e.relatedTarget).data('itemid');
        var remoteLink = webURL + '/cms/product/items/view/price/'+itemID;
        $(this).find('.modal-body').load(remoteLink, function() {});
    });

    $('body').on('click', '#btnFormItemCost', function(e){
        $('#modal_view_item_cost').modal('hide');
        $('#modal_add_item_cost').modal('toggle');
        var item_ID = $('#itemID').val();
        var remoteLink = webURL + '/cms/product/items/add/price/'+item_ID;

        $('#modal_add_item_cost').find('.modal-body').load(remoteLink, function() {
            $('#effectiveDate').daterangepicker({
                singleDatePicker: true,
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });
            $("#effectiveDate").val("");
        });
    });

    $('body').on('click', '#btnSaveItemPrice', function(e){
        e.preventDefault();
        var error = false;
        var itemPrice = $('#itemPrice').val();
        var effDate   = $('#effectiveDate').val();
        var isPromo   = $('input[name="radIsPromo"]:checked').val();

        if(itemPrice.length == 0)
        {
            var error = true;
            $('#itemPriceError').show();
        }
        else
        {
            if(isNaN(itemPrice))
            {
                var error = true;
                $('#itemPriceError').html('Price must be a number or decimal.');
    		    $('#itemPriceError').show();
            }
        }

        if(effDate.length == 0)
        {
            var error = true;
            $('#effectiveDateError').show();
        }

        if(error == false)
        {
            var form_data = new FormData();
            form_data.append('itemPrice', itemPrice);
            form_data.append('effDate', effDate);
            form_data.append('itemID', $('#item_ID').val());
            form_data.append('isPromo', isPromo);

            $.ajax({
                url: webURL + '/cms/product/items/save/price',
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
                                $(window.location).attr('href', webURL + '/cms/product/items');
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
    });

    $('body').on('keydown', '#itemPrice', function(){
        $('#itemPriceError').hide();
    });

    $('body').on('change', '#effectiveDate', function(){
        $('#effectiveDateError').hide();
    });


    $('body').on('click', '#btnPriceUpdate', function() {
        $(this).prop('disabled', true);
        $(this).html('Server is processing your request');
        $('#btnNew').prop('disabled', true);
        var token = $('#globalToken').val()
        $.ajax({
			url: webURL + '/cms/update/price',
			type: 'POST',
			dataType: 'json',
            data:{token},
			cache: false,
			contentType: false,
			processData: false,
			success: function (response) {
				swal({
					title: "Success!",
					text: response.message,
					confirmButtonColor: "#EF5350",
					type: "success"
				},
				function(isConfirm){
					if (isConfirm) {
						$(window.location).attr('href', webURL + '/cms/product/items');
					}
				});
			}
		});
    });

    /* ---- Add Form Modal  --------- */
    $('#modal_add_category').on('show.bs.modal', function() {
        var remoteLink = webURL + '/cms/product/category/add';
        $(this).find('.modal-body').load(remoteLink, function() {
            // Styled file input
            $(".file-styled").uniform({
                fileButtonClass: 'action btn bg-primary'
            });
        });
    });

    $('body').on('click', '#btnSaveCategory', function(e) {
    	e.preventDefault();
    	var error = false;
    	var categoryName = $('#categoryName').val();
        var iconFile     = $('#img_file').prop('files')[0];
        var imgFile      = $('#img_file').val();

    	if(categoryName.length == 0)
    	{
    		var error = true;
    		$('#categoryNameError').show();
        }

        if(imgFile.length != 0)
        {
            if (!iconFile.type.match('image.*')) {
                var error = true;
                $('#imgFileError').show();
            }
            else
            {
                var fileExtension = ['gif', 'png', 'jpg', 'jpeg'];
                var ext = $('#img_file').val().split('.').pop().toLowerCase();

                if($.inArray(ext, fileExtension) == -1)
                {
                    var error = true;
                    $('#imgFileError').html('* Invalid image file extension. Allowed type: gif, png, jpg, jpeg.');
                    $('#imgFileError').show();
                }
            }
        }

    	if(error == false)
    	{
    		$('#btnSaveCategory').attr('disabled', 'disabled');
            var form_data = new FormData();
            form_data.append('categoryName', categoryName);
            form_data.append('img_file', iconFile);

            $.ajax({
                url: webURL + '/cms/product/category/save',
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
                                $(window.location).attr('href', webURL + '/cms/product/category');
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
                                $('#btnSaveCategory').removeAttr('disabled');
                            }
                        });
                    }
                }
            });
    	}
    });

    /* ---- Edit Form Modal  --------- */
    $('#modal_edit_category').on('show.bs.modal', function(e) {
    	var catID  = $(e.relatedTarget).data('catid');
        var remoteLink = webURL + '/cms/product/category/edit/'+catID;
        $(this).find('.modal-body').load(remoteLink, function() {
            // Styled file input
            $(".file-styled").uniform({
                fileButtonClass: 'action btn bg-primary'
            });
        });
    });

    $('body').on('click', '#btnEditCategory', function(e) {
    	e.preventDefault();
    	var error = false;
    	var categoryName = $('#categoryName').val();
        var iconFile     = $('#img_file').prop('files')[0];
        var imgFile      = $('#img_file').val();


    	if(categoryName.length == 0)
    	{
    		var error = true;
    		$('#categoryNameError').show();
        }

        if(imgFile.length != 0)
        {
            if (!iconFile.type.match('image.*')) {
                var error = true;
                $('#imgFileError').show();
            }
            else
            {
                var fileExtension = ['gif', 'png', 'jpg', 'jpeg'];
                var ext = $('#img_file').val().split('.').pop().toLowerCase();

                if($.inArray(ext, fileExtension) == -1)
                {
                    var error = true;
                    $('#imgFileError').html('* Invalid image file extension. Allowed type: gif, png, jpg, jpeg.');
                    $('#imgFileError').show();
                }
            }
        }

    	if(error == false)
    	{
    		$('#btnEditCategory').attr('disabled', 'disabled');
            var form_data = new FormData();
            form_data.append('categoryName', categoryName);
            form_data.append('img_file', iconFile);
            form_data.append('catID', $('#catID').val());
            form_data.append('curFile', $('#curFile').val());

            $.ajax({
                url: webURL + '/cms/product/category/update',
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
                                $(window.location).attr('href', webURL + '/cms/product/category');
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
                                $('#btnEditCategory').removeAttr('disabled');
                            }
                        });
                    }
                }
            });
    	}
    });


    $('body').on('keyup', '#categoryName', function() {
        $('#categoryNameError').hide();
    });

    /* ---- Add Form Modal  --------- */
    $('#modal_add_min_charge').on('show.bs.modal', function() {
        var remoteLink = webURL + '/cms/charges/minimum/add';
        $(this).find('.modal-body').load(remoteLink, function() {
            $('#mineffDate').daterangepicker({
                singleDatePicker: true,
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });
            $("#mineffDate").val("");

            $('.bootstrap-select').selectpicker();
        });
    });

    $('body').on('click', '#btnSaveMinCharge', function(e){
        e.preventDefault();
        var error = false;
        var amount = $('#minAmt').val();
        var minEffDate = $('#mineffDate').val();
        var exStores = $('#exStores').val();

        if(amount.length == 0)
        {
            var error = true;
            $('#minAmtError').show();
        }
        else
        {
            if(isNaN(amount))
            {
                var error = true;
                $('#minAmtError').html('* Enter a valid minimum amount.')
                $('#minAmtError').show();
            }
        }

        if(minEffDate.length == 0)
        {
            var error = true;
            $('#mineffDateError').show();
        }

        if(error == false)
        {
            $('#btnSaveMinCharge').attr('disabled', 'disabled');
            var form_data = new FormData();
            form_data.append('amount', amount);
            form_data.append('effDate', minEffDate);
            form_data.append('codes', exStores);

            $.ajax({
                url: webURL + '/cms/charges/minimum/save',
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
                                $(window.location).attr('href', webURL + '/cms/charges');
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
                                $('#btnSaveMinCharge').removeAttr('disabled');
                            }
                        });
                    }
                }
            });
        }
    });

    /* ---- Edit Form Modal  --------- */
    $('#modal_edit_min_charge').on('show.bs.modal', function(e) {
    	var minChrageID = $(e.relatedTarget).data('itemid');
        var remoteLink = webURL + '/cms/charges/minimum/edit/'+minChrageID;
        $(this).find('.modal-body').load(remoteLink, function() {
            $('#mineffDate').daterangepicker({
                singleDatePicker: true,
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });

            $('.bootstrap-select').selectpicker();
        });
    });

    $('body').on('click', '#btnEditMinCharge', function(e){
        e.preventDefault();
        var error = false;
        var amount = $('#minAmt').val();
        var minEffDate = $('#mineffDate').val();
        var exStores = $('#exStores').val();

        if(amount.length == 0)
        {
            var error = true;
            $('#minAmtError').show();
        }
        else
        {
            if(isNaN(amount))
            {
                var error = true;
                $('#minAmtError').html('* Enter a valid minimum amount.')
                $('#minAmtError').show();
            }
        }

        if(minEffDate.length == 0)
        {
            var error = true;
            $('#mineffDateError').show();
        }

        if(error == false)
        {
            $('#btnEditMinCharge').attr('disabled', 'disabled');
            var form_data = new FormData();
            form_data.append('amount', amount);
            form_data.append('effDate', minEffDate);
            form_data.append('id', $('#minChargeID').val());
            form_data.append('codes', exStores);

            $.ajax({
                url: webURL + '/cms/charges/minimum/update',
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
                                $(window.location).attr('href', webURL + '/cms/charges');
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
                                $('#btnEditMinCharge').removeAttr('disabled');
                            }
                        });
                    }
                }
            });
        }
    });


    /* ---- Add Form Modal  --------- */
    $('#modal_add_del_charge').on('show.bs.modal', function() {
        var remoteLink = webURL + '/cms/charges/delivery/add';
        $(this).find('.modal-body').load(remoteLink, function() {
            $('#deleffDate').daterangepicker({
                singleDatePicker: true,
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });
            $("#deleffDate").val("");

            $('.bootstrap-select').selectpicker();
        });
    });

    $('body').on('click', '#btnSaveDelCharge', function(e){
        e.preventDefault();
        var error = false;
        var amount = $('#delAmt').val();
        var deleffDate = $('#deleffDate').val();
        var exStores = $('#exStores').val();

        if(amount.length == 0)
        {
            var error = true;
            $('#delAmtError').show();
        }
        else
        {
            if(isNaN(amount))
            {
                var error = true;
                $('#delAmtError').html('* Enter a valid delivery amount.')
                $('#delAmtError').show();
            }
        }

        if(deleffDate.length == 0)
        {
            var error = true;
            $('#deleffDateError').show();
        }

        if(error == false)
        {
            $('#btnSaveDelCharge').attr('disabled', 'disabled');
            var form_data = new FormData();
            form_data.append('amount', amount);
            form_data.append('effDate', deleffDate);
            form_data.append('codes', exStores);

            $.ajax({
                url: webURL + '/cms/charges/delivery/save',
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
                                $(window.location).attr('href', webURL + '/cms/charges');
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
                                $('#btnSaveDelCharge').removeAttr('disabled');
                            }
                        });
                    }
                }
            });
        }
    });

    /* ---- Edit Form Modal  --------- */
    $('#modal_edit_del_charge').on('show.bs.modal', function(e) {
    	var dcID = $(e.relatedTarget).data('itemid');
        var remoteLink = webURL + '/cms/charges/delivery/edit/'+dcID;
        $(this).find('.modal-body').load(remoteLink, function() {
            $('#deleffDate').daterangepicker({
                singleDatePicker: true,
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });

            $('.bootstrap-select').selectpicker();
        });
    });

    $('body').on('click', '#btnEditDelCharge', function(e){
        e.preventDefault();
        var error = false;
        var amount = $('#delAmt').val();
        var deleffDate = $('#deleffDate').val();
        var exStores = $('#exStores').val();

        if(amount.length == 0)
        {
            var error = true;
            $('#delAmtError').show();
        }
        else
        {
            if(isNaN(amount))
            {
                var error = true;
                $('#delAmtError').html('* Enter a valid delivery amount.')
                $('#delAmtError').show();
            }
        }

        if(deleffDate.length == 0)
        {
            var error = true;
            $('#deleffDateError').show();
        }

        if(error == false)
        {
            $('#btnEditDelCharge').attr('disabled', 'disabled');
            var form_data = new FormData();
            form_data.append('amount', amount);
            form_data.append('effDate', deleffDate);
            form_data.append('id', $('#dcID').val());
            form_data.append('codes', exStores);

            $.ajax({
                url: webURL + '/cms/charges/delivery/update',
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
                                $(window.location).attr('href', webURL + '/cms/charges');
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
                                $('#btnEditDelCharge').removeAttr('disabled');
                            }
                        });
                    }
                }
            });
        }
    });

    $('body').on('keyup', '#minAmt', function() {
        $('#minAmtError').hide();
    });

    $('body').on('change', '#mineffDate', function() {
        $('#mineffDateError').hide();
    });

    $('body').on('keyup', '#delAmt', function() {
        $('#delAmtError').hide();
    });

    $('body').on('change', '#deleffDate', function() {
        $('#deleffDateError').hide();
    });

    /* ---- Add Form Modal  --------- */
    $('#modal_add_slider').on('show.bs.modal', function() {
        var remoteLink = webURL + '/cms/slider/add';
        $(this).find('.modal-body').load(remoteLink, function() {
            // Styled file input
            $(".file-styled").uniform({
                fileButtonClass: 'action btn bg-primary'
            });
        });
    });

    $('body').on('click', '#btnSaveSlider', function(e) {
    	e.preventDefault();
    	var error = false;
    	var sliderName = $('#sliderName').val();
        var sliderFile = $('#img_file').prop('files')[0];
        var imgFile    = $('#img_file').val();

    	if(sliderName.length == 0)
    	{
    		var error = true;
    		$('#sliderNameError').show();
        }

        if(imgFile.length != 0)
        {
            if (!sliderFile.type.match('image.*')) {
                var error = true;
                $('#imgFileError').show();
            }
            else
            {
                var fileExtension = ['gif', 'png', 'jpg', 'jpeg'];
                var ext = $('#img_file').val().split('.').pop().toLowerCase();

                if($.inArray(ext, fileExtension) == -1)
                {
                    var error = true;
                    $('#imgFileError').html('* Invalid image file extension. Allowed type: gif, png, jpg, jpeg.');
                    $('#imgFileError').show();
                }
            }
        }

    	if(error == false)
    	{
    		$('#btnSaveSlider').attr('disabled', 'disabled');
            var form_data = new FormData();
            form_data.append('sliderName', sliderName);
            form_data.append('img_file', sliderFile);

            $.ajax({
                url: webURL + '/cms/slider/save',
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
                                $(window.location).attr('href', webURL + '/cms/slider');
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
                                $('#btnSaveSlider').removeAttr('disabled');
                            }
                        });
                    }
                }
            });
    	}
    });

    /* ---- Edit Form Modal  --------- */
    $('#modal_edit_slider').on('show.bs.modal', function(e) {
    	var sliderID  = $(e.relatedTarget).data('sid');
        var remoteLink = webURL + '/cms/slider/edit/'+sliderID;
        $(this).find('.modal-body').load(remoteLink, function() {
            // Styled file input
            $(".file-styled").uniform({
                fileButtonClass: 'action btn bg-primary'
            });
        });
    });

    $('body').on('click', '#btnEditSlider', function(e) {
    	e.preventDefault();
    	var error = false;
    	var sliderName = $('#sliderName').val();
        var sliderFile = $('#img_file').prop('files')[0];
        var imgFile    = $('#img_file').val();


    	if(sliderName.length == 0)
    	{
    		var error = true;
    		$('#sliderNameError').show();
        }

        if(imgFile.length != 0)
        {
            if (!sliderFile.type.match('image.*')) {
                var error = true;
                $('#imgFileError').show();
            }
            else
            {
                var fileExtension = ['gif', 'png', 'jpg', 'jpeg'];
                var ext = $('#img_file').val().split('.').pop().toLowerCase();

                if($.inArray(ext, fileExtension) == -1)
                {
                    var error = true;
                    $('#imgFileError').html('* Invalid image file extension. Allowed type: gif, png, jpg, jpeg.');
                    $('#imgFileError').show();
                }
            }
        }

    	if(error == false)
    	{
    		$('#btnEditSlider').attr('disabled', 'disabled');
            var form_data = new FormData();
            form_data.append('sliderName', sliderName);
            form_data.append('img_file', sliderFile);
            form_data.append('sliderID', $('#sliderID').val());
            form_data.append('curFile', $('#curFile').val());

            $.ajax({
                url: webURL + '/cms/slider/update',
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
                                $(window.location).attr('href', webURL + '/cms/slider');
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
                                $('#btnEditSlider').removeAttr('disabled');
                            }
                        });
                    }
                }
            });
    	}
    });

    $('body').on('keyup', '#sliderName', function() {
        $('#sliderNameError').hide();
    });

    /* ---- Add Form Modal  --------- */
    $('#modal_add_banner').on('show.bs.modal', function() {
        var remoteLink = webURL + '/cms/ads/add';
        $(this).find('.modal-body').load(remoteLink, function() {
            // Styled file input
            $(".file-styled").uniform({
                fileButtonClass: 'action btn bg-primary'
            });
        });
    });

    $('body').on('click', '#btnSaveAd', function(e) {
    	e.preventDefault();
    	var error = false;
    	var bannerName = $('#bannerName').val();
        var pageLocation = $('#pageLocation').val();
        var bannerFile   = $('#img_file').prop('files')[0];
        var imgFile      = $('#img_file').val();

    	if(bannerName.length == 0)
    	{
    		var error = true;
    		$('#bannerNameError').show();
        }

    	if(pageLocation.length == 0)
    	{
    		var error = true;
    		$('#pageLocationError').show();
        }

        if(imgFile.length != 0)
        {
            if (!bannerFile.type.match('image.*')) {
                var error = true;
                $('#imgFileError').show();
            }
            else
            {
                var fileExtension = ['gif', 'png', 'jpg', 'jpeg'];
                var ext = $('#img_file').val().split('.').pop().toLowerCase();

                if($.inArray(ext, fileExtension) == -1)
                {
                    var error = true;
                    $('#imgFileError').html('* Invalid image file extension. Allowed type: gif, png, jpg, jpeg.');
                    $('#imgFileError').show();
                }
            }
        }

    	if(error == false)
    	{
    		$('#btnSaveAd').attr('disabled', 'disabled');
            var form_data = new FormData();
            form_data.append('bannerName', bannerName);
            form_data.append('pageLocation', pageLocation);
            form_data.append('img_file', bannerFile);

            $.ajax({
                url: webURL + '/cms/ads/save',
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
                                $(window.location).attr('href', webURL + '/cms/ads');
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
                                $('#btnSaveAd').removeAttr('disabled');
                            }
                        });
                    }
                }
            });
    	}
    });

    /* ---- Edit Form Modal  --------- */
    $('#modal_edit_banner').on('show.bs.modal', function(e) {
    	var adID  = $(e.relatedTarget).data('aid');
        var remoteLink = webURL + '/cms/ads/edit/'+adID;
        $(this).find('.modal-body').load(remoteLink, function() {
            // Styled file input
            $(".file-styled").uniform({
                fileButtonClass: 'action btn bg-primary'
            });
        });
    });

    $('body').on('click', '#btnEditAd', function(e) {
    	e.preventDefault();
    	var error = false;
    	var bannerName   = $('#bannerName').val();
        var pageLocation = $('#pageLocation').val();
        var bannerFile   = $('#img_file').prop('files')[0];
        var imgFile      = $('#img_file').val();


    	if(bannerName.length == 0)
    	{
    		var error = true;
    		$('#bannerNameError').show();
        }

    	if(pageLocation.length == 0)
    	{
    		var error = true;
    		$('#pageLocationError').show();
        }

        if(imgFile.length != 0)
        {
            if (!bannerFile.type.match('image.*')) {
                var error = true;
                $('#imgFileError').show();
            }
            else
            {
                var fileExtension = ['gif', 'png', 'jpg', 'jpeg'];
                var ext = $('#img_file').val().split('.').pop().toLowerCase();

                if($.inArray(ext, fileExtension) == -1)
                {
                    var error = true;
                    $('#imgFileError').html('* Invalid image file extension. Allowed type: gif, png, jpg, jpeg.');
                    $('#imgFileError').show();
                }
            }
        }

    	if(error == false)
    	{
    		$('#btnEditAd').attr('disabled', 'disabled');
            var form_data = new FormData();
            form_data.append('bannerName', bannerName);
            form_data.append('pageLocation', pageLocation);
            form_data.append('img_file', bannerFile);
            form_data.append('adID', $('#adID').val());
            form_data.append('curFile', $('#curFile').val());

            $.ajax({
                url: webURL + '/cms/ads/update',
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
                                $(window.location).attr('href', webURL + '/cms/ads');
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
                                $('#btnEditAd').removeAttr('disabled');
                            }
                        });
                    }
                }
            });
    	}
    });

    $('body').on('keyup', '#bannerName', function() {
        $('#bannerNameError').hide();
    });

    $('body').on('keyup', '#pageLocation', function() {
        $('#pageLocationError').hide();
    });



    $('body').on('click', '#btnRefreshBranch', function() {
        $('#btnRefreshBranch').prop('disabled', true);
        $.ajax({
            url: webURL + '/cms/store/branch/refresh',
            type: 'POST',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                swal({
                    title: "Success!",
                    text: response.message,
                    confirmButtonColor: "#EF5350",
                    type: "success"
                },
                function(isConfirm){
                    if (isConfirm) {
                        $(window.location).attr('href', webURL + '/cms/store/branch');
                    }
                });
            }
        });
    });

    $('body').on('click', '#btnRefreshStores', function() {

        $('#btnRefreshStores').prop('disabled', true);
        $.ajax({
            url: webURL + '/cms/store/refresh',
            type: 'POST',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                swal({
                    title: "Success!",
                    text: response.message,
                    confirmButtonColor: "#EF5350",
                    type: "success"
                },
                function(isConfirm){
                    if (isConfirm) {
                        $(window.location).attr('href', webURL + '/cms/store');
                    }
                });
            }
        });
    });

    $('body').on('change', '#dcCode', function() {
    	$('#dcCodeError').hide();
    });

    $('body').on('keyup', '#storeCode', function() {
    	$('#storeCodeError').hide();
    });

    $('body').on('keyup', '#storeName', function() {
    	$('#storeNameError').hide();
    });

    $('body').on('keyup', '#address', function() {
    	$('#addressError').hide();
    });

    $('body').on('keyup', '#province', function() {
    	$('#provinceError').hide();
    });

    $('body').on('keyup', '#latitude', function() {
    	$('#latitudeError').hide();
    });

    $('body').on('keyup', '#longitude', function() {
    	$('#longitudeError').hide();
    });


    // External table additions
    // ------------------------------

    // Add placeholder to the datatable filter option
    $('.dataTables_filter input[type=search]').attr('placeholder','Type to filter...');


    // Enable Select2 select for the length option
    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });


    $('body').on('click','#btnExtractStoreList',function(){
        $(window.location).attr('href', webURL + '/cms/store/extract');
    })
});

function updateItemStatus(action, id)
{
    var form_data = new FormData();
    form_data.append('itemID', id);

    $.ajax({
        url: webURL + '/cms/product/items/status/'+action,
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
                    	$(window.location).attr('href', webURL + '/cms/product/items');
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

function updateCategoryStatus(action, id)
{
    var form_data = new FormData();
    form_data.append('catID', id);

    $.ajax({
        url: webURL + '/cms/product/category/status/'+action,
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
                    	$(window.location).attr('href', webURL + '/cms/product/category');
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

function updateSliderStatus(action, id)
{
    var form_data = new FormData();
    form_data.append('sliderID', id);

    $.ajax({
        url: webURL + '/cms/slider/status/'+action,
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
                    	$(window.location).attr('href', webURL + '/cms/slider');
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

function updateAdStatus(action, id)
{
    var form_data = new FormData();
    form_data.append('adID', id);

    $.ajax({
        url: webURL + '/cms/ads/status/'+action,
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
                    	$(window.location).attr('href', webURL + '/cms/ads');
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

function updateBranchStatus(action, id)
{
    var form_data = new FormData();
    form_data.append('branchID', id);

    $.ajax({
        url: webURL + '/cms/store/branch/status/'+action,
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
                    	$(window.location).attr('href', webURL + '/cms/store/branch');
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

function updateStoreStatus(action, id)
{
    var form_data = new FormData();
    form_data.append('storeID', id);

    $.ajax({
        url: webURL + '/cms/store/status/'+action,
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
                    	$(window.location).attr('href', webURL + '/cms/store');
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
