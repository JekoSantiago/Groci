/* ------------------------------------------------------------------------------
*
*  # Account js
*
*  Specific JS code additions for Account module
*
*  Version: 1.0
*  Latest update: Oct 11, 2020
*
* ---------------------------------------------------------------------------- */

$(document).ready(function() {
    "use strict";

    $.ajaxSetup({
        headers:
        { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $('#selectAddress').select2({
        placeholder: "* Select delivery address",
        allowClear: true,
        minimumResultsForSearch: -1
    });

    $('#deliveryAddress').select2({
        placeholder: "* Select delivery address",
        allowClear: true,
        minimumResultsForSearch: -1
    });

    $('#selectStore, #myStore').select2({
        placeholder: "* Select a store",
        allowClear: true
    });

    $('#addType, #addressType').select2({
        placeholder: "* Address type",
        allowClear: true,
        minimumResultsForSearch: -1
    });

    $('#province, #selProvince').select2({
        placeholder: "* Select province",
        allowClear: true,
        minimumResultsForSearch: -1
    });

    $('#city').select2({
        placeholder: "* Select city",
        allowClear: true,
        minimumResultsForSearch: -1,
    });

    $('#dlTimeHour, #dlTimeMin, #dlTimeAMPM, #pickTimeHour, #pickTimeMin, #pickTimeAMPM, #dlTimeHour-m, #dlTimeMin-m, #dlTimeAMPM-m, #pickTimeHour-m, #pickTimeMin-m, #pickTimeAMPM-m').select2({
        minimumResultsForSearch: -1
    });

    $('body').on('click', '#delivery', function(){
        $('#pickup').removeClass('active');
        $('#delivery').addClass('active');
        $('#deliveryContent').show();
        $('#pickupContent').hide();
        $('#transactionType').val('Delivery');
    });

    $('body').on('click', '#pickup', function(){
        $('#pickup').addClass('active');
        $('#delivery').removeClass('active');
        $('#pickupContent').show();
        $('#deliveryContent').hide();
        $('#transactionType').val('Pick-up');
    });

    $('body').on('change', 'input[name="radio-group"]', function() {
        var val = $(this).val();

        if(val == 'Delivery Later')
        {
            $('#deliveryLater').show();
            $('#deliveryNow').hide();
        }
        else
        {
            $('#deliveryNow').show();
            $('#deliveryLater').hide();
        }
    });

    /**
     * Social media buttons redirect
     */

    $('body').on('click', '#btnRedirectRegister', function(){
        var redirectLogin = $(this).data('href');

        $(window.location).attr('href', redirectLogin);
    });

    $('body').on('click', '#btnRedirectLogin', function(){
        var redirectRegister = $(this).data('href');

        $(window.location).attr('href', redirectRegister);
    });

    /**
     * Customer Login
     */

    $('body').on('click', '#btnLogin', function(e){
        e.preventDefault();
        var error = false;
        var email     = $('#email').val();
        var emailAdd  = email.trim();
        var emailReg  = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        var password  = $('#password').val();
        var transType = $('#transactionType').val();
        var addressID = $('#selectAddress').val();

        if(transType == 'Pick-up')
        {
            var schedule = $('#pickDate').val() + ' ' + $('#pickTimeHour').val() + ':' + $('#pickTimeMin').val() +' '+ $('#pickTimeAMPM').val();
            var orderType = 'Pick-up';
        }
        else
        {
            var deliverType = $('input[name="radio-group"]:checked').val();
            var schedule = (deliverType == 'Delivery Now') ? 'PROMISE TIME' : $('#deliverLaterDate').val() + ' ' + $('#dlTimeHour').val()+':'+$('#dlTimeMin').val()+' '+$('#dlTimeAMPM').val();
            var orderType = deliverType;
        }

        if(emailAdd.length == 0 || !emailReg.test(emailAdd))
        {
            var error = true;
            $('#emailError').show();
        }

        if(password.length == 0)
        {
            var error = true;
            $('#passwordError').show();
        }

        if(addressID.length == 0)
        {
            var error = true;
            $('#selectAddressError').show();
        }

        if(error == false)
        {
            var form_data = new FormData();
            form_data.append('email', email);
            form_data.append('password', password);
            form_data.append('transType', orderType);
            form_data.append('schedule', schedule);
            form_data.append('addressID', addressID);

            $.ajax({
                url: webURL + '/account/login',
                type: 'POST',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                success: function (response) {
                    if(response.status == 'success')
                    {
                        $(window.location).attr('href', webURL);
                    }
                    else
                    {
                        $('#login_error_message').html(response.message);
                        $('#loginErrorMsg').show();
                    }
                }
            });
        }
    });

    $('body').on('keyup', '#email', function() {
        $('#emailError').hide();
    });

    $('body').on('keyup', '#password', function() {
        $('#passwordError').hide();
    });

    $('body').on('change', '#selectAddress', function(){
        $('#selectAddressError').hide();
    });

    /**
     * Customer Registration
     */

    $('body').on('click', '#btnRegister', function(e) {
        e.preventDefault();
        var error = false;
        var store      = $('#selectStore').val();
        var firstName  = $('#firstName').val();
        var lastName   = $('#lastName').val();
        var email      = $('#emailAddress').val();
        var emailAdd   = email.trim();
        var contactNo  = $('#mobile_num').val();

        var addType    = $('#addType').val();
        var address    = $('#address').val();
        var city       = $('#city').val();
        var province   = $('#province').val();

        var passReg    = $('#passwordReg').val();
        var repass     = $('#repass').val();
        var emailReg   = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        var chkTerms   = $("input[name='chkTerms']").is(":checked");
        var customerID = $('#customerID').val();

        if(emailAdd.length == 0 || !emailReg.test(emailAdd))
        {
            var error = true;
            $('#emailAddressError').show();
        }

        if(contactNo.length == 0)
        {
            var error = true;
            $('#mobileNumError').show();
        }
        else
        {
            if(isNaN(contactNo))
            {
                var error = true;
                $('#mobileNumError').html('Please enter numbers only.');
                $('#mobileNumError').show();
            }
        }

        if(firstName.length == 0)
        {
            var error = true;
            $('#firstNameError').show();
        }

        if(lastName.length == 0)
        {
            var error = true;
            $('#lastNameError').show();
        }

        if(addType.length == 0)
        {
            var error = true;
            $('#typeError').show();
        }

        if(address.length == 0)
        {
            var error = true;
            $('#addressError').show();
        }

        if(city.length == 0)
        {
            var error = true;
            $('#cityError').show();
        }

        if(province.length == 0)
        {
            var error = true;
            $('#provinceError').show();
        }

        if(store.length == 0)
        {
            var error = true;
            $('#selectStoreError').show();
        }

        if(customerID.length == 0)
        {
            if(passReg.length == 0)
            {
                var error = true;
                $('#passwordRegError').show();
            }
            else
            {
                if(repass != passReg)
                {
                    var error = true;
                    $('#rePassError').show();
                }
            }
        }

        if(!chkTerms)
        {
            var error = true;
            $('#termsError').show();
        }

        if(error == false)
        {
            $('#btnRegister').prop('disabled', true);
            $('#btnRegister').html('<i class="icon-spinner4"></i> Please wait! System is processing...');
            $('#btnRedirectFB').prop('disabled', true);
            $('#btnRedirectGoogle').prop('disabled', true);
            $('#btnRedirectLogin').prop('disabled', true);

            var form_data = new FormData();
            form_data.append('store', store);
            form_data.append('firstName', firstName);
            form_data.append('lastName', lastName);
            form_data.append('email', emailAdd);
            form_data.append('mobileNum', contactNo);
            form_data.append('type', addType);
            form_data.append('address', address);
            form_data.append('city', city);
            form_data.append('province', province);
            form_data.append('password', passReg);
            form_data.append('customerID', $('#customerID').val());
            form_data.append('addressID', $('#addressID').val());
            form_data.append('landmarks', $('#landmarks').val());
            form_data.append('remarks', $('#remarks').val());

            $.ajax({
                url: webURL + '/account/register',
                type: 'POST',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                success: function (response) {

                    if(response.status == 'ok')
                    {
                        $('#firstName').val("");
                        $('#lastName').val("");
                        $('#emailAddress').val("");
                        $('#mobile_num').val("");
                        $('#passwordReg').val("");
                        $('#repass').val("");
                        $('#addType').html('<option value=""></option><option value="Home">HOME</option><option value="Office">OFFICE</option><option value="Others">OTHERS</option>');
                        $('#address').val("");
                        $('#city').val("");
                        $('#landmarks').val("");
                        $('#remarks').val("");
                        $('#province').html(response.province);
                        $('#selectStore').html(response.stores);
                        $('#firstName').removeAttr('disabled');
                        $('#lastName').removeAttr('disabled');
                        $('#mobile_num').removeAttr('disabled');
                        $('#passwordReg').removeAttr('disabled');
                        $('#repass').removeAttr('disabled');
                        $("input[name='chkTerms']:checked").prop('checked',false);
                        $('#register_success_message').html(response.message);
                        $('#regSuccessMsg').show();
                        $('#regErrorMsg').hide();
                    }
                    else
                    {
                        $('#resgister_error_message').html(response.message);
                        $('#regErrorMsg').show();
                        $('#regSuccessMsg').hide();
                    }

                    $('#btnRegister').prop('disabled', false);
                    $('#btnRegister').html('CREATE YOUR ACCOUNT');
                    $('#btnRedirectFB').prop('disabled', false);
                    $('#btnRedirectGoogle').prop('disabled', false);
                    $('#btnRedirectLogin').prop('disabled', false);
                }
            });
        }
    });

    $('body').on('change', '#selectStore', function(){
        $('#selectStoreError').hide();
    });

    $('body').on('keyup', '#firstName', function(){
        $('#firstNameError').hide();
    });

    $('body').on('keyup', '#lastName', function(){
        $('#lastNameError').hide();
    });

    $('body').on('keyup', '#emailAddress', function(){
        $('#emailAddressError').hide();
    });

    $('body').on('keyup', '#mobile_num', function(){
        $('#mobileNumError').hide();
    });

    $('body').on('change', '#addType', function(){
        $('#typeError').hide();
    });

    $('body').on('keyup', '#address', function(){
        $('#addressError').hide();
    });

    $('body').on('change', '#city', function(){
        $('#cityError').hide();
    });

    $('body').on('change', '#province', function(){
        $('#provinceError').hide();
    });

    $('body').on('keyup', '#passwordReg', function(){
        $('#passwordRegError').hide();
    });

    $('body').on('keyup', '#repass', function(){
        $('#rePassError').hide();
    });

    $('body').on('click', 'input[name="chkTerms"]', function(){
        $('#termsError').hide();
    });

    /** My Account */
    $('body').on('click', '#checkChangePass', function(){
        var isCheck = $(this).is(":checked");

        if(isCheck)
        {
            $('#newPassword').removeAttr('disabled');
            $('#reNewPassword').removeAttr('disabled');
            $('#isChangePass').val('Yes');
        }
        else
        {
            $('#newPassword').attr('disabled', 'disabled');
            $('#reNewPassword').attr('disabled', 'disabled');
            $('#isChangePass').val('No');
        }
    });

    $('body').on('click', '#btnProfileUpdate', function(e){
        e.preventDefault();
        var error = false;
        var firstname    = $('#firstname').val();
        var lastname     = $('#lastname').val();
        var mobile_no    = $('#mobile_num').val();
        var newPass      = $('#newPassword').val();
        var reNewPass    = $('#reNewPassword').val();
        var curPassword  = $('#curPassword').val();
        var isChangePass = $('#isChangePass').val();

        if(firstname.length == 0)
        {
            var error = true;
            $('#firstnameError').show();
        }

        if(lastname.length == 0)
        {
            var error = true;
            $('#lastnameError').show();
        }

        if(mobile_no.length == 0)
        {
            var error = true;
            $('#mobileError').show();
        }
        else
        {
            if(isNaN(mobile_no))
            {
                var error = true;
                $('#mobileError').html('Please enter numbers only.');
                $('#mobileError').show();
            }
        }

        if(isChangePass == 'Yes')
        {
            if(newPass.length == 0)
            {
                var error = true;
                $('#newPassError').show();
            }
            else
            {
                if(newPass != reNewPass)
                {
                    var error = true;
                    $('#reNewPassError').show();
                }
            }
        }

        if(error == false)
        {
            $('#btnProfileUpdate').prop('disabled', true);
            $('#btnProfileCancel').prop('disabled', true);
            var form_data = new FormData();
            form_data.append('firstName', firstname);
            form_data.append('lastName', lastname);
            form_data.append('mobileNum', mobile_no);
            form_data.append('password', newPass);
            form_data.append('cur_pass', curPassword);
            form_data.append('change_pass', isChangePass);
            form_data.append('customerID', $('#customerID').val());

            $.ajax({
                url: webURL + '/account/profile/update',
                type: 'POST',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                success: function (response) {

                    if(response.status == 'success')
                    {
                        $('#update_profile_success_message').html(response.message);
                        $('#regSuccessMsg').show();
                        $("#profileForm").load(location.href + " #profileForm");
                    }
                    else
                    {
                        $('#update_profile_error_message').html(response.message);
                        $('#regErrorMsg').show();
                        $('#btnProfileUpdate').prop('disabled', false);
                        $('#btnProfileCancel').prop('disabled', false);
                    }
                }
            });
        }

    });

    $('body').on('click', '#btnProfileCancel', function() {
        $(window.location).attr('href', webURL + '/');

    });

    $('#deliverLaterDate, #pickDate, #deliverLaterDate-m, #pickDate-m').daterangepicker({
        singleDatePicker: true,
        locale: {
            format: 'YYYY-MM-DD'
        },
        autoApply: true
    });

    $('body').on('click', '#delivery-m', function(){
        $('#pickup-m').removeClass('active');
        $('#delivery-m').addClass('active');
        $('#deliveryContent-m').show();
        $('#pickupContent-m').hide();
        $('#transactionType-m').val('Delivery');
    });

    $('body').on('click', '#pickup-m', function(){
        $('#pickup-m').addClass('active');
        $('#delivery-m').removeClass('active');
        $('#pickupContent-m').show();
        $('#deliveryContent-m').hide();
        $('#transactionType-m').val('Pick-up');
    });

    $('body').on('change', 'input[name="radioDeliver"]', function() {
        var val = $(this).val();

        if(val == 'Delivery Later')
        {
            $('#deliveryLater-m').show();
            $('#deliveryNow-m').hide();
        }
        else
        {
            $('#deliveryNow-m').show();
            $('#deliveryLater-m').hide();
        }
    });

    $('body').on('click', '#btnSubmit', function(e) {
        e.preventDefault();
        var error = false;
        var transType = $('#transactionType-m').val();
        var addID     = $('#deliveryAddress').val();

        if(transType == 'Pick-up')
        {
            var schedule = $('#pickDate-m').val() + ' ' + $('#pickTimeHour-m').val() + ':' + $('#pickTimeMin-m').val() +' '+ $('#pickTimeMin-m').val();
            var oType = 'Pick-up';
        }
        else
        {
            var deliverType = $('input[name="radioDeliver"]:checked').val();
            var schedule = (deliverType == 'Delivery Now') ? 'PROMISE TIME' : $('#deliverLaterDate-m').val() + ' ' + $('#dlTimeHour-m').val()+':'+$('#dlTimeMin-m').val()+' '+$('#dlTimeAMPM-m').val();
            var oType = deliverType;
        }

        if(addID.length == 0)
        {
            var error = true;
            $('#deliveryAddressError').show();
        }

        if(error == false)
        {
            var form_data = new FormData();
            form_data.append('param', $('#itemVal').val());
            form_data.append('qty', $('#Qty').val());
            form_data.append('transType', oType);
            form_data.append('schedule', schedule);
            form_data.append('addressID', addID);


            $.ajax({
                url: webURL + '/account/transaction',
                type: 'POST',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                success: function (response) {
                    if(response.status == 'ok')
                    {
                        var uriParams = $('#params').val();
                        var searched = $('#searched').val()
                        console.log(uriParams);
                        if(uriParams.length == 0)
                        {
                            $(window.location).attr('href', webURL);
                        }
                        else
                        {
                            if(uriParams == 'result')
                            {
                                $(window.location).attr('href', webURL +'/search/'+uriParams+'/'+searched);
                            }
                            else
                            {
                                $(window.location).attr('href', webURL + '/category/'+uriParams);
                            }

                        }
                    }
                    else
                    {
                        swal({
                            title: "Warning!",
                            text: response.message,
                            confirmButtonColor: "#EF5350",
                            type: "warning"
                        },
                        function(isConfirm){
                            if (isConfirm) {
                                $(window.location).attr('href', webURL);
                             }
                        });
                    }
                }
            });
        }
    });

    $('body').on('change', '#deliveryAddress', function(){
        $('#deliveryAddressError').hide();
    });

    $('body').on('change', '#province', function() {
        var province = $(this).val();
        var storeCode = $('#storeCode').val();

        if(storeCode.length == 0)
        {
            var form_data = new FormData();
            form_data.append('province', province);

            $.ajax({
                url: webURL + '/account/stores',
                type: 'POST',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                success: function (response) {
                    $('#city').html(response.city);
                    $('#selectStore').html(response.stores).change();
                }
            });
        }
    });

    $('body').on('change', '#city', function() {
        var city = $(this).val();
        var form_data = new FormData();
        form_data.append('city', city);

        $.ajax({
            url: webURL + '/stores/per-city',
            type: 'POST',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            success: function (response) {
                $('#selectStore').html(response.stores).change();
                $('#myStore').html(response.stores).change();
            }
        });
    });

    $('body').on('change', '#selProvince', function() {
        var province = $(this).val();
        var form_data = new FormData();
        form_data.append('province', province);

        $.ajax({
            url: webURL + '/account/stores',
            type: 'POST',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            success: function (response) {
                $('#city').html(response.city);
                $('#myStore').html(response.stores).change();
            }
        });
    });

    $('body').on('change', '#email', function() {
        var email = $(this).val();
        var storeCode = $('#storeCode').val();
        var form_data = new FormData();
        form_data.append('email', email);

        $.ajax({
            url: webURL + '/account/check/code',
            type: 'POST',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            success: function (response) {
                if(response.status == 'exist')
                {
                    $('#login_error_message').html(response.message);
                    $('#loginErrorMsg').show();
                }
                else
                {
                    loginData(email, storeCode);
                }
            }
        });
    });

    $('body').on('change', '#emailAddress', function() {
        var email = $(this).val();
        var storeCode = $('#storeCode').val();

        registerData(email, storeCode);
    });

    /**
     * Change store
     */
    $('body').on('change', '#customerRegStores', function() {
        var addressID = $(this).val();
        var curAddressID = $('#curAddressID').val();

        if(addressID.length != 0)
        {
            if(addressID == 'add')
            {
                $(window.location).attr('href', webURL + '/account/address');
            }
            else
            {
                if(sessBasket == "") {
                    changeStore(addressID);
                }
                else
                {
                    swal({
                        title: "Are you sure?",
                        text: "By clicking 'YES', store will be change and order cart will be reset.",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#2196f3",
                        cancelButtonColor: "#ed1c24",
                        confirmButtonText: "YES",
                        cancelButtonText: "NO",
                        closeOnConfirm: false,
                        closeOnCancel: true
                    },
                    function(isConfirm){
                        if (isConfirm) {
                            changeStore(addressID);
                        }
                        else
                        {
                            $('#customerRegStores option[value="'+curAddressID+'"]').prop('selected', true).change();
                        }
                    });
                }
            }
        }
    });

    $('body').on('click', '#btnSaveAddress', function(e){
        e.preventDefault();
        var error = false;
        var type       = $('#addressType').val();
        var address    = $('#address').val();
        var city       = $('#city').val();
        var province   = $('#selProvince').val();
        var myStore    = $('#myStore').val();
        var landmarks  = $('#landmarks').val();
        var customerID = $('#customerID').val();

        if(type.length == 0)
        {
            var error = false;
            $('#addressTypeError').show();
        }

        if(address.length == 0)
        {
            var error = true;
            $('#addressError').show();
        }

        if(city.length == 0)
        {
            var error = true;
            $('#cityError').show();
        }

        if(province.length == 0)
        {
            var error = true;
            $('#provinceError').show();
        }

        if(myStore.length == 0)
        {
            $('#selectStoreError').show();
        }

        if(error == false)
        {
            $('#btnSaveAddress').prop('disabled', true);
            $('#btnProfileCancel').prop('disabled', true);
            var form_data = new FormData();
            form_data.append('type', type);
            form_data.append('address', address);
            form_data.append('city', city);
            form_data.append('province', province);
            form_data.append('storeCode', myStore);
            form_data.append('landmarks', landmarks);
            form_data.append('customerID', customerID);

            $.ajax({
                url: webURL + '/account/address/save',
                type: 'POST',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                success: function (response) {
                    if(response.status == 'success')
                    {
                        $('#success_message').html(response.message);
                        $('#addAddressSuccessMsg').show();
                        $('#addAddressErrorMsg').hide();
                        $('#addressType').html('<option value=""></option><option value="Home">HOME</option><option value="Office">OFFICE</option><option value="Others">OTHERS</option>');
                        $("#address").val('');
                        $("#city").val('');
                        $("#landmarks").val('');
                        $("#selProvince").val('').change();
                        $("#myStore").val('').change();
                        $('#btnSaveAddress').prop('disabled', false);
                        $('#btnProfileCancel').prop('disabled', false);
                        $('#error_message').hide();
                        loadAddressList();

                    }
                    else
                    {
                        $('#error_message').html(response.message);
                        $('#addAddressSuccessMsg').hide();
                        $('#addAddressErrorMsg').show();
                        $('#btnSaveAddress').prop('disabled', false);
                        $('#btnProfileCancel').prop('disabled', false);
                    }
                }
            });
        }
    });

    $('body').on('change', '#addressType', function() {
        $('#addressTypeError').hide();
    });

    $('body').on('change', '#selProvince', function() {
        $('#provinceError').hide();
    });

    $('body').on('change', '#myStore', function() {
        $('#selectStoreError').hide();
    });

    $('body').on('click', '#btnViewDetials', function() {
        var orderID = $(this).data('value');
        var orderStatus = $(this).data('status');
        var remoteLink = webURL + '/account/order/details/'+orderID;
        $('#modal_view_order_items').modal({ show: true});
        $('#modal_view_order_items').find('.modal-body').load(remoteLink, function() {
            if(orderStatus == 'CLOSE' || orderStatus == 'CANCEL')
            {
                $('#btnReOrder').show();
                $('#btnCancelReOrder').hide();
            }
            else
            {
                $('#btnReOrder').hide();
                $('#btnCancelReOrder').show();
            }
        });
    });

    /**
     * Reset password instructions - send notification
     * @param {*} email
     */
    $('body').on('click', '#btnSendMail', function(e){
        e.preventDefault();
        var error = false;
        var email = $('#emailChangePass').val();
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        var radGroup = $('input[name="radio-group"]:checked').val();

        if(email.length == 0 || !emailReg.test(email))
        {
            var error = true;
            $('#emailChangePassError').show();
        }

        if(error == false)
        {
            $('#btnSendMail').prop('disabled', true);
            $('#btnSendMail').html('<i class="icon-spinner4"></i> Please wait! System is processing...');
            var form_data = new FormData();
            form_data.append('email', email);
            form_data.append('option', radGroup);

            $.ajax({
                url: webURL + '/account/send/notification',
                type: 'POST',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                success: function (response) {
                    if(response.status == 'success')
                    {
                        $('#emailChangePass').val('');
                        $('#forgot_success_message').html(response.message);
                        $('#forgotPassSuccessMsg').show();
                        $('#btnSendMail').prop('disabled', false);
                        $('#btnSendMail').html('SEND');
                    }
                    else
                    {
                        $('#forgot_error_message').html(response.message);
                        $('#forgotPassErrorMsg').show();
                        $('#btnSendMail').prop('disabled', false);
                        $('#btnSendMail').html('SEND');
                    }
                }
            });
        }
    });

    $('body').on('click', '#btnChangePassword', function(e){
        e.preventDefault();
        var error = false;
        var newPass = $('#newPasswordOne').val();
        var rePass  = $('#reNewPasswordOne').val();
        var custEmail = $('#customerEmail').val();
        var custID = $('#customerID').val();

        if(newPass.length == 0)
        {
            var error = true;
            $('#newPasswordOneError').show();
        }
        else
        {
            if(newPass != rePass)
            {
                var error = true;
                $('#newPasswordOneError').show();
            }
        }

        if(error == false)
        {
            $('#btnChangePassword').prop('disabled', true);
            $('#btnChangePassword').html('<i class="icon-spinner4"></i> Please wait! System is processing...');
            var form_data = new FormData();
            form_data.append('email', custEmail);
            form_data.append('id', custID);
            form_data.append('password', newPass);

            $.ajax({
                url: webURL + '/account/change-password',
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
                                $(window.location).attr('href', webURL + '/login');
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
                                $('#btnChangePassword').prop('disabled', false);
                                $('#btnChangePassword').html('CHANGE PASSWORD');
                            }
                        });
                    }
                }
            });
        }
    });

    function registerData(email, storeCode)
    {
        var form_data = new FormData();
        form_data.append('email', email);
        form_data.append('code', storeCode);

        $.ajax({
            url: webURL + '/account/details',
            type: 'POST',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            success: function (response) {
                if(response.count > 0)
                {
                    if(storeCode.length == 0) {
                        $('#mobile_num').val(response.contactNum);
                        $('#firstName').val(response.firstname);
                        $('#lastName').val(response.lastname);
                        $('#customerID').val(response.customerID);
                        $('#address').val('');
                        $('#mobile_num').attr('disabled', 'disabled');
                        $('#firstName').attr('disabled', 'disabled');
                        $('#lastName').attr('disabled', 'disabled');
                        $('#passwordReg').attr('disabled', 'disabled');
                        $('#repass').attr('disabled', 'disabled');
                        $('#mobileNumError').hide();
                        $('#firstNameError').hide();
                        $('#lastNameError').hide();
                        $('#passwordRegError').hide();
                        $('#addType').removeAttr('disabled');
                        $('#address').removeAttr('disabled');
                        $('#city').removeAttr('disabled');
                        $('#province').removeAttr('disabled');
                        $('#landmarks').removeAttr('disabled');
                        $('#selectStore').removeAttr('disabled');
                        $('#regWarningMsg').hide();
                        $('#regSuccessMsg').hide();
                        $('#regErrorMsg').hide();
                        $('#btnRegister').removeAttr('disabled', 'disabled');
                    }
                    else
                    {
                        $('#mobile_num').val(response.contactNum);
                        $('#firstName').val(response.firstname);
                        $('#lastName').val(response.lastname);
                        $('#customerID').val(response.customerID);
                        $('#address').val(response.address);
                        $('#landmarks').val(response.landmarks);
                        $('#mobile_num').attr('disabled', 'disabled');
                        $('#firstName').attr('disabled', 'disabled');
                        $('#lastName').attr('disabled', 'disabled');
                        $('#passwordReg').attr('disabled', 'disabled');
                        $('#repass').attr('disabled', 'disabled');

                        if(response.type == '')
                        {
                            $('#addType').html('<option value=""></option><option value="Home">HOME</option><option value="Office">OFFICE</option><option value="Others">OTHERS</option>');
                            $('#province').html(response.prov_id);
                            $('#addType').removeAttr('disabled');
                            $('#address').removeAttr('disabled');
                            $('#landmarks').removeAttr('disabled');
                            $('#btnRegister').removeAttr('disabled');
                            $('#regWarningMsg').hide();
                        }
                        else
                        {
                            $('#addType option[value="'+response.type+'"]').prop('selected', true).change();
                            $('#addType').attr('disabled', 'disabled');
                            $('#address').attr('disabled', 'disabled');
                            $('#landmarks').attr('disabled', 'disabled');
                            $('#btnRegister').attr('disabled', 'disabled');
                            $('#regWarningMsg').html('You have already registered with ' + response.store_name + '. You may sign-in using your registered username and password.');
                            $('#regWarningMsg').show();
                        }
                    }
                }
                else
                {
                    $('#mobile_num').val(response.contactNum)
                    $('#firstName').val(response.firstname);
                    $('#lastName').val(response.lastname);
                    $('#customerID').val(response.customerID);
                    $('#addType').html('<option value=""></option><option value="Home">HOME</option><option value="Office">OFFICE</option><option value="Others">OTHERS</option>');
                    $('#address').val('');
                    $('#landmarks').val('');
                    $('#addType').removeAttr('disabled');
                    $('#mobile_num').removeAttr('disabled');
                    $('#firstName').removeAttr('disabled');
                    $('#lastName').removeAttr('disabled');
                    $('#passwordReg').removeAttr('disabled');
                    $('#repass').removeAttr('disabled');
                    $('#mobileNumError').hide();
                    $('#firstNameError').hide();
                    $('#lastNameError').hide();
                    $('#passwordRegError').hide();
                    $('#address').removeAttr('disabled');
                    $('#landmarks').removeAttr('disabled');
                    $('#regWarningMsg').hide();
                    $('#regSuccessMsg').hide();
                    $('#regErrorMsg').hide();
                    $('#btnRegister').removeAttr('disabled');
                }
            }
        });
    }

    function loginData(email, storeCode)
    {
        var form_data = new FormData();
        form_data.append('email', email);
        form_data.append('code', storeCode);

        $.ajax({
            url: webURL + '/account/delivery/address',
            type: 'POST',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            success: function (response) {
                $('#selectAddress').html(response.address).change();

                if(storeCode != '')
                {
                    if(response.count > 0)
                    {
                        $('#selectAddress').attr('disabled', 'disabled');
                        $('#btnLogin').removeAttr('disabled');
                    }
                    else
                    {
                        $('#selectAddress').removeAttr('disabled');
                        $('#btnLogin').attr('disabled', 'disabled');
                    }
                }
            }
        });
    }

    function changeStore(addressID)
    {
        var form_data = new FormData();
        form_data.append('addID', addressID);

        $.ajax({
            url: webURL + '/account/change/store',
            type: 'POST',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            success: function (response) {
                if(response.status == 'success')
                {
                    $(window.location).attr('href', webURL);
                }
            }
        });
    }


        /******* New Modifications (Jeko) *******/

        if (top.location.pathname === '/account/address')
        {
            loadAddressList()
        }

        function loadAddressList()
        {
            $('#addrlist').empty();
            $.ajax({
                url: webURL + '/address-list',
                type: 'GET',
                dataType: 'text',
                success: function (data) {
                    $('#addrlist').html(data);
                }
            });
        }



});






