@extends('layout.default')
	@section('contents')    
	<script type="text/javascript" src="{{ asset('assets/js/inventory.js') }}"></script>
    <!--- Customer index section -->
        <!-- Basic datatable -->
        <div class="panel panel-flat">
		    <div class="panel-heading">
			    <h5 class="panel-title">Store Inventory</h5>
                <div class="heading-elements">
					<button type="button" id="btnUpdateInventory" class="btn bg-danger-700 btn-xs btn-raised">
						<i class="icon-pencil position-left"></i> UPDATE INVENTORY
                    </button>
					<input type="hidden" id="storeCode" value="{{ Session::get('LocationCode') }}">
	            </div>
			</div>

			<table class="table datatable-basic" id="customerTable">
			    <thead>
				    <tr>
						<th>CATEGORY NAME</th>
					    <th class="col-md-1">SKU</th>
						<th>ITEM NAME</th>
						<th class="text-center col-md-1">PROMO?</th>
                        <th class="text-center">PRICE</th>
						<th class="text-center">PROMO PRICE</th>
						<th class="text-center col-md-1">STOCKS ON HAND</th>
						<th class="text-center col-md-1">ACTUAL</th>
                        <th class="text-center col-md-1">PRE-ORDER</th>
                        <th>REPLENISH DATE</th>
					</tr>
				</thead>
				<tbody>
                @foreach($items as $row)
					<tr>
						<td>{{ $row['category_name'] }}</td>
						<td>{{ $row['sku'] }}</td>
						<td>{{ $row['item_name'] }}</td>
						<td class="text-center"><span class="label {{ $row['bg'] }}">{{ $row['text'] }}</span></td>
                        <td class="text-center">{{ $row['regular_price'] }}</td>
						<td class="text-center">{{ $row['promo_price'] }}</td>
						<td class="text-center">{{ $row['stocks_on_hand'] }}</td>
                        <td class="text-center">{{ $row['stocks_on_hand'] - $row['pre_order_qty'] }}</td>
						<td class="text-center">{{ $row['pre_order_qty'] }}</td>
                        <td>{{ ($row['replenish_at'] == '') ? '' : date('Y-m-d h:i:s A', strtotime($row['replenish_at'])) }}</td>
					</tr>		
				@endforeach		
				</tbody> 
		    </table>
		</div>
		<!-- /basic datatable -->

		<!-- New form modal -->
		<div id="modal_upload_price" class="modal fade" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<h5 class="modal-title">Update Item Price</h5>
					</div>
					<div class="modal-body"></div>
					<div class="modal-footer">
						<button type="button" class="btn bg-danger-700 btn-xs btn-raised" data-dismiss="modal">CLOSE</button>
						<button type="button" class="btn bg-primary-700 btn-xs btn-raised" id="btnUploadPrice">UPDATE</button>
					</div>
				</div>
			</div>
		</div>
		<!-- /new form modal -->

		<!-- New form modal -->
		<div id="modal_upload_items" class="modal fade" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<h5 class="modal-title">Update Item Quantity</h5>
					</div>
					<div class="modal-body"></div>
					<div class="modal-footer">
						<button type="button" class="btn bg-danger-700 btn-xs btn-raised" data-dismiss="modal">CLOSE</button>
						<button type="button" class="btn bg-primary-700 btn-xs btn-raised" id="btnUploadFile">UPLOAD</button>
					</div>
				</div>
			</div>
		</div>
		<!-- /new form modal -->

    <!-- End sliders index`y section -->
    @endsection