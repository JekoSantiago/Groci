@extends('layout.base')
    @section('contents')
    <!--- Sliders index section -->

        <!-- Basic datatable -->
        <div class="panel panel-flat">
		    <div class="panel-heading">
			    <h5 class="panel-title">Product Items</h5>
				<div class="heading-elements">
					<button type="button" class="btn bg-danger-800 btn-xs btn-raised" id="btnPriceUpdate">
						<i class="icon-pencil position-left"></i> UPDATE PRICE
                    </button>
					<button type="button" class="btn bg-indigo btn-xs btn-raised" id="btnNew" data-toggle="modal" data-target="#modal_add_items">
						<i class="icon-new position-left"></i> NEW ITEM
					</button>
	            </div>
			</div>

            <input type="hidden" name="_token" id="globalToken" value="{{csrf_token()}}" />

			<table class="table datatable-basic">
			    <thead>
				    <tr>
						<th class="col-md-1">IMAGE FILE</th>
						<th class="col-md-1">CATEGORY</th>
					    <th class="col-md-1">SKU</th>
						<th>ITEM NAME</th>
						<th class="col-md-1 text-center">PRICE</th>
						<th class="col-md-1 text-center">PROMO PRICE</th>
						<th class="text-center col-md-1">IS PROMO?</th>
						<th class="text-center col-md-1">STATUS</th>
						<th class="col-md-2">EXCLUDED STORES</th>
						<th class="text-center col-md-1">ACTIONS</th>
					</tr>
				</thead>
				<tbody>
				@foreach($items as $row)

					<tr>
						<td><img src="{{ url('storage/products/item/'.$row['img']) }}" width="100%"></td>
						<td>{{ strtoupper($row['category_name']) }}</td>
						<td>{{ $row['sku'] }}</td>
						<td>{{ $row['item_name'] }}</td>
						<td class="text-center">{{ $row['regular_price'] }}</td>
						<td class="text-center">{{ $row['promo_price'] }}</td>
						<td class="text-center"><span class="label {{ $row['bg_color'] }}">{{ $row['stat'] }}</span></td>
						<td class="text-center"><span class="label {{ $row['bg'] }}">{{ $row['text'] }}</span></td>
						<td>{{ $row['store_name'] }}</td>
						<td class="text-center">
							<ul class="icons-list">
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					        			<i class="icon-menu9"></i>
									</a>

									<ul class="dropdown-menu dropdown-menu-right">
										<li>
											<a data-toggle='modal' data-target='#modal_edit_items' data-itemid='{{ $row['item_id'] }}'>
												<i class="icon-pencil"></i> Edit Details
											</a>
										</li>
										<li>
											<a href="javascript: updateItemStatus('{{ $row['action'] }}', {{ $row['item_id'] }});">
												<i class="{{ $row['icon'] }}"></i> {{ ucwords($row['action']) }}
											</a>
										</li>
										<li>
                                        	<a data-itemid='{{ $row['item_id'] }}' data-toggle='modal' data-target='#modal_view_item_cost'>
                                            	<i class="icon-eye"></i> View Price
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
		<div id="modal_add_items" class="modal fade" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title">Add Form</h5>
					</div>
					<div class="modal-body"></div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger btn-xs btn-raised" data-dismiss="modal">CANCEL</button>
						<button type="button" class="btn btn-primary btn-xs btn-raised" id="btnSaveItems">SAVE</button>
					</div>
				</div>
			</div>
		</div>
		<!-- /new form modal -->

		<!-- Edit form modal -->
		<div id="modal_edit_items" class="modal fade" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h5 class="modal-title">Edit Form</h5>
					</div>
					<div class="modal-body"></div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger btn-xs btn-raised" data-dismiss="modal">CANCEL</button>
						<button type="button" class="btn btn-primary btn-xs btn-raised" id="btnEditItems">UPDATE</button>
					</div>
				</div>
			</div>
		</div>
		<!-- /edit form modal -->

		<!-- Item cost modal -->
		<div id="modal_view_item_cost" class="modal fade" data-backdrop="static" data-keyboard="false">
		    <div class="modal-dialog">
			    <div class="modal-content">
				    <div class="modal-header bg-primary">
						<h5 class="modal-title">Item Cost</h5>
					</div>
					<div class="modal-body"></div>
					<div class="modal-footer">
                        <button type="button" class="btn bg-grey btn-raised btn-xs" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
        <!-- /item cost modal -->

        <!-- Add item cost modal -->
		<div id="modal_add_item_cost" class="modal fade" data-backdrop="static" data-keyboard="false">
		    <div class="modal-dialog modal-sm">
			    <div class="modal-content">
				    <div class="modal-header bg-primary">
						<h5 class="modal-title">Add Item Cost Form</h5>
					</div>
					<div class="modal-body"></div>
					<div class="modal-footer">
                        <button type="button" class="btn bg-grey btn-raised btn-xs" data-dismiss="modal">Close</button>
						<button type="button" id="btnSaveItemPrice" class="btn bg-primary-600 btn-raised btn-xs">Save</button>
					</div>
				</div>
			</div>
		</div>
        <!-- /add item cost modal -->

    <!-- End sliders index`y section -->
    @endsection
