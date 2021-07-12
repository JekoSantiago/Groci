@extends('layout.default')
    @section('contents')    
    <script type="text/javascript"> var orderID = {{ $orderID }} </script>
    <script type="text/javascript" src="{{ asset('assets/js/orders.js') }}"></script>
    <input type="hidden" id="orderID" value="{{ $orderID }}">
    <!--- Order form section -->
        <div class="col-md-7">
            <!-- Basic datatable -->
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h5 class="panel-title">Select items below</h5>
                    <div class="heading-elements">
                        <button type="button" id="btnAddtoCart" class="btn bg-primary-700 btn-raised btn-xs">
                            <i class="icon-cart-add position-left"></i> Add to cart
                        </button>
                    </div>
                </div>

                <table class="table datatable-basic">
                    <thead>
                        <tr>
                            <th>CATEGORY</th>
                            <th>SKU</th>
                            <th>ITEM NAME</th>
                            <th>PRICE</th>
                            <th style="width: 100px !important" class="text-center">QUANTITY</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $row)
                        @php 
                            $price = (empty($row['reg_price'])) ? $row['price'] : $row['reg_price'];
                            $promo = (empty($row['reg_price'])) ? $row['is_promo'] : $row['reg_is_promo'];
                            $value = $orderID.'@@'.$row['item_id'].'@@'.$row['item_name'].'@@'.$price.'@@'.$promo;
                        @endphp
                        <tr>
                            <td>{{ strtoupper($row['category_name']) }}</td>
                            <td>{{ $row['sku'] }}</td>
                            <td>{{ $row['item_name'] }}</td>
                            <td>{{ (empty($row['reg_price'])) ? $row['price'] : $row['reg_price'] }}</td>
                            <td>
                                <input type="text" class="form-control text-center" data-params="{{ $value }}" id="Quant" name="Quant_{{ $row['item_id'] }}" min="1" max="{{ $row['stocks_on_hand'] - $row['pre_order_qty'] }}">
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <input type="hidden" id="selectedItems">
            </div>
            <!-- /basic datatable -->
        </div>

        <div class="col-md-5">
            <!-- Basic datatable -->
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h5 class="panel-title">My Basket</h5>
                    <div class="heading-elements">
                        <button type="button" disabled='disabled' id="btnReview" data-toggle="modal" data-target="#modal_review_orders" class="btn bg-primary-700 btn-raised btn-xs" data-orderid="{{ $orderID }}" >
                            <i class="icon-basket position-left"></i> View Basket
                        </button>
                    </div>
                </div>

                <div class="table-responsive pre-scrollable" style="max-height: 750px">
                    <table class="table datatable-order-items">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 10px">&nbsp;</th>
                                <th>ITEM NAME</th>
                                <th>PRICE</th>
                                <th class="text-center col-md-2">QTY</th>
                                <th>AMOUNT</th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <div class="panel-body">
                    <div class="col-md-12 text-right text-semibold">TOTAL AMOUNT : <span id="totalAmount">0.00</span></div>
                </div>
            </div>
            <!-- /basic datatable -->
        </div>

        <!-- New form modal -->
		<div id="modal_review_orders" class="modal fade" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog modal-full">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<h5 class="modal-title">View Basket</h5>
					</div>
					<div class="modal-body"></div>
					<div class="modal-footer">
                        <div id="processing_loaders" class="tex-semibold" style="font-size: 15px; display: none">
                            <i class="icon-spinner4 spinner position-left"></i> Please wait as system is processing your request
						</div>

						<button type="button" class="btn bg-danger-700 btn-xs btn-raised" id="btnCancel" ><i class="icon-x position-left"></i>CANCEL</button>
                        <button type="button" class="btn bg-grey-400 btn-xs btn-raised" id="btnReset"><i class=" icon-reset position-left"></i>RESET</button>
						<button type="button" class="btn bg-primary-700 btn-xs btn-raised" id="btnSaveOrder"><i class="icon-floppy-disk position-left"></i>SAVE ORDER</button>
                        <button type="button" class="btn btn-default btn-xs btn-raised" id="btnBack" data-dismiss="modal"><i class="icon-arrow-left13 position-left"></i>BACK</button>
                    </div>
				</div>
			</div>
		</div>
		<!-- /new form modal -->

    <!-- End order form section -->
    @endsection