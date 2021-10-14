<h1 class="text-semibold" style="margin: 0px;">Order ID : {{ $orderID }}</h1>

<div class="col-md-7" style="padding-left: 0px;">
    <form>
        <div class="row">
            <h6 class="text-semibold col-md-12">Delivery Details</h6>
        </div>
        <div class="row">
            <div class="form-group col-md-11">
                <input type="text" class="form-control" readonly value="Name : {{ $details[0]->customer_name }}">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-11">
                <input type="text" class="form-control" readonly value="Mobile No. : {{ $details[0]->contact_num }}">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-11">
                <input type="text" class="form-control" readonly value="Address : {{ $details[0]->address }} {{ $details[0]->city }}, {{ $details[0]->province_name }}">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-11">
                <input type="text" class="form-control" readonly value="Landmark : {{ $details[0]->landmarks }}">
            </div>
        </div>
    </form>
</div>

<div class="col-md-5" style="padding-right: 0px;">
    <form>
        <div class="row">
            <h6 class="text-semibold col-md-12">Payment Details</h6>
        </div>
        <div class="row">
            <div class="form-group col-md-11">
                <input type="text" class="form-control" readonly value="Payment Option : {{ $details[0]->payment_option }}">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-11">
                <input type="text" class="form-control" readonly value="Amount Due : PhP {{ $details[0]->order_amount }}">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-11">
                <input type="text" class="form-control" readonly value="Cash : PhP {{ $details[0]->change_for }}">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-11">
                <input type="text" class="form-control" readonly value="Change : PhP {{ number_format(($details[0]->change_for - $details[0]->order_amount), 2, '.', '')  }} ">
            </div>
        </div>
    </form>
</div>


<div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
    <form>
        <div class="row">
            <h6 class="text-semibold col-md-12">Transaction Details</h6>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <input type="text" class="form-control" readonly value="Transaction Type : {{ $details[0]->order_type }}">
            </div>
            <div class="form-group col-md-6">
                <input type="text" class="form-control" readonly value="Delivery Time : {{ ($details[0]->delivery_time == 'PROMISE TIME') ? date('F j, Y', strtotime($details[0]->order_date)).' between 1pm-3pm' : $details[0]->delivery_time  }}">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-12">
                <input type="text" class="form-control" readonly value="Remarks : {{ $details[0]->remarks }}">
            </div>
        </div>
    </form>
</div>


<div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
    <div class="row">
        <div class="col-md-10">
            <h6 class="text-semibold col-md-12">Order Summary - ({{ count($data['items']) }} items)</h6>
        </div>
        <div class="col-md-2 pl-4">
            <button style="display: none" type="button" class="btn bg-primary-700 btn-xs btn-raised" data-toggle="modal" data-target="#modal_order_add" data-oid="{{ $orderID }}" id="btnManualAdd"  data-backdrop="static" data-keyboard="false"><i class="icon-plus3 position-left"></i>ADD</button>
        </div>
    </div>
    <div class="row">
        <div class="table-responsive pre-scrollable" style="max-height: 231px;">
            <table class="table" id="modalBasketItems">
                <thead>
                    <tr>
                        <th style="width: 50px !important">&nbsp;</th>
                        <th>ITEM NAME</th>
                        <th class="col-md-1">PRICE</th>
                        <th style="width: 100px !important" class="text-center">QTY</th>
                        <th class="col-md-2">AMOUNT</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="panel-body bg-teal" style="padding: 12px 20px;">
            <div class="col-md-12 text-right text-semibold">DELIVERY CHARGE : {{ number_format($data['charges'], 2) }}</div>
            <div class="col-md-12 text-right text-semibold">SUB-TOTAL AMOUNT : <span id="amountDue"></span></div>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<input type="hidden" id="orderID" value="{{ $orderID }}">

<script type="text/javascript>">
$(function() {

    $.ajaxSetup({
        headers:
        { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
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
                    return '<ul class="icons-list"><li class="delbut" style="display:none;"><a id="btnDeleteItem" data-oid="'+ row.order_items_id+'" data-itemid="'+ row.item_id  +'" data-popup="tooltip" title="Remove" data-placement="left"><i class="icon-x"></i></a></li></ul>';
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

            $('#btnManualAdd').hide();
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

    $('body').on('change', '#updateQty', function(){
        var items = $(this).data('oid')
        var v = items.split('@@');
        var orderID = $('#orderID').val();
        var itemID  = v[2];
        var orderItemID = v[0];
        var qty     = $("input[name='uQty_"+ v[0] +"']").val();
        var curQty  = $("#upCurQty_"+  v[0]).val();
        var price   = v[1]
        var action  = 'update';

        swal({
            title: "Are you sure?",
            text: "You want to update the quantity of this item?",
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
                            basketItemsTable.ajax.reload();
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
            }
        });
    });

    $('body').on('click', '#btnDeleteItem', function() {
        var orderID = $('#orderID').val();
        var itemID  = $(this).data('itemid');
        var orderItemID  = $(this).data('oid');
        var qty     = '';
        var price   = '';
        var curQty  = '';
        var action  = 'delete';

        swal({
            title: "Are you sure?",
            text: "You want to remove this item?",
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
                            basketItemsTable.ajax.reload();
                            $('#amountDue').html(response.amount.toFixed(2));
                            swal({
                                title: "Success!",
                                text: response.message,
                                confirmButtonColor: "#EF5350",
                                type: "success"
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
    });


    $('#btnModifyOrder').on('click',function(){

        swal({
            title: "Please Advise",
            text: "Have you advised the customer about the order?",
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
                $('.delbut').show();
                $('#btnManualAdd').show();
                console.log('editopen');
            }
        });
    })

    $('#modal_order_add').on('hide.bs.modal',function(){
        basketItemsTable.ajax.reload();
        $('#btnManualAdd').hide();

    })

});


</script>




