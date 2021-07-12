@extends('layout.base_tpl')
    @section('contents')  
    <!-- Checkout content -->
    @include('pages.partials.headers')

    <section class="checkout-page section-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="checkout-step">
                        <div class="accordion">
                            <div class="card">
                                <div id="collapsefour" class="collapse show">
                                    <div class="card-body">
                                        <div class="text-center">
                                            <div class="col-lg-10 col-md-10 mx-auto order-done">
                                                <i class="mdi mdi-check-circle-outline color-green"></i>
                                                <h4 class="text-secondary">Your Order has been Accepted.</h4>
                                                <p class="land">Thank you for ordering at Alfamart. Please see the order summary below.</p>
                                            </div>
                                        </div>
                                        <div class="col-md-10 mx-auto mt-4">
                                            <h3 class="mt-1 mb-3">Order ID : {{ $details[0]->order_id }}</h3>
                                            
                                            <h6 class="text-dark"><i class="mdi mdi-account-check"></i> Delivery Details :</h6>
                                            <p class="pad-left p-bottom text-dark p-fw">Name : {{ $details[0]->customer_name }}</p>
                                            <p class="pad-left p-bottom text-dark p-fw">Phone No. : {{ $details[0]->contact_num }}</p>
                                            <p class="pad-left p-bottom text-dark p-fw">Address : {{ $details[0]->address }} {{ $details[0]->city }}, {{ $details[0]->province_name }} </p>
                                            <p class="pad-left text-dark p-fw">Landmark : {{ $details[0]->landmarks }}</p>

                                            <p class="pad-left p-bottom text-dark">Transaction Type : {{ $details[0]->order_type }}</p>
                                            <p class="pad-left p-bottom text-dark">Delivery Time : {{ ($details[0]->delivery_time == 'PROMISE TIME') ? date('F j, Y', strtotime($details[0]->order_date)).' between 1pm-3pm' : $details[0]->delivery_time  }}</p>
                                            <p class="pad-left text-dark">Remarks : {{ $details[0]->remarks  }}</p>
                                            

                                            <h6 class="text-dark"><i class="mdi mdi-cash-multiple"></i> Payment Details :</h6>
                                            <p class="pad-left p-bottom text-dark p-fw">Payment Option : {{ $details[0]->payment_option }}</p>
                                            <p class="pad-left p-bottom text-dark p-fw">Amount Due : Php {{ $details[0]->order_amount }}</p>
                                            <p class="pad-left p-bottom text-dark p-fw">Cash : Php {{ $details[0]->change_for }}</p>
                                            <p class="pad-left p-bottom text-dark p-fw">Change : Php {{ $details[0]->change_for - $details[0]->order_amount }}</p>
                                            <p class="pad-left text-dark p-fs">Total Amount : {{ $details[0]->order_amount }}</p>
                                        </div> 
                                        <div class="text-center mt-5">
                                            <a href="{{ url('/') }}"><button type="button" class="btn btn-secondary mb-2 btn-lg">Return to home page</button></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div id="myBasket">
                        <div class="card" style="height: 547px;">
                            <h5 class="card-header">My Cart <span class="text-secondary float-right">({{ $data['count'] }} item)</span></h5>
                            <div class="card-body pt-0 pr-0 pl-0 pb-0" style="overflow: auto;">
                            
                            @foreach($data['items'] as $ii) 
                                <div class="cart-list-product">
                                    <img class="img-fluid" src="{{ config('app.assets_url').'/products/item/'.$ii['img'] }}" alt="">
                                    <span class="badge badge-success" style="border: none; background: none; padding: 0px;">&nbsp;</span>
                                    <h5 style="font-size: 11px !important; margin: -25px 0 5px 0">{{ $ii['item_name'] }}</h5>
                                    <h6 style="font-size: 12px !important;"><strong>{{ $ii['qty'] }}</strong> x {{ $ii['item_price'] }}</h6>
                                    <p class="offer-price mb-0" style="font-size: 13px">Php {{ $ii['total_amount'] }}</p>
                                </div>
                            @endforeach
                            </div>
                            <div class="card-header">
                                <div class="cart-store-details">
                                    <p style="font-weight: 500">Sub Total <strong class="float-right">Php {{ $data['amount'] }}</strong></p>
                                    <p style="font-weight: 500">Delivery Charges <strong class="float-right text-danger">Php {{ number_format(Session::get('deliveryCharge'), 2) }}</strong></p>
                                    <h6>Amount Due <strong class="float-right text-danger">Php {{ $details[0]->order_amount }}</strong></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('pages.partials.footers')
    <!-- End checkout content -->
    @endsection






