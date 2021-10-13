
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
                            <button class="btn btn-link badge-danger" type="button" id="menuToggle" >
                                Let's Shop <i class="mdi mdi-menu" aria-hidden="true"></i>
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
                    <div class="shop-head">
                        <a href="{{ url('/') }}">
                            <span class="mdi mdi-home"></span> Home
                        </a>
                        <span class="mdi mdi-chevron-right"></span>
                        <a href="#">{{ $categoryName }}</a>
                    </div>
                    <h5 class="mb-3">{{ $categoryName }}</h5>
                    @if(count($items) == 0)
                    <h5 class="mb-3">No results found!</h5>
                    @else
                    <div class="row">
                        @foreach($items as $i)
                        <div class="col-md-3 col-xs-12 mb-4">
                            <div class="product">
                                <div class="product-header">
                                    @if($i['promo'] == 1)
                                    <span class="badge bg-success" style="padding: 4px 10px; font-size; 12px; color: #FFF">SALE</span>
                                    @endif
                                    <img class="img-fluid" src="{{ config('app.assets_url').'/products/item/'.$i['img'] }}" alt="">
                                </div>
                                <div class="product-body">
                                    <h6>{{ $i['item_name'] }}</h6>
                                </div>
                                <div class="product-footer">
                                    <p class="offer-price" style="margin-bottom: 10px;">
                                        <span style="display: block; margin-bottom: 5px;">Php {{ (($i['promo']) == 1) ? $i['promo_price'] : $i['regular_price'] }}</span>
                                        @if($i['promo'] == 1)
                                        <span style="display: block; margin: 5px 0px 10px;" class="regular-price">Php {{ $i['regular_price'] }}</span>
                                        @endif
                                    </p>
                                    <div style="display: inline-block: text-align: center;">
                                        <div class="form-group">
                                            <span class="input-group-btn" style="line-height: 22px !important; display: inline-block">
                                                <button type="button" class="btnQty btn-danger bg-grey btn-number" data-type="minus" data-field="quant[{{ $i['item_id'] }}]">
                                                    <span class="mdi mdi-minus"></span>
                                                </button>
                                            </span>
                                            <input type="text" style="display: inline-block" name="quant[{{ $i['item_id'] }}]" class="form-control input-number form-inp" value="1" min="1" max="{{ $i['stocks_on_hand'] - $i['pre_order_qty'] }}">
                                            <span class="input-group-btn" style="line-height: 22px !important; display: inline-block">
                                                <button type="button" class="btnQty btn-success bg-grey btn-number" data-type="plus" data-field="quant[{{ $i['item_id'] }}]">
                                                    <span class="mdi mdi-plus"></span>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                    @if( $i['stocks_on_hand'] > 0)
                                    <button type="button" class="btn btn-secondary btn-sm" id="btnAddToCart" data-value="{{ base64_encode($i['params']) }}" data-itemid="{{ $i['item_id'] }}">
                                        <i class="mdi mdi-cart-outline"></i> Add To Cart
                                    </button>
                                    @else
                                    <button type="button" disabled="disabled" class="btn bg-default" style="width: 100%">
                                        OUT OF STOCK
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @if(request()->segment(1) == 'category')
                    {!! $items->links('vendor.pagination.default') !!}
                    @else
                    {!! $items->links('vendor.pagination.promo') !!}
                    @endif
                    @endif
                </div>
            </div>
        </div>
    </section>

    @include('pages.partials.footers')
    @include('pages.partials.modal_login')
    <!-- End index section -->
    @endsection
