$(document).ready(function() {
    $.ajaxSetup({
        headers:
        { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    if("geolocation" in navigator)
    {
        navigator.geolocation.getCurrentPosition(allowCallback, deniedCallBack);
    }
    else
    {
        $('#storeLocation').html("GeoLocation is disabled.");
    }


    /* ---- Edit Form Modal  --------- */
    $('#store_locator_modal').on('show.bs.modal', function(e) {
    	var userLatitude  = ($('#userPositionLatitude').val() == '') ? 0 : $('#userPositionLatitude').val();
        var userLongitude = ($('#userPositionLongitude').val() == '') ? 0 : $('#userPositionLongitude').val();

        console.log(userLatitude)
        console.log(userLongitude)
        var remoteLink = webURL + '/nearest/stores/'+userLatitude+'/'+userLongitude;
        $(this).find('.modal-body').load(remoteLink, function() {
        });
    });

    $('body').on('click', '#btnSearchStore', function(){
        var keyword = $('#keywords').val();
        var form_data = new FormData();
        form_data.append('keyword', keyword);

        $.ajax({
            url: webURL + '/search/stores',
            type: 'POST',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            success: function (response) {
                $('#nearbyStores').html(response);
            }
        });
    });

    $('#store_locator_modal').on('keypress',function(e) {
        if(e.which == 13) {
            $('#btnSearchStore').click();
            return false;
        }
    });


    function allowCallback(position)
    {
        var form_data = new FormData();
        form_data.append('lat', position.coords.latitude);
        form_data.append('lon', position.coords.longitude);

        $.ajax({
            url: webURL + '/validate',
            type: 'POST',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            success: function (response) {
                $('#userPositionLatitude').val(response.latitude);
                $('#userPositionLongitude').val(response.longitude);
            }
        });
    }

    function deniedCallBack(error)
    {
        switch(error.code)
        {
            case error.PERMISSION_DENIED:
                $('#userPositionLatitude').val("");
                $('#userPositionLongitude').val("");
            break;
            case error.POSITION_UNAVAILABLE:
                $('#userPositionLatitude').val("");
                $('#userPositionLongitude').val("");
            break;
            case error.TIMEOUT:
                $('#userPositionLatitude').val("");
                $('#userPositionLongitude').val("");
            break;
            case error.UNKNOWN_ERROR:
                $('#userPositionLatitude').val("");
                $('#userPositionLongitude').val("");
            break;
        }
        //$('#storeLocation').html("GeoLocation is disabled.");
    }
});



