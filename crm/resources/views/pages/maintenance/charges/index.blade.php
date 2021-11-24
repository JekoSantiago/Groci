@extends('layout.base')
    @section('contents')
    <!--- Minimu and delivery charge index section -->
    <di class="col-md-6">
        <!-- Basic datatable -->
        <div class="panel panel-flat">
		    <div class="panel-heading">
				<h5 class="panel-title">Minimum Charge</h5>
				<div class="heading-elements">
					{{-- <button type="submit" class="btn bg-indigo btn-xs btn-raised" data-toggle="modal" data-target="#modal_add_min_charge">
						<i class="icon-new position-left"></i> New Minimum Charge
					</button> --}}
	            </div>
			</div>

			<table class="table datatable-basic">
			    <thead>
				    <tr>
						<th>STORE CODE</th>
                        <th>STORE NAME</th>
					    <th>AMOUNT</th>
						<th class="text-center col-md-1">Actions</th>
					</tr>
				</thead>
				<tbody>
                @foreach($mCharge as $m)
					<tr>
                        <td>{{ $m->store_code }}</td>
                        <td>{{ $m->store_name }}</td>
						<td>{{ ($m->MinimumCharge) ? (($m->MinimumCharge == 0) ? '0.00' : $m->MinimumCharge) : '0.00' }}</td>
						<td class="text-center">
							<ul class="icons-list">
								<li class="dropdown">
                                    <a data-toggle='modal' data-target='#modal_edit_min_charge' data-itemid='{{ $m->store_id }}'>
					        			<i class="icon-pencil"></i>
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
    </di>

    <di class="col-md-6">
        <!-- Basic datatable -->
        <div class="panel panel-flat">
		    <div class="panel-heading">
				<h5 class="panel-title">Delivery Charge</h5>
				<div class="heading-elements">
					<button type="submit" class="btn bg-indigo btn-xs btn-raised" data-toggle="modal" data-target="#modal_add_del_charge">
						<i class="icon-new position-left"></i> New Delivery Charge
					</button>
	            </div>
			</div>

			<table class="table datatable-basic">
			    <thead>
				    <tr>
                        <th></th>
					    <th>AMOUNT</th>
                        <th>EFFECTIVE DATE FROM</th>
                        <th>EFFECTIVE DATE TO</th>
						<th>EXCLUDED STORE</th>
						<th class="text-center col-md-1">Actions</th>
					</tr>
				</thead>
				<tbody>
                @foreach($dCharge as $d)
					<tr>
						<td><input type="radio" {{ (date('Y-m-d') >= $d->edate_from && (date('Y-m-d') <= $d->edate_to || is_null($d->edate_to))) ? 'checked=checked' : '' }} disabled></td>
						<td>{{ ($d->dc_amount == '.00') ? '0.00' : $d->dc_amount }}</td>
						<td>{{ $d->edate_from }}</td>
                        <td>{{ ($d->edate_to == NULL) ? ($d->edate_from > date('Y-m-d')) ? 'ONWARD' : 'PRESENT' : $d->edate_to }}</td>
						<td>{{ $d->store_name }}</td>
						<td class="text-center">
							<ul class="icons-list">
								<li class="dropdown">
                                    <a data-toggle='modal' data-target='#modal_edit_del_charge' data-itemid='{{ $d->dc_id }}' @if($d->edate_to  != NULL && $d->edate_to < date('Y-m-d') ) style="display:none" @endif>
					        			<i class="icon-pencil"></i>
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
    </di>

    <!-- Minimum charge form modal -->
		<div id="modal_add_min_charge" class="modal fade" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title">Add Form</h5>
					</div>
					<div class="modal-body"></div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger btn-xs btn-raised" data-dismiss="modal">CANCEL</button>
						<button type="button" class="btn btn-primary btn-xs btn-raised" id="btnSaveMinCharge">SAVE</button>
					</div>
				</div>
			</div>
		</div>

		<div id="modal_edit_min_charge" class="modal fade" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title">Edit Form</h5>
					</div>
					<div class="modal-body"></div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger btn-xs btn-raised" data-dismiss="modal">CANCEL</button>
						<button type="button" class="btn btn-primary btn-xs btn-raised" id="btnEditMinCharge">UPDATE</button>
					</div>
				</div>
			</div>
		</div>
		<!-- /Minimum charge form modal -->

        <!-- Minimum charge form modal -->
		<div id="modal_add_del_charge" class="modal fade" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title">Add Form</h5>
					</div>
					<div class="modal-body"></div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger btn-xs btn-raised" data-dismiss="modal">CANCEL</button>
						<button type="button" class="btn btn-primary btn-xs btn-raised" id="btnSaveDelCharge">SAVE</button>
					</div>
				</div>
			</div>
		</div>

		<div id="modal_edit_del_charge" class="modal fade" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title">Edit Form</h5>
					</div>
					<div class="modal-body"></div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger btn-xs btn-raised" data-dismiss="modal">CANCEL</button>
						<button type="button" class="btn btn-primary btn-xs btn-raised" id="btnEditDelCharge">UPDATE</button>
					</div>
				</div>
			</div>
		</div>
		<!-- /Minimum charge form modal -->


    <!-- End sliders index`y section -->
    @endsection
