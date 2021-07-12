@extends('layout.default')
	@section('contents')    
	<style type="text/css">
		div.dataTables_scrollBody thead {           
			display: none;
		}
	</style>
	<script type="text/javascript" src="{{ asset('assets/js/customer.js') }}"></script>
    <!--- Customer index section -->
        <!-- Basic datatable -->
        <div class="panel panel-flat">
		    <div class="panel-heading">
			    <h5 class="panel-title">Customer</h5>
			</div>

			<table class="table datatable-basic" id="customerTable">
			    <thead>
				    <tr>
						<th>CUSTOMER NAME</th>
						<th>EMAIL ADDRESS</th>
						<th>MOBILE NO.</th>
                        <th>ORIGIN</th>
						<th class="text-center col-md-1">IS CONFIRM?</th>
						<th class="text-center col-md-1">STATUS</th>
						<th>ASSIGNED STORE</th>
						<th>DATE REGISTER</th>
						<th class="text-center col-md-1">ACTIONS</th>
					</tr>
				</thead>
				<tbody>
				@foreach($items as $row)
					<tr>
						<td>{{ $row['firstname'].' '.$row['lastname'] }}</td>
						<td>{{ $row['email_address'] }}</td>
						<td>{{ $row['contact_num'] }}</td>
                        <td>{{ $row['registered_from'] }}</td>
						<td class="text-center"><span class="label label-rounded {{ $row['badge_bg'] }}" style="font-size: 11px; padding: 2px 8px;">{{ $row['badge_txt'] }}</span></td>
						<td class="text-center"><span class="label label-rounded {{ $row['bg'] }}" style="font-size: 11px; padding: 2px 8px;">{{ $row['text'] }}</span></td>
						<td>{{ $row['store_name'] }}</td>
						<td>{{ date('Y-m-d H:i A', strtotime($row['date_registered'])) }}</td>
						<td class="text-center">
							<ul class="icons-list">
								@if($row['registered_from'] == 'MANUAL') :
                                <li>
									<a data-toggle='modal' data-target='#modal_edit_customer' data-cid='{{ $row['customer_id'] }}' data-popup="tooltip" title="Edit" data-placement="left">
										<i class="icon-pencil"></i>
									</a>
                                </li>
								@endif

								@if($row['registered_from'] == 'ONLINE' && $row['is_confirm'] == NULL)
								<li>
									<a data-toggle='modal' data-target='#modal_validation_form' data-cid='{{ $row['customer_id'] }}' data-aid='{{ $row['address_id'] }}' data-popup="tooltip" title="Confirm" data-placement="left">
										<i class="icon-user-check"></i>
									</a>
								</li>
								@endif

								<li>
									<a data-toggle='modal' data-target='#modal_view_address' data-aid='{{ $row['address_id'] }}' data-cid='{{ $row['customer_id'] }}' data-popup="tooltip" title="Address" data-placement="left">
										<i class="icon-pin-alt"></i>
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
		<div id="modal_edit_customer" class="modal fade" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title">Customer Information</h5>
					</div>
					<div class="modal-body"></div>
					<div class="modal-footer">
						<button type="button" class="btn bg-danger-700 btn-xs btn-raised" data-dismiss="modal">CLOSE</button>
						<button type="button" class="btn bg-primary-700 btn-xs btn-raised" id="btnUpdate">UPDATE</button>
					</div>
				</div>
			</div>
		</div>
		<!-- /new form modal -->

		<!-- New form modal -->
		<div id="modal_validation_form" class="modal fade" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog modal-xs">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title">Validation</h5>
					</div>
					<div class="modal-body"></div>
					<div class="modal-footer">
						<button type="button" class="btn bg-danger-700 btn-xs btn-raised" id="btnCloseValidationForm" data-dismiss="modal">CLOSE</button>
						<button type="button" class="btn bg-success-800 btn-xs btn-raised" id="btnValidate">VALIDATE</button>
					</div>
				</div>
			</div>
		</div>
		<!-- /new form modal -->

		<!-- New form modal -->
		<div id="modal_view_customer" class="modal fade" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title">Customer Information</h5>
					</div>
					<div class="modal-body"></div>
					<div class="modal-footer">
						<button type="button" class="btn bg-danger-700 btn-xs btn-raised" id="btnCancel">REJECT</button>
						<button type="button" class="btn bg-success-800 btn-xs btn-raised" id="btnConfirm">ACCEPT</button>
					</div>
				</div>
			</div>
		</div>
		<!-- /new form modal -->

		<!-- Customer address modal -->
		<div id="modal_view_address" class="modal fade" data-backdrop="static" data-keyboard="false">
		    <div class="modal-dialog modal-lg">
			    <div class="modal-content">
				    <div class="modal-header bg-primary">
						<h5 class="modal-title">Customer Address</h5>
					</div>
					<div class="modal-body"></div>
					<div class="modal-footer">
                        <button type="button" class="btn bg-grey btn-raised btn-xs" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
        <!-- /item cost modal -->

		<!-- New form modal -->
		<div id="modal_reject_remarks_form" class="modal fade" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title">Remarks</h5>
					</div>
					<div class="modal-body"></div>
					<div class="modal-footer">
						<button type="button" class="btn bg-danger-700 btn-xs btn-raised" data-dismiss="modal">CANCEL</button>
						<button type="button" class="btn bg-success-800 btn-xs btn-raised" id="btnSubmitRemarks">SUBMIT</button>
					</div>
				</div>
			</div>
		</div>
		<!-- /new form modal -->

		<!-- Audio -->

    <!-- End sliders index`y section -->
    @endsection