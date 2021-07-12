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
                        <button class="btn bg-success-800 btn-xs btn-raised" id="btnReportBranchPerDay">
                            <i class="icon-download4 position-left"></i> EXPORT
                        </button>
                        <input type="hidden" value="{{ request()->segment(3) }}" id="params">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table no-wrap">
                    <thead>
                            <tr class="bg-teal">
                                <th style="width: 23%">WAREHOUSE</th>
                                <th style="width: 12%">DATE</th>
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
                            foreach($dateRange as $date) :
                            $data = App\Services\ReportServices::reportPerDCPerDay($bcode, date('Y-m-d', strtotime($date)));
                            @endphp
                            <tr>
                                <td>{{ $dcName }}</td>
                                <td>{{ date('M j, Y', strtotime($date)) }}</td>
                                <td class="text-center">1</td>
                                <td class="text-center">{{ $data['CS'] }}</td>
                                <td class="text-center">{{ ($data['QTY'] == NULL) ? 0 : $data['QTY'] }}</td>
                                <td class="text-center">{{ number_format($data['AMT'], 2, '.', '') }}</td>
                                <td class="text-center">{{ $data['TC'] }}</td>
                                <td class="text-center">{{ $data['SPD'] }}</td>
                                <td class="text-center">{{ $data['STD'] }}</td>
                                <td class="text-center">{{ number_format($data['APC'], 3, '.', '') }}</td>
                            </tr>
                            @php
                            endforeach;

                            $tData = App\Services\ReportServices::overAllReport($bcode, date('Y-m-d', strtotime($firstDate)), date('Y-m-d', strtotime($lastDate)));
                            @endphp
                            <tr>
                                <td class="text-semibold">GRAND TOTAL</td>
                                <td class="text-semibold">{{ date('M j', strtotime($firstDate)) .'-'. date('M j, Y', strtotime($lastDate)) }}</td>
                                <th class="text-semibold text-center">{{ count($dateRange) }}</th>
                                <th class="text-semibold text-center">{{ $tData['CS'] }}</th>
                                <th class="text-semibold text-center">{{ $tData['QTY'] }}</th>
                                <th class="text-semibold text-center">{{ number_format($tData['AMT'], 2, '.', '') }}</th>
                                <th class="text-semibold text-center">{{ $tData['TC'] }}</th>
                                <th class="text-semibold text-center">{{ $tData['SPD'] }}</th>
                                <th class="text-semibold text-center">{{ $tData['STD'] }}</th>
                                <th class="text-semibold text-center">{{ number_format(($tData['SPD'] / $tData['STD']), 3, '.', '') }}</th>
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