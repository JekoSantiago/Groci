                    <div class="navbar-nav top-categories-search-main">
                        <div class="top-categories-search" style="width: 450px; position: inherit;">
                            <form action="{{ url('search') }}" method="post">
                                @csrf
                            <div class="input-group">
                                <input class="form-control" placeholder="Search nearby store" id="keywords" type="text">
                                <span class="input-group-btn">
                                    <button class="btn btn-secondary" type="button" id="btnSearchStore"><i class="mdi mdi-file-find"></i> Search</button>
                                </span>
                            </div>
                            </form>
                        </div>
                    </div>
                    <div class="navbar-nav top-categories-search-main">
                        <div style="margin-top: 20px">
                            <a href="{{ url('/login') }}" class="btn btn-secondary">CLICK HERE TO LOG IN</a>
                        </div>
                    </div>

                    <div class="row" style="margin-top: 20px; padding-top: 15px; height: 60vh; overflow: auto; background: #eff7fa none repeat scroll 0 0;" id="nearbyStores">
                        @if(is_null($data))
                        <div class="col-lg-12"><h3>Search nearby stores.</h3></div>
                        @else
                        @foreach($data as $item)
                        <div class="col-md-6 col-xs-12 mb-4">
                            <div style="border-radius: 5px; box-shadow: 0 2px 8px 0 rgba(0, 0, 0, 0.1); margin-bottom: 16px; overflow: hidden; padding: 12px; position: relative;">
                                <h6><i aria-hidden="true" class="mdi mdi-map-marker-circle"></i> {{ $item['storeName'] }}</h6>
                                <div style="font-weight: 500; font-size: 11px; color: #000">
                                {{ strtoupper($item['city']) }}, {{ $item['province'] }}
                                </div>
                                <div class="float-right">
                                    <a href="{{ url('/register/'.$item['storeCode']) }}" class="btn btn-secondary">REGISTER</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
