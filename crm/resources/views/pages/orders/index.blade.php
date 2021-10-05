@extends('layout.default')
	@section('contents')
	<style type="text/css">
		div.dataTables_scrollBody thead {
			display: none;
		}
	</style>
	<script type="text/javascript"> var orderID = 0; </script>
	<script type="text/javascript" src="{{ asset('assets/js/orders.js') }}"></script>
	<!--- Order index section -->
        <!-- Basic datatable -->
        <div class="panel panel-flat">
		    <div class="panel-heading">
				<h5 class="panel-title">Order List</h5>
				<div class="heading-elements">
					<a href="{{ url('orders/form') }}" class="btn bg-primary-700 btn-xs btn-raised">
						<i class="icon-cart2 position-left"></i> New Order
                    </a>
	            </div>
			</div>

			<table class="table datatable-basic" id="ordersTable">
				<thead>
					<tr>
						<th class="col-md-1">ORDER #</th>
						<th style="width: 13%"class="text-center">CUSTOMER NAME</th>
						<th class="col-md-1 text-center">CONTACT #</th>
						<th class="col-md-1 text-center">ORDER DATE</th>
						<th style="width: 14%">ORDER STATUS</th>
						<th style="width: 6%">AMOUNT</th>
						<th style="width: 7%" class="text-center">SERVICE TYPE</th>
						<th style="width: 8%" class="text-center">PAYMENT OPTION</th>
						<th style="width: 7%" class="text-center">PAYMENT STATUS</th>
						<th style="width: 7%" class="text-center">DELIVERY TIME</th>
						<th style="width: 5%">ORIGIN</th>
						<th style="width: 8%" class="text-center">ACTIONS</th>
					</tr>
				</thead>
				<tbody>
				@foreach($items as $row)
					<tr>
						<td>{{ $row['order_id'] }}</td>
						<td class="text-center">{{ $row['customer_name'] }}</td>
						<td class="text-center">{{ $row['contact_num'] }}</td>
						<td class="text-center">{{ $row['order_date'] }}</td>
						<td>
							{!! App\Services\OrderServices::showOrderStatus($row['order_id'], $row['order_type']) !!}
						</td>
						<td>{{ $row['order_amount'] }}</td>
						<td class="text-center">{{ $row['order_type'] }}</td>
						<td class="text-center">{{ $row['payment_option'] }}</td>
						<td class="text-semibold text-center">{{ $row['payment_status'] }}</td>
						<td class="text-center">{{ ($row['delivery_time'] == 'PROMISE TIME') ? '1PM - 3PM' : App\Services\HelperServices::timeFormat($row['delivery_time']) }}</td>
						<td>{{ strtoupper($row['origin']) }}</td>
						<td class="text-center">
							<ul class="icons-list">
								@if($row['order_status'] == 'FLOAT' || $row['order_status'] == 'DELIVERED' || $row['order_status'] == 'CANCEL' || $row['order_status'] == 'PICKED')
								<li>
									<a data-toggle="modal" data-target="#modal_order_details" data-oid="{{ $row['order_id'] }}" data-status="{{ $row['order_status'] }}" data-popup="tooltip" title="Details" data-placement="top">
										<i class="icon-eye"></i>
									</a>
								</li>
								@endif

								{{-- @if($row['order_status'] == 'RECEIVED')
								<li>
									<a data-toggle='modal' data-target='#modal_receipt_form' data-sid='{{ $row['order_id'] }}' data-popup="tooltip" title="Punch" data-placement="left">
										<i class="icon-calculator2"></i>
									</a>
								</li>
								@endif --}}

								@if($row['order_status'] == 'ON PROCESS')
								<li>
									<a data-toggle='modal' data-target='#modal_order_validation' data-oid='{{ $row['order_id'] }}' data-ot='{{ $row['order_type'] }}' data-popup="tooltip" title="Deliver" data-placement="left">
										<i class="icon-truck"></i>
									</a>
								</li>
								@endif

								@if($row['order_status'] == 'OUT FOR DELIVERY')
								<li>
									<a id="btnOrderDelivered" data-oid='{{ $row['order_id'] }}' data-popup="tooltip" title="Delivered" data-placement="left">
										<i class="icon-thumbs-up2"></i>
									</a>
								</li>
								@endif

								@if($row['order_status'] == 'READY FOR PICK-UP')
								<li>
									<a id="btnOrderPickup" data-oid='{{ $row['order_id'] }}' data-popup="tooltip" title="Picked" data-placement="left">
										<i class="icon-store"></i>
									</a>
								</li>
								@endif

								{{-- @if($row['order_status'] == 'DELIVERED' || $row['order_status'] == 'PICKED')
								<li>
									<a id="btnOrderClosed" data-oid='{{ $row['order_id'] }}' data-popup="tooltip" title="Close" data-placement="left">
										<i class="icon-thumbs-up2"></i>
									</a>
								</li>
								@endif --}}

								@if($row['order_status'] != 'CLOSE' && $row['order_status'] != 'CANCEL')
								<li style="margin-left: 10px;">
									<a data-toggle='modal' data-target='#modal_cancel_form' data-oid='{{ $row['order_id'] }}' data-popup="tooltip" title="Cancel" data-placement="left">
										<i class="icon-cancel-square2"></i>
									</a>
								</li>
								@endif

								<li style="margin-left: 10px;">
									<a id="btnRePrint" data-oid='{{ $row['order_id'] }}' data-popup="tooltip" title="Print" data-placement="left">
										<i class="icon-printer2"></i>
									</a>
								</li>
							</ul>
						</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
		<!-- /basic datatable -->

		<!-- New form modal -->
		<div id="modal_order_details" class="modal fade" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<h5 class="modal-title">Order Details</h5>
					</div>
					<div class="modal-body"></div>
					<div class="modal-footer">
						<button type="button" class="btn bg-danger-700 btn-xs btn-raised" id="btnCloseModal" data-dismiss="modal"><i class="icon-x position-left"></i>CLOSE</button>
						<button type="button" class="btn bg-success-700 btn-xs btn-raised" id="btnReceiveOrder"><i class="icon-check position-left"></i>CONFIRM</button>
						<!--
						<button type="button" class="btn bg-indigo-700 btn-xs btn-raised" id="btnEditOrder"><i class="icon-pencil position-left"></i>EDIT</button>
						<button type="button" class="btn bg-primary-700 btn-xs btn-raised" id="btnPrintOrder"><i class="icon-printer4 position-left"></i>PRINT</button> -->
					</div>
				</div>
			</div>
		</div>
		<!-- /new form modal -->

		<!-- Edit form modal -->
		<div id="modal_receipt_form" class="modal fade" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog modal-xs">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title">Receipt Form</h5>
					</div>
					<div class="modal-body"></div>
					<div class="modal-footer text-center">
						{{-- <button type="button" class="btn bg-danger-700 btn-xs btn-raised" id="btnClose" data-dismiss="modal"><i class="icon-x position-left"></i>CLOSE</button> --}}
						<button type="button" class="btn bg-primary-700 btn-xs btn-raised" id="btnPunch"><i class="icon-database-add position-left"></i>SAVE</button>
					</div>
				</div>
			</div>
		</div>
		<!-- /edit form modal -->

		<!-- New form modal -->
		<div id="modal_order_validation" class="modal fade" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<h5 class="modal-title">Check for validation</h5>
					</div>
					<div class="modal-body"></div>
					<div class="modal-footer">
						<button type="button" class="btn bg-danger-700 btn-xs btn-raised" id="btnClose" data-dismiss="modal"><i class="icon-x position-left"></i>CLOSE</button>
						<button type="button" class="btn bg-primary-700 btn-xs btn-raised" id="btnCheckOrder"><i class="icon-check position-left"></i>SUBMIT</button>
					</div>
				</div>
			</div>
		</div>
		<!-- /new form modal -->

		<!-- Edit form modal -->
		<div id="modal_cancel_form" class="modal fade" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<h5 class="modal-title">Cancel Remarks</h5>
					</div>
					<div class="modal-body"></div>
					<div class="modal-footer">
						<button type="button" class="btn bg-danger-700 btn-xs btn-raised" data-dismiss="modal"><i class="icon-x position-left"></i>CLOSE</button>
						<button type="button" class="btn bg-primary-700 btn-xs btn-raised" id="btnOrderCancel"><i class="icon-database-add position-left"></i>CANCEL ORDER</button>
					</div>
				</div>
			</div>
		</div>
		<!-- /edit form modal -->

		<style type="text/css">
			div.dataTables_scrollBody thead {
				display: none;
			}
		</style>

    <!-- End order index`y section -->
    @endsection
