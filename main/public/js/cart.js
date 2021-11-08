/* ------------------------------------------------------------------------------
*
*  # Cart js
*
*  Specific JS code additions for Shop module
*
*  Version: 1.0
*  Latest update: Oct 20, 2020
*
* ---------------------------------------------------------------------------- */

$(document).ready(function() {

    $('#SMAC').on('keypress', function(key) {
        if(key.charCode < 48 || key.charCode > 57) return false;
    });


    $('#dlTimeHour').daterangepicker({
        timePicker: true,
        defaultSelect: false,
        defaultTime:0,
        singleDatePicker:true,
        timePickerIncrement: 15,
        timePickerSeconds: false,
        locale: {
            format: 'hh:mm A'
        },
    }).on('show.daterangepicker', function (ev, picker) {
        picker.container.find(".calendar-table").hide();
    });

    $('#pickTimeHour').daterangepicker({
        timePicker: true,
        defaultSelect: false,
        defaultTime:0,
        singleDatePicker:true,
        timePickerIncrement: 15,
        timePickerSeconds: false,
        locale: {
            format: 'hh:mm A'
        },
    }).on('show.daterangepicker', function (ev, picker) {
        picker.container.find(".calendar-table").hide();
    });




    "use strict";

    $.ajaxSetup({
        headers:
        { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    /**
     * Add to cart section
     */
    $('body').on('click', '#btnAddToCart', function() {

        var val    = $(this).data('value');
        var itemID = $(this).data('itemid');
        var inpQty = $("input[name='quant["+itemID+"]']").val();

        if(isLogged == "")
        {
            $(window.location).attr('href', webURL + '/login');
        }
        else
        {
            if(sessBasket == "")
            {
                $('#trans-type-modal').modal({ show: true });
                $('#itemVal').val(val);
                $('#itemID').val(itemID);
                $('#Qty').val(inpQty);
            }
            else
            {
                addToCart(val, inpQty, itemID);
            }
        }
    });


    /**
     * Update Quantity from Basket
     * Onchange event
     */
    $('body').on('change', '#Quant', function() {
        var val = $(this).data('value');
        var d  = val.split('@@');
        var inpQty = $("input[name='Quant_"+ d[0] +"']").val();
        var curQty  = $("#curQty_"+ d[0]).val();
        var actualQty = $("#actualStocks_"+ d[0]).val();

        if(isNaN(inpQty) || inpQty <= 0 || isFloat(inpQty))
        {
            $("input[name='Quant_"+ d[0] +"']").val(curQty);
        }
        else
        {
            if(parseInt(inpQty) > parseInt(actualQty))
            {
                swal.fire({
                    text: "You’ve reached the purchase limit for this item",
                    icon: "warning",
                    timer: 2000,
                    showConfirmButton: false
                });

                $("input[name='Quant_"+ d[0] +"']").val(curQty).change();
            }
            else
            {
                updateBasket(d[0], inpQty, d[1]);
            }
        }
    });

    /**
     * Remove items in basket
     * Click event
     */
    $('body').on('click', '#removeItem', function() {
        var id = $(this).data('itemid');
        var qty = 0;
        var price = 0;
        updateBasket(id, qty, price);
    });

    /**
     * Plus/Minus Quantity
     */
    $('.btn-number').click(function(e){
        e.preventDefault();

        var fieldName = $(this).attr('data-field');
        var type      = $(this).attr('data-type');
        var input     = $("input[name='"+fieldName+"']");
        var currentVal = parseInt(input.val());
        if (!isNaN(currentVal)) {
            if(type == 'minus') {

                if(currentVal > input.attr('min')) {
                    input.val(currentVal - 1).change();
                }
                if(parseInt(input.val()) == input.attr('min')) {
                    $(this).attr('disabled', true);
                }

            } else if(type == 'plus') {

                if(currentVal < input.attr('max')) {
                    input.val(currentVal + 1).change();
                }
                if(parseInt(input.val()) == input.attr('max')) {
                    $(this).attr('disabled', true);
                }

            }
        } else {
            input.val(0);
        }
    });

    $('.input-number').focusin(function(){
       $(this).data('oldValue', $(this).val());
    });

    $('.input-number').change(function() {

        var minValue =  parseInt($(this).attr('min'));
        var maxValue =  parseInt($(this).attr('max'));
        var valueCurrent = parseInt($(this).val());

        var name = $(this).attr('name');
        if(valueCurrent >= minValue) {
            $(".btn-number[data-type='minus'][data-field='"+name+"']").removeAttr('disabled')
        } else {
            swal.fire({
                text: "You’ve reached the purchase limit for this item",
                icon: "warning",
                timer: 2000,
                showConfirmButton: false
            });

            $(this).val($(this).data('oldValue'));
        }

        if(valueCurrent <= maxValue) {
            $(".btn-number[data-type='plus'][data-field='"+name+"']").removeAttr('disabled')
        } else {
            swal.fire({
                text: "You’ve reached the purchase limit for this item",
                icon: "warning",
                timer: 2000,
                showConfirmButton: false
            });

            $(this).val($(this).data('oldValue'));
        }
    });

    $(".input-number").keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
            // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) ||
             // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
             // let it happen, don't do anything
            return;
        }

        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

    $('body').on('click', '#btnCheckOut', function() {
        var cntItems = $('#cntItems').val();
        var minCharge = $('#minimumCharge').val();
        var totalAmountDue = $('#totalAmountDue').val();

        if(cntItems > 0) {
            if(minCharge == 0)
            {
                $(window.location).attr('href', webURL + '/checkout');
            }
            else
            {
                if(parseFloat(minCharge) > parseFloat(totalAmountDue))
                {
                    swal.fire({
                        title: 'Our minimum order requirement is P'+ minCharge+'. Please add more items. ',
                        confirmButtonColor: "#EF5350",
                        icon: "warning"
                    });
                }
                else
                {
                    $(window.location).attr('href', webURL + '/checkout');
                }
            }
        }
        else
        {
            swal.fire({
                text: 'Your basket is empty. Kindly add more items..',
                confirmButtonColor: "#EF5350",
                icon: "warning"
            });
        }
    });


    $('body').on('click', '#btnSubmitOrder', function(e){
        e.preventDefault();
        var error = false;
        var amtChange   = $('#amtChange').val();
        var amtDue      = $('#amtDue').val();
        var delCharge   = $('#delCharge').val();
        var orderID     = $('#orderID').val();
        var checkTerms  = $("input[name='chkTermsConditions']").is(":checked");
        var serviceType = $('#transactionType').val();
        var smac        = $('#SMAC').val();
        var dltime       = $('#dlTimeHour').val();
        var putime       = $('#pickTimeHour').val();
        var dldate       = $('#pickDate').val();
        var dt = new Date();
        var mintime = parseInt(dt.getHours() + addtomin);


/*
        if(serviceType == 'Pick-up')
        {
            var schedule = $('#pickDate').val() + ' ' + $('#pickTimeHour').val();
            var orderType = 'Pick-up';

            if(parseInt(dldate.substring(8))==parseInt(dt.getDate()))
            {
                var hours = Number(putime.match(/^(\d+)/)[1]);
                var AMPM = putime.match(/\s(.*)$/)[1];
                if(AMPM == "PM" && hours<12) hours = hours+12;
                if(AMPM == "AM" && hours==12) hours = hours-12;
                var pHours = hours.toString();
                if(hours<10) pHours = "0" + pHours;

                if(pHours >= mintime && pHours <= maxtime)
                {
                 console.log('pasok');
                }
                else
                {
                 var error = true;
                 $('#ptimeForError').show();
                 console.log('err')
                 }
            }

        }
        else
        {
            var deliverType = $('input[name="radio-group"]:checked').val();
            var schedule = (deliverType == 'Delivery Now') ? 'PROMISE TIME' : $('#deliverLaterDate').val() + ' ' + $('#dlTimeHour').val();
            var orderType = deliverType;

            if(deliverType == 'Delivery Later')
            {
                var hours = Number(dltime.match(/^(\d+)/)[1]);
                var AMPM = dltime.match(/\s(.*)$/)[1];
                if(AMPM == "PM" && hours<12) hours = hours+12;
                if(AMPM == "AM" && hours==12) hours = hours-12;
                var dHours = hours.toString();
                if(hours<10) dHours = "0" + dHours;

                if(dHours >= mintime && dHours <= maxtime)
                {
                    console.log('pasok');
                }
                else
                {
                    var error = true;
                    $('#timeForError').show();
                    console.log('err')
                }
            }
        }
*/


        if(!checkTerms)
        {
            var error = true;
            $('#chkTermsConditionsError').show();
        }

        if(amtChange.length == 0)
        {
            var error = true;
            $('#changeForError').show();
        }
        else
        {
            if(isNaN(amtChange))
            {
                var error = true;
                $('#changeForError').html('Please enter number only');
                $('#changeForError').show();
            }
            else
            {

                if(parseFloat(amtChange) < ((serviceType == 'Pick-up') ? parseFloat(amtDue) :  parseFloat(amtDue) + parseFloat(delCharge)))
                {
                    var error = true;
                    $('#changeForError').html('You enter an invalid amount');
                    $('#changeForError').show();
                }
            }
        }

        if(smac)
        {
            if(smac.length != 16)
            {
                var error = true;smacForError
                $('#smacForError').show();
            }
        }


        if(error == false)
        {
            $('#btnCancelOrders').hide();
            $('#btnSubmitOrder').hide();
            $('#btnBackHome').hide();
            $('#processLoaders').show();

            var totalAmount = (serviceType == 'Pick-up') ? parseInt(amtDue) : parseInt(amtDue) + parseInt(delCharge) ;

            var form_data = new FormData();
            form_data.append('addressID', $('#addressID').val());
            form_data.append('type', $('#addType').val());
            form_data.append('address', $('#address').val());
            form_data.append('city', $('#city').val());
            form_data.append('province', $('#province').val());
            form_data.append('landmark', $('#landmark').val());
            form_data.append('remarks', $('#orderRemarks').val());
            form_data.append('amount', totalAmount);
            form_data.append('amtChange', $('#amtChange').val());
            form_data.append('payMethod', $('input[name="radio-group-payment"]').val());
            form_data.append('orderType', orderType);
            form_data.append('delTime', schedule);
	        form_data.append('storeCode', $('#storeCode').val());
            form_data.append('smac', smac);

            $.ajax({
                url: webURL + '/save/order',
                type: 'POST',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                success: function (response) {
                    if(response.status == 'success')
                    {
                        $(window.location).attr('href', webURL + '/success/' + orderID);
                    }
                    else
                    {
                        $('#btnCancelOrders').show();
                        $('#btnSubmitOrder').show();
                        $('#btnBackHome').show();
                        $('#processLoaders').hide();
                        $('#checkoutError').html(response.message);
                        $('#checkoutError').show();
                    }
                }
            });
        }
    });

    $('body').on('keyup', '#amtChange', function() {
        $('#changeForError').hide();
    });

    $('body').on('keyup', '#SMAC', function() {
        $('#smacForError').hide();
    });

    $('body').on('click', '.applyBtn', function() {
        $('#timeForError').hide();
        $('#ptimeForError').hide();

    });

    $('body').on('click', 'input[name="chkTermsConditions"]', function(){
        $('#chkTermsConditionsError').hide();
    });

    $('body').on('click', '#btnCancelOrders', function() {
        $('#btnCancelOrders').attr('disabled', 'disabled');
        $('#btnSubmitOrder').attr('disabled', 'disabled');
        $('#btnBackHome').attr('disabled', 'disabled');

        swal.fire({
            text: "Are you sure you want to cancel your order?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#2196f3",
            cancelButtonColor: "#ed1c24",
            confirmButtonText: "YES",
            cancelButtonText: "NO",
        }).then((isConfirm) => {
            if (isConfirm) {
                $(window.location).attr('href', webURL + '/cancel/order');
            }
            else
            {
                $('#btnCancelOrders').removeAttr('disabled');
                $('#btnSubmitOrder').removeAttr('disabled');
                $('#btnBackHome').removeAttr('disabled');
            }
        });
    });

    $('body').on('click', '#btnBackHome', function() {
        $(window.location).attr('href', webURL);
    });


    $('body').on('click', '#btnReOrder', function() {
        $('#btnReOrder').prop('disabled', true);
        $('#btnClosed').prop('disabled', true);
        var orderID = $('#orderID').val();
        var form_data = new FormData();
        form_data.append('orderID', orderID);

        $.ajax({
            url: webURL + '/re-order',
            type: 'POST',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            success: function (response) {
                if(response.status == 'success')
                {
                    $('#modal_view_order_items').modal({ show: false});
                    swal.fire({
                        title: response.message,
                        confirmButtonColor: "#EF5350",
                        icon: "success"
                    },
                    function(isConfirm){
                        if (isConfirm) {
                            $(window.location).attr('href', webURL + '/account/orders');
                        }
                    });
                }
                else
                {
                    swal.fire({
                        text: "Error!",
                        text: response.message,
                        confirmButtonColor: "#EF5350",
                        type: "error"
                    },
                    function(isConfirm){
                        if (isConfirm) {
                            $('#btnReOrder').prop('disabled', false);
                            $('#btnClosed').prop('disabled', false);
                        }
                    });
                }
            }
        });
    });

    $('body').on('click', '#btnCancelReOrder', function(){
        var orderID = $('#orderID').val();
        var form_data = new FormData();
        form_data.append('orderID', orderID);

        $('#btnCancelReOrder').prop('disabled', true);
        $('#btnClosed').prop('disabled', true);

        swal.fire({
            text: "Are you sure you want to cancel your order?",
            type:"question",
            showCancelButton: true,
            confirmButtonColor: "#2196f3",
            cancelButtonColor: "#ed1c24",
            confirmButtonText: "YES",
            cancelButtonText: "NO",
            closeOnConfirm: false,
            closeOnCancel: true
        }).then((isConfirm) => {
            if (isConfirm) {
                $.ajax({
                    url: webURL + '/cance/order',
                    type: 'POST',
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,
                    success: function (response) {
                        if(response.status == 'success')
                        {
                            $('#modal_view_order_items').modal({ show: false});
                            swal.fire({
                                text: response.message,
                                confirmButtonColor: "#EF5350",
                                icon: "success"
                            }).then((isConfirm) => {
                                if (isConfirm) {
                                    $(window.location).attr('href', webURL + '/account/orders');
                                }
                            });
                        }
                        else
                        {
                            swal.fire({
                                title: "Error!",
                                text: response.message,
                                confirmButtonColor: "#EF5350",
                                type: "error"
                            }).then((isConfirm) => {
                                if (isConfirm) {
                                    $('#btnCancelReOrder').prop('disabled', false);
                                    $('#btnClosed').prop('disabled', false);
                                }
                            });
                        }
                    }
                });
            }
            else
            {
                $('#btnCancelReOrder').prop('disabled', false);
                $('#btnClosed').prop('disabled', false);
            }
        });
    });

});

function addToCart(value, qty, id)
{
    var form_data = new FormData();
    form_data.append('param', value);
    form_data.append('qty', qty);

    $.ajax({
        url: webURL + '/add-to-cart',
        type: 'POST',
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        success: function (response) {
            swal.fire({
                text: "Item added to your basket.",
                icon: "success",
                timer: 2000,
                showConfirmButton: false
            });

            $("input[name='quant["+id+"]']").val(1);
            $("#myBasket").load(location.href + " #myBasket");
            $("#itemsCount").load(location.href + " #itemsCount");
        }
    });
}

function updateBasket(id, qty, price)
{
    var form_data = new FormData();
    form_data.append('id', id);
    form_data.append('qty', qty);
    form_data.append('price', price);

    $.ajax({
        url: webURL + '/update-basket',
        type: 'POST',
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        success: function (response) {
            $("#myBasket").load(location.href + " #myBasket");
            $("#itemsCount").load(location.href + " #itemsCount");
        }
    });
}

function isFloat(n){
    if(n % 1 === 0)
    {
        return false;
    }
    else
    {
        return true;
    }
}
