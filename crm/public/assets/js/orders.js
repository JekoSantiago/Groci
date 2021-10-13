/* ------------------------------------------------------------------------------
*
*  # Order Module
*
*  Specific JS code additions for order page
*
*  Version: 1.0
*  Latest update: Aug 1, 2015
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

    $.ajaxSetup({
        headers:
        { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $('.datatable-orders-basic').DataTable({
        "autoWidth":false,
        "ordering": false,
        scrollX: true
    });

    var cartItems = $('.datatable-order-items').DataTable({
        bJQueryUI: true,
        bFilter: false,
        bInfo: false,
        bPaginate: false,
        sDom: 't',
        "ordering": false,
        autoWidth: false,
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": webURL + "/orders/cart/item/"+orderID,
            "method": "POST",
            "datatype": "json",
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Processing data failed. Please report to the System Adminstator.");
            }
        },
        columns:[
            {
                render: function (data, type, row) {
                    var value = row.order_items_id;

                    return '<ul class="icons-list"><li><a id="removeItem" data-itemid="'+ value  +'" data-popup="tooltip" title="Add" data-placement="left"><i class="icon-x"></i></a></li></ul>';
                }
            },
            { "data": "item_name" },
            { "data": "item_price" },
            {
                render: function (data, type, row) {
                    var value = row.order_items_id+'@@'+row.item_price;

                    return '<input class="form-control text-center" data-oid="'+value+'" id="inpQty" name="qty_'+row.order_items_id+'" type="text" value="' + row.qty + '">'+
                           '<input type="hidden" id="curQty_'+row.order_items_id+'" value="' + row.qty + '">';
                }
            },
            { "data": "total_amount" }
        ],
        "fnInitComplete": function(oSettings, json) {
            var totalRecords = json.recordsTotal;

            if(totalRecords > 0)
            {
                $('#btnReview').attr('disabled', false);
                $('#totalAmount').html(json.totalAmount);
            }
            else
            {
                $('#btnReview').attr('disabled', true);
                $('#totalAmount').html('0.00');
            }
        }
    });

    var editBasketItems = $('#editBasketItems').DataTable({
        bJQueryUI: true,
        bFilter: false,
        bInfo: false,
        bPaginate: false,
        sDom: 't',
        "ordering": false,
        autoWidth: false,
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": webURL + "/orders/cart/item/"+orderID,
            "method": "POST",
            "datatype": "json",
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Processing data failed. Please report to the System Adminstator.");
            }
        },
        columns:[
            {
                render: function (data, type, row) {
                    return '<ul class="icons-list"><li><a id="btnDeleteItemE" data-oid="'+ row.order_items_id+'" data-itemid="'+ row.item_id  +'" data-popup="tooltip" title="Add" data-placement="left"><i class="icon-x"></i></a></li></ul>';
                }
            },
            { "data": "item_name" },
            { "data": "item_price" },
            {
                render: function (data, type, row) {
                    var value = row.order_items_id+'@@'+row.item_price+'@@'+row.item_id;

                    return '<input class="form-control text-center" data-oid="'+value+'" id="updateQtyE" name="uQtyE_'+row.order_items_id+'" type="text" value="' + row.qty + '">'+
                            '<input type="hidden" id="upCurQtyE_'+row.order_items_id+'" value="' + row.qty + '">';
                }
            },
            { "data": "total_amount" }
        ],
        "fnInitComplete": function(oSettings, json) {
            var totalRecords = json.recordsTotal;
            var amountDue = parseFloat(json.totalAmount) + parseFloat(json.charges) ;

            if(totalRecords > 0)
            {
                $('#amountDue').html(amountDue.toFixed(2));
            }
            else
            {
                $('#totalAmount').html('0.00');
            }
        }
    });

    $('body').on('change', '#updateQtyE', function(){
        var items = $(this).data('oid')
        var v = items.split('@@');
        var orderID = $('#orderID').val();
        var itemID  = v[2];
        var orderItemID = v[0];
        var qty     = $("input[name='uQtyE_"+ v[0] +"']").val();
        var curQty  = $("#upCurQtyE_"+  v[0]).val();
        var price   = v[1]
        var action  = 'update';
        var form_data = new FormData();
        form_data.append('id', orderID);
        form_data.append('itemID', itemID);
        form_data.append('oID', orderItemID);
        form_data.append('qty', qty);
        form_data.append('curQty', curQty);
        form_data.append('price', price);
        form_data.append('action', action);

        $.ajax({
            url: webURL + '/orders/modify/cart',
            type: 'POST',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            success: function (response) {
                if(response.status == 'success')
                {
                    editBasketItems.ajax.reload();
                    $('#amountDue').html(response.amount.toFixed(2));
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
    });

    $('body').on('click', '#btnDeleteItemE', function() {
        var orderID = $('#orderID').val();
        var itemID  = $(this).data('itemid');
        var orderItemID  = $(this).data('oid');
        var qty     = '';
        var price   = '';
        var curQty  = '';
        var action  = 'delete';
        var form_data = new FormData();
        form_data.append('id', orderID);
        form_data.append('itemID', itemID);
        form_data.append('oID', orderItemID);
        form_data.append('qty', qty);
        form_data.append('price', price);
        form_data.append('action', action);
        form_data.append('curQty', curQty);

        $.ajax({
            url: webURL + '/orders/modify/cart',
            type: 'POST',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            success: function (response) {
                if(response.status == 'success')
                {
                    editBasketItems.ajax.reload();
                    $('#amountDue').html(response.amount.toFixed(2));
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
    });

    var prodItems = new Array();
    $('body').on('change', '#Quant', function(e) {
        e.preventDefault();
        var error = false;
        var value = $(this).data('params');
        var d = value.split('@@');
        var inpQty = $("input[name='Quant_"+ d[1] +"']").val();
        var minValue =  parseInt($(this).attr('min'));
        var maxValue =  parseInt($(this).attr('max'));

        if(isNaN(inpQty) || inpQty <= 0 || isFloat(inpQty))
        {
            var error = true;
            swal({
                title: "Warning!",
                text: 'You are not allowed to enter non-numeric, decimal, zero and negative value quantity.',
                confirmButtonColor: "#EF5350",
                type: "warning"
            },
            function(isConfirm){
                if (isConfirm) {
                    $("input[name='Quant_"+ d[1] +"']").val("").focus();
                }
            });
        }

        if(inpQty < minValue) {
            var error = true;
            swal({
                title: "Warning!!!",
                text: "Sorry, the minimum value of one(1) was reached.",
                type: "warning",
                timer: 2000,
                showConfirmButton: false
            });

            $("input[name='Quant_"+ d[1] +"']").val("");
        }

        if(inpQty > maxValue) {
            var error = true;
            swal({
                title: "Warning!!!",
                text: "Sorry, the maximum value of "+ maxValue +" was reached",
                type: "warning",
                timer: 2000,
                showConfirmButton: false
            });

            $("input[name='Quant_"+ d[1] +"']").val("");
        }

        if(error == false)
        {
            var newVal = value +'@@'+ inpQty;
            prodItems.push(newVal);
            $('#selectedItems').val(prodItems);
        }
    });

    $('body').on('click', '#btnAddtoCart', function(e){
        e.preventDefault();
        var error = false;
        var orderID = $('#orderID').val();
        var prodItems = $("#selectedItems").val();

        if(prodItems.length == 0)
        {
            var error = true;
            swal({
                title: "Error!",
                text: 'Please select product items to add to basket.',
                confirmButtonColor: "#EF5350",
                type: "error"
            });
        }

        if(error == false)
        {
            var count = $("table.datatable-order-items").dataTable().fnSettings().aoData.length + 1
            addToBasket(prodItems, cartItems, count, orderID);
        }
    });

    $('body').on('click', '#btnSaveOrder', function(e){
        e.preventDefault();
        var error = false;
        var firstName   = $('#firstName').val();
        var lastName    = $('#lastName').val();
        var email       = $('#emailAdd').val();
        var emailReg    = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        var mobileNum   = $('#mobileNum').val();
        var addType     = $('#addressType').val();
        var address     = $('#address').val();
        var city        = $('#city').val();
        var province    = $('#province').val();
        var landmark    = $('#landmark').val();
        var remarks     = $('#orderRemarks').val();
        var customerID  = $('#customerID').val();
        var addressID   = $('#addressID').val();
        var totAmount   = $('#totAmount').val();
        var orderID     = $('#orderID').val();
        var transType   = $('#transactionType').val();
        var changeFor   = $('#change_for').val();


        if(transType == 'pick-up')
        {
            var schedule = $('#pickDate').val() + ' ' + $('#pickHour').val() + ':' + $('#pickMin').val() +' '+ $('#pickAMPM').val();
            var orderType = 'Pick-up';
        }
        else
        {
            var deliverType = $('input[name="deliveryType"]:checked').val();
            var schedule = (deliverType == 'Delivery Now') ? 'PROMISE TIME' : $('#dLaterDate').val() + ' ' + $('#dLaterHour').val()+':'+$('#dLaterMin').val()+' '+$('#dLaterAMPM').val();
            var orderType = deliverType;
        }

        if(firstName.length == 0)
        {
            var error = true;
            $('#firstNameInput').addClass('has-error');
        }

        if(lastName.length == 0)
        {
            var error = true;
            $('#lastNameInput').addClass('has-error');
        }

        if(email.length == 0 || !emailReg.test(email))
        {
            var error = true;
            $('#emailInput').addClass('has-error');
        }

        if(mobileNum.length == 0 || isNaN(mobileNum))
        {
            var error = true;
            $('#mobileInput').addClass('has-error');
        }

        if(addType.length == 0)
        {
            $('#selectAddType').addClass('has-error');
        }

        if(address.length == 0)
        {
            var error = true;
            $('#addressInput').addClass('has-error');
        }

        if(city.length == 0)
        {
            var error = true;
            $('#cityInput').addClass('has-error');
        }

        if(province.length == 0)
        {
            $('#selectProvince').addClass('has-error');
        }

        if(changeFor.length == 0) {
            var error = true;
            $('#changeForInput').addClass('has-error');
        }
        else {
            if(parseFloat(changeFor) < parseFloat(totAmount)) {
                var error = true;
                $('#changeError').html('* You enter an invalid amount.');
                $('#changeError').show();
            }
        }

        if(error == false)
        {
            $('#btnSaveOrder').hide();
            $('#btnBack').hide();
            $('#btnReset').hide();
            $('#btnCancel').hide();
            $('#processing_loaders').show();

            var form_data = new FormData();
            form_data.append('firstName', firstName);
            form_data.append('lastName', lastName);
            form_data.append('email', email);
            form_data.append('mobileNum', mobileNum);
            form_data.append('type', addType);
            form_data.append('address', address);
            form_data.append('city', city);
            form_data.append('province', province);
            form_data.append('landmark', landmark);
            form_data.append('remarks', remarks);
            form_data.append('customerID', customerID);
            form_data.append('addressID', addressID);
            form_data.append('totalAmount', totAmount);
            form_data.append('orderID', orderID);
            form_data.append('transType', orderType);
            form_data.append('schedule', schedule);
            form_data.append('changeFor', changeFor);

            $.ajax({
                url: webURL + '/orders/save',
                type: 'POST',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                success: function (response) {
                    if(response.status == 'ok')
                    {
                        var fileName = orderID + '.pdf';
                        var docs = webURL + '/storage/pdfs/'+fileName;

                        swal({
                            title: 'Success!',
                            text: response.message,
                            type: "success",
                            confirmButtonColor: "#1976D2"
                        },
                        function(isConfirm){
                            if (isConfirm) {
                                print_pdf(docs);
                                $('#modal_review_orders').modal('hide');
                                $(window.location).attr('href', webURL + '/orders');
                            }
                            else {
                                $(window.location).attr('href', webURL + '/orders');
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
                                $('#btnSaveOrder').show();
                                $('#btnBack').show();
                                $('#btnReset').show();
                                $('#btnCancel').show();
                                $('#processing_loaders').hide();
                            }
                        });
                    }
                }
            });
        }

    });

    $('body').on('keyup', '#firstName', function(){
        $('#firstNameInput').removeClass('has-error');
    });

    $('body').on('keyup', '#lastName', function(){
        $('#lastNameInput').removeClass('has-error');
    });

    $('body').on('keyup', '#emailAdd', function(){
        $('#emailInput').removeClass('has-error');
    });

    $('body').on('keyup', '#mobileNum', function(){
        $('#mobileInput').removeClass('has-error');
    });

    $('body').on('change', '#addressType', function(){
        $('#selectAddType').removeClass('has-error');
    });

    $('body').on('keyup', '#address', function(){
        $('#addressInput').removeClass('has-error');
    });

    $('body').on('keyup', '#city', function(){
        $('#cityInput').removeClass('has-error');
    });

    $('body').on('change', '#province', function(){
        $('#selectProvince').removeClass('has-error');
    });

    $('body').on('keyup', '#change_for', function(){
        $('#changeForInput').removeClass('has-error');
        $('#changeError').hide();
    });

    /* ---- Edit Form Modal  --------- */
    $('#modal_review_orders').on('show.bs.modal', function(e) {
    	var orderID  = $(e.relatedTarget).data('orderid');
        var remoteLink = webURL + '/orders/view/cart/'+orderID;
        $(this).find('.modal-body').load(remoteLink, function() {
            // Init Select2 when loaded
            $('.selectCustomer').select2({});

            $('.select').select2({
                minimumResultsForSearch: Infinity
            });

            // Default initialization
            $(".styled").uniform({
                radioClass: 'choice'
            });

            $('#dLaterDate, #pickDate').daterangepicker({
                singleDatePicker: true,
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });

        });
    });

    $('#modal_order_add').on('show.bs.modal', function(e) {
        var oid = $(this).data('oid');
        var remoteLink = webURL + '/orders/manual/'+oid;
        $(this).find('.modal-body').load(remoteLink, function() {
            // Init Select2 when loaded
            $('#dt-order').DataTable({
                "ordering": false,
                pageLength : 5,
                lengthChange: false,
            });

        });
    });

    $('body').on('click', '#btnTransType', function() {
        var value = $(this).data('value');

        if(value == 'pick-up')
        {
            $('#deliveryTrans').hide();
            $('#pickUpTrans').show();
            $('.btnDelivery').removeClass('btn-default');
            $('.btnDelivery').addClass('bg-grey');
            $('.btnPickUp').removeClass('bg-grey');
            $('.btnPickUp').addClass('btn-default');
        }
        else
        {
            $('#deliveryTrans').show();
            $('#pickUpTrans').hide();
            $('.btnDelivery').removeClass('bg-grey');
            $('.btnDelivery').addClass('btn-default');
            $('.btnPickUp').removeClass('btn-default');
            $('.btnPickUp').addClass('bg-grey');
        }

        $('#transactionType').val(value);
    });

    $('body').on('change', 'input[name="deliveryType"]', function() {
        var dType = $(this).val();

        if(dType == 'Delivery Now')
        {
            $('#delLaterDate').hide();
            $('#delNowDate').show();
        }
        else
        {
            $('#delNowDate').hide();
            $('#delLaterDate').show();
        }
    });

    $('body').on('change', '#inpQty', function() {
        var value = $(this).data('oid');
        var d = value.split('@@');
        var inpQty = $("input[name='qty_"+ d[0] +"']").val();
        var curQty  = $("#curQty_"+  d[0]).val();

        if(isNaN(inpQty) || inpQty < 0 || isFloat(inpQty))
        {
            swal({
                title: "Warning!",
                text: "You are not allowed to enter non-numeric, decimal and negative value quantity.",
                confirmButtonColor: "#EF5350",
                type: "warning"
            },
            function(isConfirm){
                if (isConfirm) {
                    $("input[name='qty_"+  d[0] +"']").val(curQty);
                }
            });
        }
        else
        {
            updateBasket( d[0], inpQty,  d[1], cartItems, orderID, 'update');
        }

    });

    $('body').on('click', '#removeItem', function() {
        var orderItemID = $(this).data('itemid');
        var inpQty    = 0;
        var itemPrice = 0;

        updateBasket(orderItemID, inpQty,  itemPrice, cartItems, orderID, 'remove');

    });

    $('body').on('change', '#storeCustomer', function() {
        var value = $(this).val();

        $.ajax({
            url: webURL + '/customer/details/'+value,
            type: 'GET',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {

                $('#firstName').val(response.firstname);
                $('#firstName').attr('disabled','disabled');
                $('#lastName').val(response.lastname);
                $('#lastName').attr('disabled','disabled');
                $('#emailAdd').val(response.email);
                $('#emailAdd').attr('disabled','disabled');
                $('#mobileNum').val(response.contact_num);
                $('#mobileNum').attr('disabled','disabled');
                $('#addressType').val(response.type).change();
                $('#addressType').attr('disabled', 'disabled');
                $('#address').val(response.address);
                $('#address').attr('disabled','disabled');
                $('#city').val(response.city);
                $('#city').attr('disabled','disabled');
                $('#province').val(response.province_id).change();
                $('#province').attr('disabled','disabled');
                $('#landmark').val(response.landmarks);
                $('#landmark').attr('disabled','disabled');
                $('#customerID').val(response.customer_id);
                $('#addressID').val(response.addressID);

            }
        });
    });

    $('body').on('click', '#btnReset', function() {

        $('#storeCustomer').val("").change();
        $('#firstName').val("");
        $('#firstName').removeAttr('disabled');
        $('#lastName').val("");
        $('#lastName').removeAttr('disabled');
        $('#emailAdd').val("");
        $('#emailAdd').removeAttr('disabled');
        $('#mobileNum').val("");
        $('#mobileNum').removeAttr('disabled');
        $('#addressType').val("").change();
        $('#addressType').removeAttr('disabled');
        $('#address').val("");
        $('#address').removeAttr('disabled');
        $('#city').val("");
        $('#city').removeAttr('disabled');
        $('#province').val("").change();
        $('#province').removeAttr('disabled');
        $('#landmark').val("");
        $('#landmark').removeAttr('disabled');
        $('#customerID').val("");
        $('#addressID').val("");
    });

    $('body').on('click', '#btnCancel', function() {
        swal({
            title: "Are you sure?",
            text: "You want to cancel your order?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#2196f3",
            cancelButtonColor: "#ed1c24",
            confirmButtonText: "YES",
            cancelButtonText: "NO",
            closeOnConfirm: true,
            closeOnCancel: true
        },
        function(isConfirm){
            if (isConfirm) {
                $(window.location).attr('href', webURL + '/orders');
            }
        });
    });

    /* ---- Edit Form Modal  --------- */
    $('#modal_receipt_form').on('show.bs.modal', function(e) {
    	var orderID  = $(e.relatedTarget).data('sid');
        var remoteLink = webURL + '/orders/punch/'+orderID;
        $(this).find('.modal-body').load(remoteLink, function() {});
    });

    $('body').on('click', '#btnPunch', function(e){
        e.preventDefault();
        var error = false;
        var receipt   = $('#receiptNum').val();
        var orderID   = $('#orderID').val();
        var status    = 'on_process';
        var payStatus = '';

        if(receipt.length == 0)
        {
            var error = true;
            $('#receiptNumError').show();
        }
        else
        {
            if(isNaN(receipt) || isFloat(receipt))
            {
                var error = true;
                $('#receiptNumError').html("* Enter numbers only.");
                $('#receiptNumError').show();
            }
        }

        if(error == false)
        {
            $('#btnClose').attr('disabled', 'disabled');
            $('#btnPunch').attr('disabled', 'disabled');
            updateOrderStatus(orderID, status, receipt, payStatus);
        }

    });

    $('body').on('keyup', '#receiptNum', function(){
        $('#receiptNumError').hide();
    });

    /* ---- Edit Form Modal  --------- */
    $('#modal_order_details').on('show.bs.modal', function(e) {
        var orderID     = $(e.relatedTarget).data('oid');
        var orderStatus = $(e.relatedTarget).data('status');
        var remoteLink  = webURL + '/orders/details/'+orderID;
        $(this).find('.modal-body').load(remoteLink, function() {
            // Init Select2 when loaded
            $('.selectCustomer').select2({});

            $('.select').select2({
                minimumResultsForSearch: Infinity
            });

            $('[data-popup=tooltip]').tooltip();

            if(orderStatus == 'CLOSE' || orderStatus == 'CANCEL' || orderStatus == 'DELIVERED' || orderStatus == 'PICKED')
            {
                $('#btnPrintOrder').hide();
                $('#btnReceiveOrder').hide();
                $('#btnEditOrder').hide();
            }
            else
            {
                $('#btnPrintOrder').show();
                $('#btnReceiveOrder').show();
                $('#btnEditOrder').show();
            }
        });
    });

    /* ---- Edit Form Modal  --------- */
    $('#modal_order_validation').on('show.bs.modal', function(e) {
        var orderID     = $(e.relatedTarget).data('oid');
        var orderType   = $(e.relatedTarget).data('ot');
        var remoteLink  = webURL + '/orders/validate/'+orderID;
        $(this).find('.modal-body').load(remoteLink, function() {
            // Init Select2 when loaded
            $('.selectCustomer').select2({});

            $('.select').select2({
                minimumResultsForSearch: Infinity
            });

            $('#orderType').val(orderType);
        });
    });

    $('body').on('click', '#btnCheckOrder', function(e) {
        e.preventDefault();
        var error = false;
        var orderID   = $('#orderID').val();
        var amtChange = $('#cashChange').val();
        var orderType = $('#orderType').val();
        var status    = (orderType == 'Pick-up') ? 'pick-up' : 'delivery';
        var receipt   = '';
        var payStatus = '';

        if(amtChange.length == 0)
        {
            var error = true;
            $('#cashChangeError').show();
        }
        else
        {
            if(isNaN(amtChange))
            {
                var error = true;
                $('#cashChangeError').html('Amount change must be a number.');
                $('#cashChangeError').show();
            }
        }

        if(error == false)
        {
            var form_data = new FormData();
            form_data.append('orderID', orderID);
            form_data.append('amount', amtChange);

            $.ajax({
                url: webURL + '/orders/save/cash-change',
                type: 'POST',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                success: function (response) {
                    if(response.status == 'success')
                    {
                        updateOrderStatus(orderID, status, receipt, payStatus);
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

    $('#modal_cancel_form').on('show.bs.modal', function(e) {
        var orderID     = $(e.relatedTarget).data('oid');
        var remoteLink  = webURL + '/orders/cancel/'+orderID;
        $(this).find('.modal-body').load(remoteLink, function() {
            // Init Select2 when loaded
            $('.selectCustomer').select2({});

            $('.select').select2({
                minimumResultsForSearch: Infinity
            });
        });
    });



    $('body').on('click', '#btnOrderDelivered', function(){
        var orderID = $(this).data('oid');
        var status  = 'delivered';
        var receipt = '';
        var payStatus = 'COMPLETE';

        updateOrderStatus(orderID, status, receipt, payStatus);
    });

    $('body').on('click', '#btnOrderPickup', function(){
        var orderID = $(this).data('oid');
        var status  =  'picked';
        var receipt = '';
        var payStatus = 'COMPLETE';

        updateOrderStatus(orderID, status, receipt, payStatus);
    });

    $('body').on('click', '#btnOrderClosed', function(){
        var orderID = $(this).data('oid');
        var status  = 'closed';
        var receipt = '';
        var payStatus = '';

        updateOrderStatus(orderID, status, receipt, payStatus);
    });

    $('body').on('click', '#btnOrderCancel', function(e){
        e.preventDefault();
        var error = false;
        var orderID = $('#orderID').val();
        var remarks = $('#cancelRemarks').val();
        var status  = 'cancel';
        var receipt = '';
        var payStatus = 'INCOMPLETE';

        if(remarks.length == 0)
        {
            var error = true;
            $('#cancelRemarksError').show()
        }

        if(error == false)
        {
            var form_data = new FormData();
            form_data.append('orderID', orderID);
            form_data.append('remarks', remarks);

            $.ajax({
                url: webURL + '/orders/save/remarks',
                type: 'POST',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                success: function (response) {
                    if(response.status == 'success')
                    {
                        updateOrderStatus(orderID, status, receipt, payStatus);
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

    $('body').on('keyup', '#cancelRemarks', function(){
        $('#cancelRemarksError').hide();
    });

    $('body').on('click', '#btnRePrint', function() {
        var orderID = $(this).data('oid');
        var form_data = new FormData();
        form_data.append('id', orderID);

        $.ajax({
            url: webURL + '/orders/create/pdf',
            type: 'POST',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            success: function (response) {
                var docs  = webURL + '/storage/pdfs/'+response;
                print_pdf(docs);
            }
        });

    });

    $('body').on('click', '#btnPrintOrder', function() {

        var orderID = $('#orderID').val();
        var fileName = orderID + '.pdf';
        var pdfDoc  = webURL + '/storage/pdfs/'+fileName;
        print_pdf(pdfDoc);
    });

    $('body').on('click', '#btnReceiveOrder', function() {
        var orderID = $('#orderID').val();
        var form_data = new FormData();
        form_data.append('orderID', orderID);
        form_data.append('receipt', '');
        form_data.append('status', 'received');
        form_data.append('payStatus', '');
        $('#btnReceiveOrder').prop('disabled', true);
        $('#btnPrintOrder').prop('disabled', true);
        $('#btnEditOrder').prop('disabled', true);
        $('#btnCloseModal').prop('disabled', true);

        $.ajax({
            url: webURL + '/orders/status/update',
            type: 'POST',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            success: function (response) {
                if(response.status == 'success')
                {
                    $('#orderCount').html(response.count);
                    //$('#ordersTable').load(location.href + " #ordersTable");
                    $('#modal_order_details').modal('hide');
                    $('#modal_receipt_form').modal('show');
                    // $(window.location).attr('href', webURL + '/orders');
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
                            $('#btnReceiveOrder').prop('disabled', false);
                            $('#btnPrintOrder').prop('disabled', false);
                            $('#btnEditOrder').prop('disabled', false);
                            $('#btnCloseModal').prop('disabled', false);
                            $('#orderCount').html(response.count);
                        }
                    });
                }
            }
        });
    });

    $('body').on('click', '#btnEditOrder', function(){
        var orderID = $('#orderID').val();

        $(window.location).attr('href', webURL + '/orders/edit/'+ orderID);
    });

    function clearProdItems()
    {
        while(prodItems.length > 0)
        {
            prodItems.pop();
        }

        return prodItems;
    }

    function addToBasket(value, tblName, count, orderID)
    {
        var form_data = new FormData();
        form_data.append('items', value);
        form_data.append('id', orderID);

        $.ajax({
            url: webURL + '/orders/add/items',
            type: 'POST',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            success: function (response) {
                if(response.status == 'ok')
                {
                    tblName.ajax.reload();
                    $('#totalAmount').html(response.totalAmount.toFixed(2));
                    clearProdItems();
                    $("#selectedItems").val("");
                    $.each(response.itemIDs, function(i, v) {
                        $("input[name='Quant_"+ v +"']").val("");
                    });

                    if(count > 0)
                    {
                        $('#btnReview').attr('disabled', false);
                    }
                    else
                    {
                        $('#btnReview').attr('disabled', true);
                    }
                }
            }
        });
    }

    function updateBasket(orderItemID, qty, itemPrice, tblName, oID, action)
    {
        var form_data = new FormData();
        form_data.append('orderItemID', orderItemID);
        form_data.append('qty', qty);
        form_data.append('itemPrice', itemPrice);
        form_data.append('orderID', oID);
        form_data.append('action', action);

        $.ajax({
            url: webURL + '/orders/update/items',
            type: 'POST',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            success: function (response) {
                if(response.status == 'ok')
                {
                    tblName.ajax.reload();
                    $('#totalAmount').html(response.totalAmount.toFixed(2));
                    if(response.itemCount > 0)
                    {
                        $('#btnReview').attr('disabled', false);
                    }
                    else
                    {
                        $('#btnReview').attr('disabled', true);
                    }
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

    function updateOrderStatus(orderID, status, receiptNum, payStatus)
    {
        var form_data = new FormData();
        form_data.append('orderID', orderID);
        form_data.append('receipt', receiptNum);
        form_data.append('status', status);
        form_data.append('payStatus', payStatus);

        $.ajax({
            url: webURL + '/orders/status/update',
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
                            if(status == 'on_process')
                            {
                                $('#modal_receipt_form').modal('hide');
                            }
                            else if(status == 'delivery' || status == 'pick-up')
                            {
                                $('#modal_order_validation').modal('hide');
                            }
                            else if(status == 'cancel')
                            {
                                $('#modal_cancel_form').modal('hide');
                            }
                            $('#ordersTable').load(location.href + " #ordersTable");
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
                            $('#btnClose').removeAttr('disabled');

                            if(status == 'on_process')
                            {
                                $('#btnPunch').removeAttr('disabled');
                            }
                            else if(status == 'on_delivery')
                            {
                                $('#btnCheckOrder').removeAttr('disabled');
                            }
                        }
                    });
                }
            }
        });
    }

    function isFloat(n)
    {
        if(n % 1 === 0)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    // External table additions
    // ------------------------------

    // Add placeholder to the datatable filter option
    $('.dataTables_filter input[type=search]').attr('placeholder','Type to filter...');


    // Enable Select2 select for the length option
    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });


    var basketItemsTable = $('#modalBasketItems').DataTable({
        bJQueryUI: true,
        bFilter: false,
        bInfo: false,
        bPaginate: false,
        sDom: 't',
        "ordering": false,
        autoWidth: false,
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": webURL + "/orders/cart/item/"+$('#orderID').val(),
            "method": "POST",
            "datatype": "json",
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Processing data failed. Please report to the System Adminstator.");
            }
        },
        columns:[
            {
                render: function (data, type, row) {
                    return '<ul class="icons-list"><li><a id="btnDeleteItem" data-oid="'+ row.order_items_id+'" data-itemid="'+ row.item_id  +'" data-popup="tooltip" title="Remove" data-placement="left"><i class="icon-x"></i></a></li></ul>';
                }
            },
            { "data": "item_name" },
            { "data": "item_price" },
            {
                render: function (data, type, row) {
                    var value = row.order_items_id+'@@'+row.item_price+'@@'+row.item_id;

                    return '<input class="form-control text-center" data-oid="'+value+'" id="updateQty" name="uQty_'+row.order_items_id+'" type="text" value="' + row.qty + '">'+
                            '<input type="hidden" id="upCurQty_'+row.order_items_id+'" value="' + row.qty + '">';
                }
            },
            { "data": "total_amount" }
        ],
        "fnInitComplete": function(oSettings, json) {
            var totalRecords = json.recordsTotal;
            var amountDue = parseFloat(json.totalAmount) ;

            if(totalRecords > 0)
            {
                $('#amountDue').html(amountDue.toFixed(2));
            }
            else
            {
                $('#totalAmount').html('0.00');
            }
        }
    });

    $(document).on('show.bs.modal', '.modal', function (event) {
        var zIndex = 1040 + (10 * $('.modal:visible').length);
        $(this).css('z-index', zIndex);
        setTimeout(function() {
            $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
        }, 0);
    });

    $('body').on('click', '#btnAddManual', function(e){
        e.preventDefault();
        var error = false;
        var orderID = $('#orderID').val();
        var prodItems = $("#selectedItems").val();

        if(prodItems.length == 0)
        {
            var error = true;
            swal({
                title: "Error!",
                text: 'Please select product items to add to basket.',
                confirmButtonColor: "#EF5350",
                type: "error"
            });
        }

        if(error == false)
        {
            addToBasketNew(prodItems,orderID);

        }
    });

    function addToBasketNew(value, orderID)
    {
        var form_data = new FormData();
        form_data.append('items', value);
        form_data.append('id', orderID);

        // console.log(form_data);

        $.ajax({
            url: webURL + '/orders/add/itemsNew',
            type: 'POST',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            success: function (response) {
                if(response.status == 'ok')
                {
                    console.log(response);
                    $('#amountDue').html(response.totalAmount.toFixed(2));
                    swal({
                        title: "Success!",
                        text: response.message,
                        confirmButtonColor: "#EF5350",
                        type: "success"
                    });
                    $('#modal_order_add').modal('hide');
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


function print_pdf(doc)
{
    var iframe = document.querySelector('iframe');
    if (iframe) {
        iframe.parentNode.removeChild(iframe);
    }

    var i = document.createElement('iframe');
    i.style.display = 'none';
    i.src = doc;
    document.body.appendChild(i);
    document.querySelector('iframe').contentWindow.focus();
    document.querySelector('iframe').contentWindow.print();

    /*
    var id = 'iframe';
    var html = '<iframe id="'+id+'" src="'+doc+'" style="position:absolute; left: -10000px; top: -10000px;"></iframe>';
    $('body').append(html);
    document.getElementById(id).contentWindow.print(); */


}








