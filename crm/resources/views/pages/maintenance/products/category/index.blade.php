@extends('layout.base')
    @section('contents')    
    <!--- Sliders index section -->

        <!-- Basic datatable -->
        <div class="panel panel-flat">
		    <div class="panel-heading">
			    <h5 class="panel-title">Product Category</h5>
				<div class="heading-elements">
					<button type="submit" class="btn bg-indigo btn-xs btn-raised" data-toggle="modal" data-target="#modal_add_category">
						<i class="icon-new position-left"></i> New Category
					</button>
	            </div>
			</div>

			<table class="table datatable-basic">
			    <thead>
				    <tr>
					    <th class="col-md-1">ICON</th>
						<th>CATEGORY NAME</th>
						<th class="text-center col-md-1">STATUS</th>
						<th class="text-center col-md-1">ACTIONS</th>
					</tr>
				</thead>
				<tbody>
				@foreach($items as $row)
					@php 
						$icons = ($row['icons'] == NULL) ? 'no-image-available.png' : $row['icons'];
					@endphp
					<tr>
						<td><img src="{{ url('storage/products/small/'.$icons) }}" width="100"></td>
						<td>{{ $row['category_name'] }}</td>
						<td class="text-center"><span class="label {{ $row['bg'] }}">{{ $row['text'] }}</span></td>
						<td class="text-center">
							<ul class="icons-list">
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					        			<i class="icon-menu9"></i>
									</a>

									<ul class="dropdown-menu dropdown-menu-right">
										<li>
											<a data-toggle='modal' data-target='#modal_edit_category' data-catid='{{ $row['category_id'] }}'>
												<i class="icon-pencil"></i> Edit Details
											</a>
										</li>
										<li>
											<a href="javascript: updateCategoryStatus('{{ $row['action'] }}', {{ $row['category_id'] }})">
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
		<div id="modal_add_category" class="modal fade" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title">Add Form</h5>
					</div>
					<div class="modal-body"></div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger btn-xs btn-raised" data-dismiss="modal">CANCEL</button>
						<button type="button" class="btn btn-primary btn-xs btn-raised" id="btnSaveCategory">SAVE</button>
					</div>
				</div>
			</div>
		</div>
		<!-- /new form modal -->

		<!-- Edit form modal -->
		<div id="modal_edit_category" class="modal fade" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title">Edit Form</h5>
					</div>
					<div class="modal-body"></div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger btn-xs btn-raised" data-dismiss="modal">CANCEL</button>
						<button type="button" class="btn btn-primary btn-xs btn-raised" id="btnEditCategory">UPDATE</button>
					</div>
				</div>
			</div>
		</div>
		<!-- /edit form modal -->

    <!-- End sliders index`y section -->
    @endsection