@extends('layout.base')
	@section('contents')    
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
						<th class="col-md-1">FIRST NAME</th>
                        <th class="col-md-1">LAST NAME</th>
						<th>EMAIL ADDRESS</th>
						<th class="col-md-1">MOBILE NO.</th>
                        <th class="col-md-1">ORIGIN</th>
						<th class="text-center col-md-1">STATUS</th>
						<th>ASSIGNED STORE</th>
						<th class="col-md-1">REGISTER ON</th>
						<th class="text-center col-md-1">ACTIONS</th>
					</tr>
				</thead>
				<tbody>
				@foreach($items as $row)
					<tr>
						<td>{{ $row['firstname'] }}
                        <td>{{ $row['lastname'] }}</td>
						<td>{{ $row['email_address'] }}</td>
						<td>{{ $row['contact_num'] }}</td>
                        <td>{{ $row['registered_from'] }}</td>
						<td class="text-center"><span class="label label-rounded {{ $row['bg'] }}" style="font-size: 11px; padding: 2px 8px;">{{ $row['text'] }}</span></td>
						<td>
                            {{ App\Services\CustomerServices::customerAssignedStore($row['email_address']) }}
                        </td>
						<td>{{ date('Y-m-d H:i A', strtotime($row['date_registered'])) }}</td>
						<td class="text-center">
							<ul class="icons-list">
								<li>
								    <a href='javascript: deleteCustomer({{ $row['customer_id'] }});' data-popup="tooltip" title="DELETE" data-placement="left">
									    <i class="icon-bin"></i>
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
						<button type="button" class="btn bg-danger-700 btn-xs btn-raised" id="btnCancel">CANCEL</button>
						<button type="button" class="btn bg-success-800 btn-xs btn-raised" id="btnConfirm">CONFIRM</button>
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

		<!-- Audio -->

    <!-- End sliders index`y section -->
    @endsection