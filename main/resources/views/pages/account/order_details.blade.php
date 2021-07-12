<h5>Assigned Store</h5>
<input type="hidden" id="orderID" value="{{ $orderID }}">
<div>
    <div class="form-group">
        <label>STORE NAME</label>
        <input type="text" value="{{ $details[0]->store_code.' - '. $details[0]->store_name }}" class="form-control" disabled>
    </div>

    <div class="form-group">
        <label>CANCEL REMARKS</label>
        <textarea class="form-control" disabled>{{ $details[0]->cancel_remarks }}</textarea>
    </div>

    <div class="form-group">
        <label>TRANSACTION TYPE</label>
        <input type="text" value="{{ $details[0]->order_type }}" class="form-control" disabled>
    </div>
	
    <div class="form-group">
        <label>ORDER DATE</label>
        <input type="text" value="{{ date('M j, Y', strtotime($details[0]->order_date)) }}" class="form-control" disabled>
    </div>
	
	<div class="form-group">
        <label>DELIVERY DATE</label>
        <input type="text" value="{{ date('M j, Y', strtotime($details[0]->order_date)) }} {{ ($details[0]->delivery_time == 'PROMISE TIME') ? 'between 1PM-3PM' : $details[0]->delivery_time }}" class="form-control" disabled>
    </div>

    <div class="form-group">
        <label style="display: block; margin-bottom: 8px;">ORDER STATUS</label>
        <div class="col-lg-12" >
        @php
        $x = 1;
        foreach($orderStatus as $s) :
            $text = App\Services\AccountServices::orderStatus($s->status);
            $time = (is_null($s->date_time)) ? '' : date('g:h:i A', strtotime($s->date_time));
        @endphp
        <div style="display: inline-block; padding: 5px 10px; width: 49%; border-radius: 5px; margin-bottom: 5px; background-color: #e9ecef; color: #000000; font-weight: 500;">{{ $x.'. '. $text . $time }}</div>
        @php 
        $x++;
        endforeach;
        @endphp
        <div style="clear: both;">&nbsp;</div>
        </div>
    </div>


</div>

<h5>Ordered Items</h5>
<div>
    <table class="table table-striped table-bordered" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Price</th>
                <th style="text-align: center">Qty</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orderItems as $row)
            <tr>
                <td>{{ $row->item_name }}</td>
                <td>{{ $row->item_price }}</td>
                <td style="text-align: center">{{ $row->qty }}</td>
                <td>{{ $row->total_amount }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="panel-body">
        <div class="col-md-12 text-right" style="font-weight: 500; color: #000;">TOTAL AMOUNT : PhP {{ $details[0]->order_amount }}</div>
        <div class="col-md-12 text-right" style="font-weight: 500; color: #000;">CHANGE FOR : Php {{ $details[0]->change_for }}</div>
    </div>
</div>