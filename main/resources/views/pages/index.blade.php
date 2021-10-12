
@extends('layout.base_tpl')
    @section('contents')
    <!--- Index content section -->
    @include('pages.partials.headers')
    <section class="osahan-carousel-two border-top py-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="category-list-sidebar">
                        <div class="category-list-sidebar-header">
                            <button class="btn btn-link badge-danger" type="button" id="menuToggle">
                                All Categories <i class="mdi mdi-menu" aria-hidden="true"></i>
                            </button>
                            <input type="hidden" id="hideValue" value="1">
                        </div>
                        <div class="menuCategories">
                            <div class="category-list-sidebar-body">
                                @foreach($category as $c)
                                <div class="item">
                                    <div class="sidebar-category-item">
                                        <a href="{{ url('category/'.base64_encode($c['category_id'])) }}">
                                            <img class="img-fluid" src="{{ config('app.assets_url').'/products/small/'.$c['icons'] }}" alt="">
                                            <h6>{{ $c['category_name'] }}</h6>
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                                <div class="item">
                                    <div class="sidebar-category-item">
                                        <a href="{{ url('/sale') }}">
                                            <img class="img-fluid" src="{{ config('app.assets_url').'/products/small/1618301812.jpg' }}" alt="">
                                            <h6>SALE PRODUCTS</h6>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 shop-content">
                    <div class="carousel-slider-main text-center">
                        <div class="owl-carousel owl-carousel-slider rounded overflow-hidden shadow-sm">
                        @foreach($sliders as $s)
                            <div class="item">
                                <a href="#">
                                    <img class="img-fluid" src="{{ config('app.assets_url').'/slider/'.$s['img_file'] }}" alt="{{ $s['slider_name'] }}">
                                </a>
                            </div>
                        @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="product-items-slider section-padding" style="padding-top: 20px;">
        <div class="container"><!--
            <div class="section-header">
                <h5 class="heading-design-h5">Top Savers</h5>
            </div>   -->
            <div class="row">
                @foreach($topSaver as $row)
                <div class="col-md-3 col-xs-12 mb-4">
                    <div class="product">
                        <div class="product-header">
                            @if($row['promo'] == 1)
                            <span class="badge bg-success" style="padding: 4px 10px; font-size; 12px; color: #FFF">SALE</span>
                            @endif
                            <img class="img-fluid" src="{{ config('app.assets_url').'/products/item/'.$row['img'] }}" alt="">
                        </div>
                        <div class="product-body">
                            <h6>{{ $row['item_name'] }}</h6>
                        </div>
                        <div class="product-footer">
                            <p class="offer-price" style="margin-bottom: 5px;">
                                <span style="display: block; margin-bottom: 5px;">Php {{ (($row['promo']) == 1) ? $row['promo_price'] : $row['regular_price'] }}</span>
                                @if($row['promo'] == 1)
                                <span style="display: block; margin: 5px 0px 10px;" class="regular-price">Php {{ $row['regular_price'] }}</span>
                                @endif
                            </p>

                            <div style="display: inline-block: text-align: center;">
                                <div class="form-group">
                                    <span class="input-group-btn" style="line-height: 22px !important; display: inline-block">
                                        <button type="button" class="btnQty btn-danger bg-grey btn-number" data-type="minus" data-field="quant[{{ $row['item_id'] }}]">
                                            <span class="mdi mdi-minus"></span>
                                        </button>
                                    </span>
                                    <input type="text" style="display: inline-block" name="quant[{{ $row['item_id'] }}]" class="form-control input-number form-inp" value="1" min="1" max="{{ $row['stocks_on_hand'] - $row['pre_order_qty'] }}">
                                    <span class="input-group-btn" style="line-height: 22px !important; display: inline-block">
                                        <button type="button" class="btnQty btn-success bg-grey btn-number" data-type="plus" data-field="quant[{{ $row['item_id'] }}]">
                                            <span class="mdi mdi-plus"></span>
                                        </button>
                                    </span>
                                </div>
                            </div>
                            @if( $row['stocks_on_hand'] > 0)
                            <button type="button" class="btn btn-secondary btn-sm" id="btnAddToCart" data-value="{{ base64_encode($row['params']) }}" data-itemid="{{ $row['item_id'] }}">
                                <i class="mdi mdi-cart-outline"></i> Add To Cart
                            </button>
                            @else
                            <button type="button" disabled="disabled" class="btn bg-default" style="width: 100%">
                                OUT OF STOCK
                            </button>
                            @endif
                        </div>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    @include('pages.partials.footers')
    @include('pages.partials.modal_login')
    <!-- End index section -->
    @endsection
