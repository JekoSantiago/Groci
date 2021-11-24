@extends('layout.base')
    @section('contents')
    <script type="text/javascript" src="{{ asset('assets/js/report.js') }}"></script>
    <!--- Report content section -->
    <div class="row">
        <div class="col-lg-12">
            <!-- Marketing campaigns -->
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h5 class="panel-title">Orders List</h5>
                    <div class="heading-elements">
                        <button class="btn bg-success-800 btn-xs btn-raised" id="btnOrdersReport">
                            <i class="icon-download4 position-left"></i> EXPORT
                        </button>
                        <input type="hidden" id="params" value="{{ request()->segment(5) }}">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table no-wrap">
                        <thead>
                            <tr class="bg-teal">
                                <th style="width: 5%" class="text-center">ACTION</th>
                                <th style="width: 15%">STORE</th>
                                <th style="width: 10%">ORDER DATE</th>
                                <th style="width: 10%">ORDER ID</th>
                                <th style="width: 10%">ORDER STATUS</th>
                                <th style="width: 5%">RECEIPT NO.</th>
                                <th style="width: 10%">AMOUNT</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($orders as $order )

                            @endforeach
                            <tr>
                                <td class="text-center">
                                    <ul class="icons-list">
                                        <li>
											<a data-toggle='modal' data-target='#modal_items_list' data-rno= '{{ $order->receipt_num }}' data-oid='{{ $order->order_id }}' data-popup="tooltip" title="Details" data-placement="right">
												<i class="icon-eye"></i>
											</a>
                                        </li>
                                    </ul>
                                </td>
                                <td>{{ $storeName }}</td>
                                <td>{{ $order->order_date}}</td>
                                <td>{{ $order->order_id }}</td>
                                <td>{{ $order->order_status }}</td>
                                <td>{{ ($order->receipt_num) ? : '-' }}</td>
                                <td>{{ number_format($order->order_amount, 2, '.', '') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
            <!-- /marketing campaigns -->
        </div>
    </div>
    <!-- End report section -->

    		<!-- Item list modal -->
		<div id="modal_items_list" class="modal fade" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title">Items List</h5>
					</div>
					<div class="modal-body">
                    </div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger btn-xs btn-raised" data-dismiss="modal">CLOSE</button>
					</div>
				</div>
			</div>
		</div>
		<!-- /Item list modal -->
    @endsection
