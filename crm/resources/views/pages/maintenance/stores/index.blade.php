@extends('layout.base')
    @section('contents')
    <!--- Sliders index section -->

        <!-- Basic datatable -->
        <div class="panel panel-flat">
		    <div class="panel-heading">
				<h5 class="panel-title">Stores</h5>
				<div class="heading-elements">
                    <button class="btn bg-success-800 btn-xs btn-raised" id="btnExtractStoreList">
                        <i class="icon-download4 position-left"></i> EXPORT
                    </button>
					<button type="button" class="btn bg-indigo btn-xs btn-raised" id="btnRefreshStores">
						<i class="icon-spinner4 position-left"></i> Refresh Store Lists
					</button>
				</div>
			</div>

			<table class="table datatable-stores">
			    <thead>
				    <tr>
					    <th class="col-md-1">BRANCH CODE</th>
						<th>BRANCH NAME</th>
						<th class="col-md-1">STORE CODE</th>
						<th>STORE NAME</th>
						<th>ADDRESS</th>
						<th>PROVINCE</th>
						<th>LATITUDE</th>
						<th>LONGITUDE</th>
						<th class="text-center col-md-1">STATUS</th>
						<th class="text-center col-md-1">ACTIONS</th>
					</tr>
				</thead>
				<tbody>
				@foreach($items as $row)
					<tr>
					<td>{{ $row['branch_code'] }}</td>
						<td>{{ $row['branch_name'] }}</td>
						<td>{{ $row['store_code'] }}</td>
						<td>{{ $row['store_name'] }}</td>
						<td>{{ $row['address'] }}</td>
						<td>{{ $row['province'] }}</td>
						<td>{{ $row['latitude'] }}</td>
						<td>{{ $row['longitude'] }}</td>
						<td class="text-center"><span class="label {{ $row['bg'] }}">{{ $row['text'] }}</span></td>
						<td class="text-center">
							<ul class="icons-list">
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					        			<i class="icon-menu9"></i>
									</a>

									<ul class="dropdown-menu dropdown-menu-right">
										<li>
											<a href="javascript: updateStoreStatus('{{ $row['action'] }}', {{ $row['store_id'] }});">
												<i class="{{ $row['icon'] }}"></i> {{ ucwords($row['action']) }}
											</a>
										</li>
									</ul>
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
		<div id="modal_add_store" class="modal fade" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title">Add Form</h5>
					</div>
					<div class="modal-body"></div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger btn-xs btn-raised" data-dismiss="modal">CANCEL</button>
						<button type="button" class="btn btn-primary btn-xs btn-raised" id="btnSaveStore">SAVE</button>
					</div>
				</div>
			</div>
		</div>
		<!-- /new form modal -->

		<!-- Edit form modal -->
		<div id="modal_edit_store" class="modal fade" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title">Edit Form</h5>
					</div>
					<div class="modal-body"></div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger btn-xs btn-raised" data-dismiss="modal">CANCEL</button>
						<button type="button" class="btn btn-primary btn-xs btn-raised" id="btnEditStore">UPDATE</button>
					</div>
				</div>
			</div>
		</div>
		<!-- /edit form modal -->


    <!-- End sliders index`y section -->
    @endsection
