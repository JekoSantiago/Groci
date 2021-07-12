@extends('layout.base')
    @section('contents')    
    <!--- Report content section -->
    <script type="text/javascript" src="{{ asset('assets/js/report.js') }}"></script>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-flat">
                <div class="panel-body">
                    <form action="{{ url('report') }}" method="POST" id="frmSearchSales" >
                        @csrf
                        <div class="form-group">
                            <div class="col-lg-1">&nbsp;</div>
                            <div class="col-lg-2">
                                <label for="date_range" class="text-semibold">DATE RANGE</label>
								<input type="text" name="dateRange" id="dateRange" class="form-control daterange-basic" value="{{ $firstDate }} - {{ $lastDate }}"> 
							</div>
						</div>
                        <div class="form-group">
                            <div class="col-lg-2">
							    <label for="dc" class="text-semibold">DC NAME</label>
								<select class="bootstrap-select show-tick" data-width="100%" name="optDC" id="optDC">
                                    <option value="all" {{ ($dc == 'all') ? 'selected=selected' : '' }}>ALL DC's</option>
                                    @foreach($optBranch as $row)
                                    <option value="{{ $row->branch_code }}" {{ ($dc == $row->branch_code) ? 'selected=selected' : '' }}>{{ $row->branch_name }}</option>
                                    @endforeach
                                </select>
							</div>
						</div>
                        <div class="form-group">
                            <div class="col-lg-4">
							    <label for="dc" class="text-semibold">STORE NAME</label>
								<select class="select-store" data-placeholder="SELECT STORE" id="stores" name="stores">
                                    <option></option>
                                    @foreach($optStores as $s) 
                                    <option value="{{ $s->store_code }}" {{ ($s->store_code == $store) ? 'selected=selected' : '' }}>{{ $s->store_code.'-'.$s->store_name }}</option>
                                    @endforeach
                                </select>
							</div>
						</div>
                        <div class="form-group">
							<div class="col-lg-2">
                                <button type="submit" name="btnSubmit" class="btn btn-raised btn-block btn-xs bg-primary-800" style="margin-top: 25px">
                                    <i class="icon-spinner4 position-left"></i> DISPLAY GENERATED DATA
                                </button>
							</div>
                            <div class="col-lg-1">&nbsp;</div>
						</div>
                    </form>	
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <!-- Marketing campaigns -->
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h5 class="panel-title">Sales Transaction</h5>
                    <div class="heading-elements">
                        <button class="btn bg-success-800 btn-xs btn-raised" id="btnExport">
                            <i class="icon-download4 position-left"></i> EXPORT
                        </button>
                    </div>
                </div>

                <div class="table-responsive">

                    @if(is_null($store))
                    <table class="table no-wrap">
                        <thead>
                            <tr class="bg-teal">
                                <th style="width: 5%" class="text-center">ACTION</th>
                                <th class="col-md-2">WAREHOUSE</th>
                                <th class="col-md-2">DATE RANGE</th>
                                <th class="col-md-1 text-center">WD</th>
                                <th class="col-md-1 text-center">CS</th>
                                <th class="col-md-1 text-center">QTY</th>
                                <th class="col-md-1 text-center">NET SALES</th>
                                <th class="col-md-1 text-center">TC</th>
                                <th class="col-md-1 text-center">SPD</th>
                                <th class="col-md-1 text-center">STD</th>
                                <th class="col-md-1 text-center">APC</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                            $totalQty = 0;
                            $cntStore = 0;
                            $netSales = 0;
                            $transCount = 0;
                            $spd = 0;
                            $std = 0;
                            foreach($branch as $b) :
                            $param = App\Services\ReportServices::base64url_encode($b->branch_code.'@@'.$firstDate.'@@'.$lastDate);
                            $data = App\Services\ReportServices::overAllReport($b->branch_code, date('Y-m-d', strtotime($firstDate)), date('Y-m-d', strtotime($lastDate)));
                            $cntStore += $data['CS'];
                            $netSales += $data['AMT'];
                            $transCount += $data['TC'];
                            $spd += $data['SPD'];
                            $std += $data['STD'];
                            $totalQty += $data['QTY'];
                            $apc = ($data['STD'] == 0) ? 0 : ($data['SPD'] / $data['STD']);
                            @endphp
                            <tr>
                                <td class="text-senter">
                                    <ul class="icons-list">
                                        <li>
                                            <a href="{{ url('report/per-day/'.$param) }}" data-popup="tooltip" title="Daily" data-placement="right">
                                                <i class="icon-eye"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ url('report/per-store/'.$param) }}" data-popup="tooltip" title="Store" data-placement="right">
                                                <i class="icon-store"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </td>
                                <td>{{ $b->branch_name }}</td>
                                <td>{{ date('M j', strtotime($firstDate)) .'-'. date('M j, Y', strtotime($lastDate)) }}</td>
                                <td class="text-center">{{ count($dateRange) }}</td>
                                <td class="text-center">{{ $data['CS'] }}</td>
                                <td class="text-center">{{ (is_null($data['QTY'])) ? 0 : $data['QTY'] }}</td>
                                <td class="text-center">{{ number_format($data['AMT'], 2, '.', '') }}</td>
                                <td class="text-center">{{ $data['TC'] }}</td>
                                <td class="text-center">{{ $data['SPD'] }}</td>
                                <td class="text-center">{{ $data['STD'] }}</td>
                                <td class="text-center">{{ number_format($apc, 3, '.', '') }}</td>
                            </tr>
                            @php
                            endforeach;
                            
                            $totalAPC = ($std == 0)? 0 : ($spd / $std);
                            @endphp
                            <tr>
                                <td class="text-semibold" colspan="2">GRAND TOTAL</td>
                                <td class="text-semibold">{{ date('M j', strtotime($firstDate)) .'-'. date('M j, Y', strtotime($lastDate)) }}</td>
                                <th class="text-semibold text-center">{{ count($dateRange) }}</th>
                                <th class="text-semibold text-center">{{ $cntStore }}</th>
                                <th class="text-semibold text-center">{{ $totalQty }}</th>
                                <th class="text-semibold text-center">{{ number_format($netSales, 2, '.', '') }}</th>
                                <th class="text-semibold text-center">{{ $transCount }}</th>
                                <th class="text-semibold text-center">{{ number_format($spd, 2, '.', '') }}</th>
                                <th class="text-semibold text-center">{{ $std }}</th>
                                <th class="text-semibold text-center">{{ number_format($totalAPC, 3, '.', '') }}</th>
                            </tr>
                        </tbody>
                    </table>
                    @else
                    <table class="table no-wrap">
                        <thead>
                            <tr class="bg-teal">
                                <th style="width: 5%" class="text-center">ACTION</th>
                                <th class="width: 25%">STORE</th>
                                <th style="width: 14%">DATE RANGE</th>
                                <th style="width: 7%" class="text-center">WD</th>
                                <th style="width: 7%" class="text-center">CS</th>
                                <th style="width: 7%" class="text-center">QTY</th>
                                <th style="width: 9%" class="text-center">NET SALES</th>
                                <th style="width: 5%" class="text-center">TC</th>
                                <th style="width: 7%" class="text-center">SPD</th>
                                <th style="width: 5%" class="text-center">STD</th>
                                <th style="width: 9%" class="text-center">APC</th>
                            </tr>
                        </thead>
                        @php
                            $param = App\Services\ReportServices::base64url_encode($store.'@@'.$firstDate.'@@'.$lastDate);
                            $row  = App\Services\ReportServices::reportPerDCPerStorePerRange($store, date('Y-m-d', strtotime($firstDate)), date('Y-m-d', strtotime($lastDate)));
                        @endphp
                        <tbody>
                            <tr>
                                <td class="text-center">
                                    <ul class="icons-list">
                                        <li>
                                            <a href="{{ url('report/view/store/'.$param) }}" data-popup="tooltip" title="Daily" data-placement="right">
                                                <i class="icon-eye"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </td>
                                <td>{{ $storeName }}</td>
                                <td>{{ date('M j', strtotime($firstDate)) .'-'. date('M j, Y', strtotime($lastDate)) }}</td>
                                <td class="text-center">{{ count($dateRange) }}</td>
                                <td class="text-center">{{ $row['CS'] }}</td>
                                <td class="text-center">{{ (is_null($row['QTY'])) ? 0 : $row['QTY'] }}</td>
                                <td class="text-center">{{ (is_null($row['AMT'])) ? '0.00' : number_format($row['AMT'], 2, '.', '') }}</td>
                                <td class="text-center">{{ $row['TC'] }}</td>
                                <td class="text-center">{{ $row['SPD'] }}</td>
                                <td class="text-center">{{ $row['STD'] }}</td>
                                <td class="text-center">{{ number_format($row['APC'], 3, '.', '') }}</td>
                            </tr>
                            <tr>
                                <td class="text-semibold" colspan="2">GRAND TOTAL</td>
                                <td class="text-semibold">{{ date('M j', strtotime($firstDate)) .'-'. date('M j, Y', strtotime($lastDate)) }}</td>
                                <th class="text-semibold text-center">{{ count($dateRange) }}</th>
                                <th class="text-semibold text-center">{{ $row['CS'] }}</th>
                                <th class="text-semibold text-center">{{ (is_null($row['QTY'])) ? 0 : $row['QTY'] }}</th>
                                <th class="text-semibold text-center">{{ (is_null($row['AMT'])) ? '0.00' : number_format($row['AMT'], 2, '.', '') }}</th>
                                <th class="text-semibold text-center">{{ $row['TC'] }}</th>
                                <th class="text-semibold text-center">{{ $row['SPD'] }}</th>
                                <th class="text-semibold text-center">{{ $row['STD'] }}</th>
                                <th class="text-semibold text-center">{{ number_format($row['APC'], 3, '.', '') }}</th>
                            </tr>
                            
                        </tbody>
                    </table>
                    @endif
                </div>

                <div class="panel-body">
                    <h6 class="panel-title display-block text-semibold">LEGENDS</h6>  

                    <div class="row">
                        <div class="col-md-6">
                            <div class="col-md-6 text-semibold">WD : Working Days</div>
                            <div class="col-md-6 text-semibold">CS : Store Count</div>
                            <div class="col-md-6 text-semibold">QTY : No. of item sold</div>
                            <div class="col-md-6 text-semibold">NET SALES : Total Sales</div>
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-6 text-semibold">TC : Transaction Count</div>
                            <div class="col-md-6 text-semibold">SPD : Average sales per day</div>
                            <div class="col-md-6 text-semibold">STD : Average sales transaction per day</div>
                            <div class="col-md-6 text-semibold">APC : Average per customer</div>
                        </div>
                    </div>       
                
                </div>
            </div>
            <!-- /marketing campaigns -->
        </div>

        <div class="col-lg-12">
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h6 class="panel-title">Top 15 Products</h6>
                    <div class="heading-elements">
                        <button class="btn bg-success-800 btn-xs btn-raised" id="btnTopProducts">
                            <i class="icon-download4 position-left"></i> EXPORT
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 10px; background-color: #bbbbbb;">&nbsp;</th>
                                <th class="col-md-12 text-right" style="background-color: #bbbbbb;">&nbsp;</th>
                                <th style="width: 10px; background-color: #bbbbbb;">&nbsp;</th>
                                @php
                                $b = true;
                                foreach($dateRange as $date) :
                                @endphp
                                <th class="col-md-1 text-center {{ ($b = !$b) ? 'bg-grey-300' : 'bg-teal'}}" colspan="2">{{ date('M j, Y', strtotime($date)) }}</th>
                                @php
                                endforeach;
                                @endphp
                            </tr>
                            <tr>
                                <th class="text-center" style="background-color: #bbbbbb;">RANK</th>
                                <th style="background-color: #bbbbbb;">PRODUCT NAME</th>
                                <th class="text-center" style="background-color: #bbbbbb;">TOTAL</th>
                                @php
                                $c = true;
                                for($x=1; $x<=$cntDays; $x++) :
                                $bgc = ($c = !$c) ? 'bg-grey-300' : 'bg-teal';
                                @endphp
                                <th class="text-center {{ $bgc }}">QTY</th>
                                <th class="text-center {{ $bgc }}">AMT</th>
                                @php
                                endfor;
                                @endphp
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($top15 as $top)
                            <tr>
                                <td class="text-center">{{ $top['rank'] }}</td>
                                <td>{{ $top['item_name'] }}</td>
                                <td class="text-center">{{ $top['total_qty'] }}</td>
                                @php 
                                $items = json_decode($top['items']);
                                $x=0;
                                foreach($dateRange as $d) :
                                $dd = date('Ymd', strtotime($d));
                                @endphp
                                <td class="text-center">{{ $items[$x]->$dd->QTY }}</td>
                                <td class="text-center">{{ number_format($items[$x]->$dd->AMT, 2, '.', '') }}</td>
                                @php 
                                $x++;
                                endforeach;
                                @endphp
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
                    
        </div>
    </div>
    <!-- End report section -->
    @endsection