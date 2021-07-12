    <div class="navbar-top pt-2 pb-2">
        <div class="container">
            <div class="row">
                <div class="col-md-4" style="margin-top: 4px;">
                    <a class="text-white"><i class="mdi mdi-clock"></i> <span id="clock"></span></a>
                </div>
                @if(Session::get('isLogged') == true)
                <div class="col-md-8 text-right">
                    <select class="select2 form-control customer-reg-store" id="customerRegStores" style="display: inline-block; width: 50%; height: 30px;">
                        <option value="add">ADD NEW STORE</option>
                        @foreach(App\Models\Account::getCustomerAddress(NULL, Session::get('email')) as $i)
                        <option value="{{ $i->address_id }}" {{ (Session::get('addressID') == $i->address_id) ? 'selected=selected' : '' }}>{{ $i->store_name }}</option>
                        @endforeach
			            <option value="">NO SELECTED STORE</option>
                    </select>
		            <input type="hidden" value="{{ Session::get('addressID') }}" id="curAddressID">
                    <a href="{{ url('account/orders') }}" class="text-white ml-3 mr-3"><i class="mdi mdi-basket"></i> MY ORDER</a>
                    <a class="text-white"><i class="mdi mdi-account-circle"></i> {{ strtoupper(Session::get('CustomerName')) }}</a>
                </div>
                @else
                <div class="col-md-8 text-right" style="margin-top: 4px;">
                    <input type="hidden" id="userPositionLatitude">
                    <input type="hidden" id="userPositionLongitude">
                    <a href="#" class="text-white dropdown-toggle" data-toggle='modal' data-target='#store_locator_modal'>
                        <i aria-hidden="true" class="mdi mdi-map-marker-circle"></i> <span id="nearestStore">STORE LOCATOR</span> &nbsp;
                    </a>
                    <a href="{{ url('login') }}" class="text-white ml-3 mr-3"><i class="mdi mdi-lock"></i> LOG IN</a>
                    <a href="{{ url('register') }}" class="text-white"><i class="mdi mdi-account-circle"></i> REGISTER</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-light navbar-expand-lg bg-dark bg-faded osahan-menu">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('img/logo.png') }}" alt="logo">
            </a>
            <div class="navbar-collapse" id="navbarNavDropdown ">
                <div class="navbar-nav mr-auto mt-5 mt-lg-2 margin-auto top-categories-search-main">
                    <div class="top-categories-search">
                        <form action="{{ url('search') }}" method="post">
                        <div class="input-group">
                            <input class="form-control" placeholder="Search products here" name="searchKey" type="text" id = "searchItems">
                            <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
                            <span class="input-group-btn">
                                <button class="btn btn-secondary" type="submit" id ="btnSearchItem"><i class="mdi mdi-file-find"></i> Search</button>
                            </span>
                        </div>
                        </form>
                    </div>
                </div>
                @if(request()->segment(1) != 'checkout')
                <div class="my-2 my-lg-0" id="itemsCount">
                    <ul class="list-inline main-nav-right">
                        @if(Session::get('isLogged') == true)
                        <li class="list-inline-item dropdown osahan-top-dropdown">
                            <a class="btn btn-theme-round dropdown-toggle dropdown-toggle-top-user" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img alt="logo" src="{{ asset('img/no_photo.fw.png') }}">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-list-design">
                                <a href="{{ url('account/profile') }}" class="dropdown-item"><i aria-hidden="true" class="mdi mdi-account-outline"></i> My Account</a>
                                <a href="{{ url('account/orders') }}" class="dropdown-item"><i aria-hidden="true" class="mdi mdi-map-marker-circle"></i> My Order</a>
                                <a href="{{ url('logout') }}" class="dropdown-item"><i class="mdi mdi-lock"></i> Logout</a>
                            </div>
                        </li>
                        @endif
                        <li class="list-inline-item cart-btn">
                            <a data-toggle="offcanvas" class="btn btn-link border-none" data-toggle="tooltip" data-placement="bottom" data-original-title="My Cart">
                                <i class="mdi mdi-cart"></i><small class="cart-value">{{ App\Services\ProductServices::countBasketItems(Session::get('orderID'), Session::get('orderBasket'), Session::get('addressID')) }}</small>
                            </a>
                        </li>
                        @if(Session::get('isLogged') == true)
                        <li class="list-inline-item cart-btn">
                            <h6 style="font-size: 14px;">PhP <span id="runAmt">{{ App\Services\ProductServices::runningAmt(Session::get('orderID'), Session::get('orderBasket'), Session::get('addressID')) }}</span></h6>
                        </li>
                        @endif
                    </ul>
                </div>
                @endif
            </div>
        </div>
    </nav>

    <nav class="navbar navbar-expand-lg navbar-light osahan-menu-2 pad-none-mobile">
        <div class="container">
            <div class="collapse navbar-collapse" >
                <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link href="#">
                            &nbsp;&nbsp;
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
