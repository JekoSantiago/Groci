    <section class="section-padding footer bg-white border-top" style="padding: 20px 0px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-2 col-md-2">
                    <h4 class="mb-1 mt-0"><a class="logo" href="{{ url('/') }}"><img src="{{ asset('img/Alfamart-logo.fw.png') }}" alt="Alfamart Delivery"></a></h4>
                </div>
                <!--
                <div class="col-lg-4 col-md-4">
                    <p class="mb-0"><a class="text-success" href="#"><i class="mdi mdi-email"></i> <span class="__cf_email__" data-cfemail="fc959d91938f9d949d92bc9b919d9590d29f9391">customercare@alfamart.com.ph</span></a></p>
                    <p class="mb-0"><a class="text-primary" href="http://sshop.Alfamart.atp.ph"><i class="mdi mdi-web"></i>http://Shop.Alfamart.atp.ph</a></p>
                </div> -->
                <div class="col-lg-10 col-md-10" style="padding-top: 7px;">
                    <ul>
                        <li style="display: inline-block; font-size: 13px; font-weight: 500;"><a href="{{ route('about') }}" target="_blank">About Us</a>&nbsp;&nbsp;|&nbsp;&nbsp;</li>
                        <li style="display: inline-block; font-size: 13px; font-weight: 500;"><a href="{{ route('privacy') }}">Data Privacy</a>&nbsp;&nbsp;|&nbsp;&nbsp;</li>
                        <li style="display: inline-block; font-size: 13px; font-weight: 500;"><a href="{{ route('tnc') }}">Terms & Condition</a>&nbsp;&nbsp;|&nbsp;&nbsp;</li>
                        <li style="display: inline-block; font-size: 13px; font-weight: 500;"><a href="{{ route('faq') }}">FAQ</a>&nbsp;&nbsp;|&nbsp;&nbsp;</li>
                        <li style="display: inline-block; font-size: 13px; font-weight: 500;"><a href="{{ route('contact') }}">Contact Us</a></li>
                    <ul>
                </div>
            </div>
        </div>
    </section>

    <section class="pt-4 pb-4 footer-bottom">
        <div class="container">
            <div class="row no-gutters">
                <div class="col-lg-12 col-sm-12">
                    <p class="mt-1 mb-0">&copy; Copyright 2021 <strong class="text-dark">Shop Alfamart</strong>. All Rights Reserved
                    <!--<br>
                        <small class="mt-0 mb-0">Made with <i class="mdi mdi-heart text-danger"></i> by <a href="#" target="_blank" class="text-primary">Alfamart IT-PM Team</a></small> -->
                    </p>
                </div><!--
                <div class="col-lg-6 col-sm-6 text-right">
                    <img alt="osahan logo" src="{{ asset('img/payment_methods.png') }}">
                </div> -->
            </div>
        </div>
    </section>
    @php
    $result = App\Services\ProductServices::showBasketItems(Session::get('orderID'), Session::get('orderBasket'), Session::get('addressID'));
    @endphp
    <div class="cart-sidebar" id="myBasket">
        <div class="cart-sidebar-header">
            <h5>
                My Cart <span class="text-warning">({{ $result['cntItems'] }} item)</span>
                <a data-toggle="offcanvas" class="float-right" href="#"><i class="mdi mdi-close"></i></a>
            </h5>
        </div>
        <div class="cart-sidebar-body">
        @if($result['items'] == NULL)
            <p>Your basket is empty</p>
        @else
            @foreach($result['items'] as $ii)

            <div class="cart-list-product">
                <a style="cursor: pointer;" class="float-right remove-cart" id="removeItem" data-itemid="{{ $ii['order_temp_item_id'] }}"><i class="mdi mdi-close" style="font-weight: 700"></i></a>
                <img class="img-fluid" src="{{ config('app.assets_url').'/products/item/'.$ii['img'] }}" alt="">
                <span class="badge badge-success" style="border: none; background: none; padding: 0px;">&nbsp;</span>
                <h5 style="font-size: 12px !important; margin: -11px 0 5px 0">{{ $ii['item_name'] }}</h5>
                <div class="float-right" style="width: 70%">
                    <div style="width: 25%; display: inline-block">
                        <input type="text" class="form-control form-inp" id="Quant" data-oid="{{ $ii['order_temp_item_id'] }}" data-value="{{ $ii['order_temp_item_id'].'@@'.$ii['item_price'] }}" name="Quant_{{ $ii['order_temp_item_id'] }}" value="{{ $ii['qty'] }}">
                        <input type="hidden" id="curQty_{{ $ii['order_temp_item_id'] }}" value="{{ $ii['qty'] }}">
                        <input type="hidden" id="actualStocks_{{ $ii['order_temp_item_id'] }}" value="{{ intval($ii['stocks_on_hand']) - intval($ii['pre_order_qty']) }}">
                    </div>
                    <div style="width: 40%; display: inline-block; font-weight: 500; font-size: 13px; color: #000">
                        x {{ $ii['item_price'] }}
                    </div>
                </div>
                <div class="float-right" style="width: 70%">
                    <p class="offer-price" style="margin: 5px 0 0; text-align: right; font-weight: 500">{{ $ii['total_amount'] }}</p>
                </div>
            </div>
            @endforeach
        @endif
        </div>
        <div class="cart-sidebar-footer">
            <div class="cart-store-details">
                <p style="font-weight: 500">Sub Total <strong class="float-right">Php {{ number_format($result['amount'], 2) }}</strong></p>
                <h6>Amount Due <strong class="float-right text-danger">Php {{ number_format($result['amount'], 2) }}</strong></h6>
            </div>
            <input type="hidden" id="minCharge" value="{{ $result['m_charge'] }}">
            <input type="hidden" id="totalAmountDue" value="{{ $result['totalAmt'] }}">
            <input type="hidden" id="cntItems" value="{{ $result['cntItems'] }}">
            <input type="hidden" id="minimumCharge" value="{{ Session::get('minimumCharge') }}">
            <a id="btnCheckOut">
                <button class="btn btn-secondary btn-lg btn-block text-center" type="button" style="margin-bottom: 10px">
                    <span class="text-center">
                        <i class="mdi mdi-cart"></i> PROCEED TO CHECKOUT
                    </span>
                </button>
            </a>
            <a href="{{ url('/') }}">
                <button class="btn btn-secondary btn-lg btn-block text-center" type="button">
                    <span class="text-center">
                        <i class="mdi mdi-basket-fill"></i> ADD MORE PRODUCTS
                    </span>
                </button>

                <input type="hidden" id="segment" value="{{ request()->segment(1) }}">
            </a>
        </div>
    </div>

    <div class="modal fade login-modal-main" id="store_locator_modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
				    <h4 class="modal-title">Nearby Stores</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>
