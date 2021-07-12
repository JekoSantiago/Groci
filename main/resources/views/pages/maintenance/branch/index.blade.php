@extends('layout.base')
    @section('contents')    
    <!--- Sliders index section -->

        <!-- Basic datatable -->
        <div class="panel panel-flat">
		    <div class="panel-heading">
				<h5 class="panel-title">Store Branch</h5>
				<div class="heading-elements">
					<button type="button" class="btn bg-indigo btn-xs btn-raised" id="btnRefreshBranch">
						<i class="icon-spinner4 position-left"></i> Refresh Branch Lists
					</button>
	            </div>
			</div>

			<table class="table datatable-basic">
			    <thead>
				    <tr>
					    <th class="col-md-2">BRANCH CODE</th>
						<th>BRANCH NAME</th>
						<th>ADDRESS</th>
						<th class="text-center col-md-1">STATUS</th>
						<th class="text-center col-md-1">ACTION</th>
					</tr>
				</thead>
				<tbody>
				@foreach($items as $row)
					<tr>
						<td>{{ $row['branch_code'] }}</td>
						<td>{{ $row['branch_name'] }}</td>
						<td>{{ $row['address'] }}</td>
						<td class="text-center"><span class="label {{ $row['bg'] }}">{{ $row['text'] }}</span></td>
						<td class="text-center">
							<ul class="icons-list">
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					        			<i class="icon-menu9"></i>
									</a>

									<ul class="dropdown-menu dropdown-menu-right">
										<li>
											<a href="javascript: updateBranchStatus('{{ $row['action'] }}', {{ $row['branch_id'] }});">
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
		<div id="modal_add_branch" class="modal fade" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title">Add Form</h5>
					</div>
					<div class="modal-body"></div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger btn-xs btn-raised" data-dismiss="modal">CANCEL</button>
						<button type="button" class="btn btn-primary btn-xs btn-raised" id="btnSaveBranch">SAVE</button>
					</div>
				</div>
			</div>
		</div>
		<!-- /new form modal -->

		<!-- Edit form modal -->
		<div id="modal_edit_branch" class="modal fade" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title">Edit Form</h5>
					</div>
					<div class="modal-body"></div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger btn-xs btn-raised" data-dismiss="modal">CANCEL</button>
						<button type="button" class="btn btn-primary btn-xs btn-raised" id="btnEditBranch">UPDATE</button>
					</div>
				</div>
			</div>
		</div>
		<!-- /edit form modal -->

    <!-- End sliders index`y section -->
    @endsection