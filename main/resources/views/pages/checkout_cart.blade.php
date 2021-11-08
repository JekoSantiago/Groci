<div class="col-md-4">
    <div id="myBasket">
        <div class="card" style="height: 547px;">
            <h5 class="card-header">My Cart <span class="text-secondary float-right">({{ $result['cntItems'] }} item)</span></h5>
            <div class="card-body pt-0 pr-0 pl-0 pb-0" style="overflow: auto;">

            @foreach($result['items'] as $ii)
                <div class="cart-list-product">
                    <a style="cursor: pointer;" class="float-right remove-cart" data-itemid="{{ $ii['order_temp_item_id'] }}"></a>
                    <img class="img-fluid" src="{{ config('app.assets_url').'/products/item/'.$ii['img'] }}" alt="">
                    <span class="badge badge-success" style="border: none; background: none; padding: 0px;">&nbsp;</span>
                    <h5 style="font-size: 11px !important; margin: -11px 0 5px 0">{{ $ii['item_name'] }}</h5>
                    <h6 style="font-size: 12px !important;"><strong>{{ $ii['qty'] }}</strong> x {{ $ii['item_price'] }}</h6>
                    <p class="offer-price mb-0" style="font-size: 13px;">Php {{ $ii['total_amount'] }}</p>
                </div>
            @endforeach
            </div>
            <div class="card-header">
                <div class="cart-store-details">
                    <p style="font-weight: 500">Sub Total <strong class="float-right">Php {{ number_format($result['amount'],2) }}</strong></p>
                    <p style="font-weight: 500">Delivery Charges <strong class="float-right text-danger">Php <span id='cartDelCharge'> {{ ($serviceType == 'Pick-up') ? number_format(0,2) : number_format(Session::get('deliveryCharge'),2) }}</span></strong></p>
                    <h6>Amount Due <strong class="float-right text-danger">Php <span id='cartTotal'>  {{ number_format($result['amount'] + (($serviceType == 'Pick-up') ? 0 : Session::get('deliveryCharge')), 2) }} </span></strong></h6>
                </div>
            </div>
        </div>
    </div>
</div>
