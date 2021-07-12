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
                <input type="text" class="form-control" readonly value="Change : PhP {{ number_format(($details[0]->change_for - $details[0]->order_amount), 2, '.', '')  }}">
                <input type="hidden" id="cashChange" value="{{ $details[0]->change_for - $details[0]->order_amount  }}">
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
        <input type="hidden" id="orderID" value="{{ $orderID }}">
    </form>
</div>


<div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
    <div class="row">
        <h6 class="text-semibold col-md-12">Order Summary - ({{ count($data['items']) }} items)</h6>
    </div>    
    <div class="row">
        <div class="table-responsive pre-scrollable" style="max-height: 212px;">
            <table class="table">
                <tbody>
                @foreach($data['items'] as $i)
                    <tr>
                        <td>{{ $i['item_name'] }}</td>
                        <td class="col-md-1">{{ $i['item_price'] }}</td>
                        <td class="col-md-1">x&nbsp;&nbsp;&nbsp;&nbsp;{{ $i['qty'] }}</td>
                        <td class="col-md-2">{{ $i['total_amount'] }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="panel-body bg-teal" style="padding: 12px 20px;">
            <div class="col-md-12 text-right text-semibold">DELIVERY CHARGE : PhP {{ number_format($data['charges'], 2, '.', '') }}</div>
            <div class="col-md-12 text-right text-semibold">SUB-TOTAL AMOUNT : PhP {{ number_format($data['amountDue'], 2, '.', '') }}</div>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<input type="hidden" id="orderID" value="{{ $orderID }}">
<input type="hidden" id="pdfDoc" value="{{ url('pdfs/'.$orderID.'.pdf') }}">
<input type="hidden" id="orderType">




