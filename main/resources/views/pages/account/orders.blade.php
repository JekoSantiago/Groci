
@extends('layout.base_tpl')
    @section('contents') 
    <!-- Start order content -->
    @include('pages.partials.headers')
    
    <section class="account-page section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 mx-auto">
                    <div class="row no-gutters">
                        <div class="col-md-4">
                            <div class="card account-left">
                                <div class="user-profile-header">
                                    <img alt="logo" src="{{ asset('img/no-image-icon.fw.png') }}">
                                    <h5 class="mb-1 text-secondary"><strong>Hi, </strong> {{ $details[0]->firstname .' '. $details[0]->lastname }}</h5>
                                    <p>{{ $details[0]->contact_num }}</p>
                                </div>
                                <div class="list-group">
                                    <a href="{{ url('account/profile') }}" class="list-group-item list-group-item-action">
                                        <i aria-hidden="true" class="mdi mdi-account-outline"></i> My Profile
                                    </a>
                                    <a href="{{ url('account/address') }}" class="list-group-item list-group-item-action">
                                        <i aria-hidden="true" class="mdi mdi-map-marker-circle"></i> My Address
                                    </a>
                                    <a href="{{ url('account/orders') }}" class="list-group-item list-group-item-action active">
                                        <i aria-hidden="true" class="mdi mdi-format-list-bulleted"></i> Order History
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card card-body account-right">
                                <div class="widget">
                                    <div class="section-header">
                                        <h5 class="heading-design-h5">
                                            Order List
                                        </h5>
                                    </div>
                                    <div class="order-list-tabel-main">
                                        <table class="datatabel table table-striped table-bordered order-list-tabel" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>Order #</th>
                                                    <th>Date Ordered</th>
                                                    <th>Status</th>
                                                    <th>Total</th>
                                                    <th>&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($orders as $i)
                                                <tr>
                                                    <td>{{ $i['order_id'] }}</td>
                                                    <td>{{ date('F j, Y', strtotime($i['order_date'])) }}</td>
                                                    <td><span class="badge {{ $i['bg'] }}">{{ $i['order_status'] }}</span></td>
                                                    <td>{{ $i['order_amount'] }}</td>
                                                    <td class="text-center">
                                                        <a data-toggle="tooltip" data-placement="top" data-original-title="View Detail" class="btn btn-secondary btn-sm text-white" id="btnViewDetials" data-status="{{ $i['order_status'] }}" data-value="{{ $i['order_id'] }}">
                                                            <i class="mdi mdi-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- End order content -->
    <!-- New form modal -->
	<div id="modal_view_order_items" class="modal fade" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document" style="max-width: 600px !important">
		    <div class="modal-content">
			    <div class="modal-body"></div>
				<div class="modal-footer">
                    <button type="button" class="btn bg-grey btn-lg" id="btnClosed" data-dismiss="modal">CLOSE</button>
                    @if(!is_null(Session::get('addressID')))
				    <button type="button" class="btn bg-red btn-lg" id="btnCancelReOrder">CANCEL</button>
                    <button type="button" class="btn bg-blue btn-lg" id="btnReOrder">RE-ORDER</button>
                    @endif
				</div>
			</div>
		</div>
	</div>
    <!-- /new form modal -->

    @include('pages.partials.footers')

    @endsection
