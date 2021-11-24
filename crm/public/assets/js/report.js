/* ------------------------------------------------------------------------------
*
*  # Report Module
*
*  Specific JS code additions for report page
*
*  Version: 1.0
*  Latest update: Oct. 5, 2020
*
* ---------------------------------------------------------------------------- */

$(function() {
    $.ajaxSetup({
        headers:
        { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $('body').on('change', '#optDC', function() {
        var dc = $(this).val();

        if(dc == 'all')
        {
            $('#stores').html('<option></option>');
        }
        else
        {
            var form_data = new FormData();
            form_data.append('bcode', dc);

            $.ajax({
                url: webURL + '/report/stores',
                type: 'POST',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                success: function (response) {
                    $('#stores').html(response);
                }
            });
        }
    });

    $('body').on('click', '#btnExport', function(){
        var dateRange = $('#dateRange').val();
        var dateAr = dateRange.split(' - ');
        var arrOne = dateAr[0].split('/');
        var startDate = arrOne[2]+'-'+arrOne[0]+'-'+arrOne[1];
        var arrTwo = dateAr[1].split('/');
        var endDate = arrTwo[2]+'-'+arrTwo[0]+'-'+arrTwo[1];
        var optDC     = $('#optDC').val();
        var stores    = $('#stores').val();
        var scode     = (stores.length == 0) ? 0 : stores;

        $(window.location).attr('href', webURL + '/report/export/'+startDate+'/'+endDate+'/'+optDC+'/'+scode);
    });


    $('body').on('click', '#btnReportBranchPerDay', function() {
        var params = $('#params').val();

        $(window.location).attr('href', webURL + '/report/export/branch/daily/'+params);
    });

    $('body').on('click', '#btnBranchPerStoreReport', function() {
        var params = $('#params').val();
        $(window.location).attr('href', webURL + '/report/export/branch/store/'+params);
    });

    $('body').on('click', '#btnAllStoresReport', function(){

        var daterange = $('#dateRange').val();

        var result = daterange.split(' - ');
        var dateFrom = result[0];
        var dateTo = result[1];
        var param = dateFrom + '@@' + dateTo;
        var params = btoa(param);
        $(window.location).attr('href', webURL + '/report/export/all/'+params);


    })



    $('body').on('click', '#btnStoreDailyReport', function() {
        var params = $('#params').val();

        $(window.location).attr('href', webURL + '/report/export/store/daily/'+params);
    });

    $('body').on('click', '#btnTopProducts', function(){
        var dateRange = $('#dateRange').val();
        var dateAr = dateRange.split(' - ');
        var arrOne = dateAr[0].split('/');
        var startDate = arrOne[2]+'-'+arrOne[0]+'-'+arrOne[1];
        var arrTwo = dateAr[1].split('/');
        var endDate = arrTwo[2]+'-'+arrTwo[0]+'-'+arrTwo[1];

        $(window.location).attr('href', webURL + '/report/export/products/'+startDate+'/'+endDate);
    });

    $('#modal_items_list').on('show.bs.modal', function(e) {
    	var orderID  = $(e.relatedTarget).data('oid');
        var receiptNo = ($(e.relatedTarget).data('rno').length > 0) ? $(e.relatedTarget).data('rno') : 0;
        var remoteLink = webURL + '/report/view/store/orders/details/'+orderID+'/'+receiptNo;
        $(this).find('.modal-body').load(remoteLink, function() {
        });
    });
});
