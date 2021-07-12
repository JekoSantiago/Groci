@extends('layout.base')
    @section('contents')    
    <!--- Report content section -->
    <script type="text/javascript" src="{{ asset('assets/js/report.js') }}"></script>
    <div class="row">
        <div class="col-lg-12">
            <!-- Marketing campaigns -->
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h5 class="panel-title">Sales Transaction</h5>
                    <div class="heading-elements">
                        <button class="btn bg-success-800 btn-xs btn-raised" id="btnBranchPerStoreReport">
                            <i class="icon-download4 position-left"></i> EXPORT
                        </button>
                        <input type="hidden" id="params" value="{{ request()->segment(3) }}">
                    </div>
                </div>

                <div class="table-responsive">
                <table class="table no-wrap">
                        <thead>
                            <tr class="bg-teal">
                                <th style="width: 5%" class="text-center">ACTION</th>
                                <th style="width: 23%">STORE</th>
                                <th style="width: 12%">DATE RANGE</th>
                                <th style="width: 7%" class="text-center">WD</th>
                                <th style="width: 7%" class="text-center">CS</th>
                                <th style="width: 7%" class="text-center">QTY</th>
                                <th style="width: 9%" class="text-center">NET SALES</th>
                                <th style="width: 7%" class="text-center">TC</th>
                                <th style="width: 7%" class="text-center">SPD</th>
                                <th style="width: 7%" class="text-center">STD</th>
                                <th style="width: 9%" class="text-center">APC</th>
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
                            foreach($stores as $s) :
                            $param = \App\Services\ReportServices::base64url_encode($s->store_code.'@@'.$firstDate.'@@'.$lastDate);
                            $data = App\Services\ReportServices::reportPerDCPerStorePerRange($s->store_code, date('Y-m-d', strtotime($firstDate)), date('Y-m-d', strtotime($lastDate)));
                            $cntStore += $data['CS'];
                            $netSales += $data['AMT'];
                            $transCount += $data['TC'];
                            $spd += $data['SPD'];
                            $std += $data['STD'];
                            $totalQty += $data['QTY'];
                            $apc = ($data['SPD'] / $data['STD']);
                            @endphp
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
                                <td>{{ $s->store_code .'-'. $s->store_name }}</td>
                                <td>{{ date('M j', strtotime($firstDate)) .'-'. date('M j, Y', strtotime($lastDate)) }}</td>
                                <td class="text-center">{{ count($dateRange) }}</td>
                                <td class="text-center">{{ $data['CS'] }}</td>
                                <td class="text-center">{{ $data['QTY'] }}</td>
                                <td class="text-center">{{ number_format($data['AMT'], 2, '.', '') }}</td>
                                <td class="text-center">{{ $data['TC'] }}</td>
                                <td class="text-center">{{ $data['SPD'] }}</td>
                                <td class="text-center">{{ $data['STD'] }}</td>
                                <td class="text-center">{{ number_format($apc, 3, '.', '') }}</td>
                            </tr>
                            @php 
                            endforeach;

                            $totalAPC = ($spd / $std);
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
    </div>
    <!-- End report section -->
    @endsection