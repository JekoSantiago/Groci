@extends('layout.default')
    @section('contents')    
    <!--- Sliders index section -->

        <!-- Basic datatable -->
        <div class="panel panel-flat">
		    <div class="panel-heading">
			    <h5 class="panel-title">Banner Ads</h5>
				<div class="heading-elements">
					<button type="submit" class="btn bg-indigo btn-xs btn-raised" data-toggle="modal" data-target="#modal_add_banner">
						<i class="icon-new position-left"></i> New Banner Ad
					</button>
	            </div>
			</div>

			<table class="table datatable-basic">
			    <thead>
				    <tr>
					    <th class="col-md-4">FILE</th>
						<th class="col-md-5">BANNER AD NAME</th>
						<th class="col-md-1">LOCATION</th>
						<th class="text-center col-md-1">STATUS</th>
						<th class="text-center col-md-1">ACTIONS</th>
					</tr>
				</thead>
				<tbody>
				@foreach($items as $row)
					<tr>
						<td><img src="{{ url('storage/ad/'.$row['img_file']) }}" width="100%"></td>
						<td>{{ $row['ad_name'] }}</td>
						<td>{{ $row['location'] }}</td>
						<td class="text-center"><span class="label {{ $row['bg'] }}">{{ $row['text'] }}</span></td>
						<td class="text-center">
							<ul class="icons-list">
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					        			<i class="icon-menu9"></i>
									</a>

									<ul class="dropdown-menu dropdown-menu-right">
										<li>
										<a data-toggle='modal' data-target='#modal_edit_banner' data-aid='{{ $row['ad_id'] }}'>
												<i class="icon-pencil"></i> Edit Details
											</a>
										</li>
										<li>
											<a href="javascript: updateAdStatus('{{ $row['action'] }}', {{ $row['ad_id'] }})">
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
		<div id="modal_add_banner" class="modal fade" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title">Add Form</h5>
					</div>
					<div class="modal-body"></div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger btn-xs btn-raised" data-dismiss="modal">CANCEL</button>
						<button type="button" class="btn btn-primary btn-xs btn-raised" id="btnSaveAd">SAVE</button>
					</div>
				</div>
			</div>
		</div>
		<!-- /new form modal -->

		<!-- Edit form modal -->
		<div id="modal_edit_banner" class="modal fade" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title">Edit Form</h5>
					</div>
					<div class="modal-body"></div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger btn-xs btn-raised" data-dismiss="modal">CANCEL</button>
						<button type="button" class="btn btn-primary btn-xs btn-raised" id="btnEditAd">UPDATE</button>
					</div>
				</div>
			</div>
		</div>
		<!-- /edit form modal -->

    <!-- End sliders index`y section -->
    @endsection