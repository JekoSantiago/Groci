<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReportServices;
use App\Exports\ExportOverAllReport;
use App\Exports\ExportPerDCPerDayReport;
use App\Exports\ExportPerStorePerDateRangeReport;
use App\Exports\ExportPerStorePerDayReport;
use App\Exports\ExportTopProductsReport;
use App\Cms;
use App\Exports\ExportStoreList;
use App\Orders;
use App\Report;
use Excel;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel as FacadesExcel;

class ReportController extends Controller
{
    public function __construct()
    {
        // $this->middleware(function ($request, $next) {
        //     if(base64_decode(Session::get('Role_ID')) != 5) :
        //         abort(403, json_encode(config('app.btn_previous')));
        //     endif;

        //     return $next($request);
        // });
    }

    public function index(Request $request)
    {
	if($request->isMethod('post')) :
            $date  = explode('-', $request->input('dateRange'));
            $first = $date[0];
            $last  = $date[1];
            $dc    = $request->input('optDC');
            $store = $request->input('stores');
            $storeName = ReportServices::storeName($store);
        else :
            $first = date('m/d/Y', strtotime('-7 days'));
            $last  = date('m/d/Y', strtotime('-1 days'));
            $dc    = 'all';
            $store = NULL;
            $storeName = NULL;
        endif;

        $branchCode = ($dc == 'all') ? NULL : $dc;
        $isActive   = ($dc == 'all') ? 1 : NULL;

        return view('pages.reports.index',
            [
                'page'      => 'Report Page',
		'dateRange' => ReportServices::dateRange($first, $last),
		'cntDays'   => ReportServices::countDays($first, $last),
		'optBranch' => Cms::getBranch(NULL, 1),
                'branch'    => Cms::getBranch($branchCode, $isActive),
                'firstDate' => $first,
                'lastDate'  => $last,
		'dc'        => $branchCode,
                'store'     => $store,
		'optStores' => Cms::getStorePerDC($branchCode),
                'storeName' => $storeName,
		'top15'     => ReportServices::exportTopProducts(date('Y-m-d', strtotime($first)), date('Y-m-d', strtotime($last)))
            ]
        );
    }

    public function branchPerDayReport(Request $request)
    {
        $param  = ReportServices::base64url_decode($request->segment(3));
        $detail = explode('@@', $param);
        $bcode  = $detail[0];
        $firstDate = $detail[1];
        $lastDate  = $detail[2];

        return view('pages.reports.branch_daily',
            [
                'page' => 'Daily Report Per Branch Page',
                'dateRange' => ReportServices::dateRange($firstDate, $lastDate),
                'firstDate' => $firstDate,
                'lastDate'  => $lastDate,
                'dcName'    => ReportServices::dcName($bcode),
                'bcode'     => $bcode
            ]
        );
    }

    public function branchPerStoreReport(Request $request)
    {
        $param  = ReportServices::base64url_decode($request->segment(3));
        $detail = explode('@@', $param);
        $bcode  = $detail[0];
        $firstDate = $detail[1];
        $lastDate  = $detail[2];

        return view('pages.reports.branch_store',
            [
                'page' => 'Report Page',
                'dateRange' => ReportServices::dateRange($firstDate, $lastDate),
                'stores'    => Report::storePerDCWithTransaction($bcode, date('Y-m-d', strtotime($firstDate)), date('Y-m-d', strtotime($lastDate))),
                'firstDate' => $firstDate,
                'lastDate'  => $lastDate,
            ]
        );
    }

    public function viewStorePerDay(Request $request)
    {
        $param  = ReportServices::base64url_decode($request->segment(4));
        $detail = explode('@@', $param);
        $scode  = $detail[0];
        $firstDate = $detail[1];
        $lastDate  = $detail[2];
        $storeName = ReportServices::storeName($scode);

        return view('pages.reports.store',
            [
                'page' => 'Report Page',
                'dateRange' => ReportServices::dateRange($firstDate, $lastDate),
                'firstDate' => $firstDate,
                'lastDate'  => $lastDate,
                'storeName' => $storeName,
                'scode'     => $scode
            ]
        );
    }

    public function viewStoreOrdersPerDay(Request $request)
    {
        $param  = ReportServices::base64url_decode($request->segment(5));
        $detail = explode('@@', $param);
        $scode  = $detail[0];
        $date = $detail[1];
        $data['page'] = 'Orders Page';
        $data['storeName'] = ReportServices::storeName($scode);
        $data['orders'] = Report::getReportOrdersPerStore($scode,$date);

        // dd($data);
        return view('pages.reports.orders',$data);
    }

    public function viewOrderItems(Request $request)
    {
        $orderID = $request->segment(6);
        $receipt = $request->segment(7);
        // dd($orderID);
        $data['receipt'] = $receipt;
        $data['items'] = Orders::getCartItems($orderID);
        // dd($data);
        return view('pages.reports.modal.items',$data);
    }

    /**
     * GENERATE REPORT METHODS
     */
    public function exportReports(Request $request)
    {
        $startDate = $request->segment(3);
        $endDate   = $request->segment(4);
        $bcode     = $request->segment(5);
        $scode     = $request->segment(6);

        if($scode == 0) :
            $dates = ReportServices::dateRange($startDate, $endDate);
            $result = ReportServices::extractOverAllReport($startDate, $endDate, $bcode);
            $filename = 'OverAllReportAsOf_'.date('Ymd', strtotime($startDate)).'To'.date('Ymd', strtotime($endDate)).'.xlsx';
            $data = [
                'data'  => $result['data'],
                'total' => $result['total'],
                'countDays' => count($dates),
                'dateRange' => date('M j', strtotime($startDate)) .'-'. date('M j, Y', strtotime($endDate))
            ];

            return Excel::download(new ExportOverAllReport($data), $filename);
        else :

            $dateRange = ReportServices::dateRange($startDate, $endDate);
            $result = ReportServices::extractDCPerStoreReport($startDate, $endDate, $scode);
            $filename = 'DCStoreReportAsOf_'.date('Ymd', strtotime($startDate)).'To'.date('Ymd', strtotime($endDate)).'.xlsx';

            $data = [
                'data' => $result['data'],
                'dateRange' => date('M j', strtotime($startDate)) .'-'. date('M j, Y', strtotime($endDate)),
                'countDays' => count($dateRange),
                'total'     => $result['total']
            ];

            return Excel::download(new ExportPerStorePerDateRangeReport($data), $filename);
        endif;
    }

    /**
     * Export Branch Daily Report
     */
    public function exportBranchDailyReport(Request $request)
    {
        $params = base64_decode($request->segment(5));
        $detail = explode('@@', $params);
        $bcode  = $detail[0];
        $startDate = $detail[1];
        $endDate   = $detail[2];
        $dates = ReportServices::dateRange($startDate, $endDate);
        $result = ReportServices::extractDCDailyReport($startDate, $endDate, $bcode);
        $filename = 'DCDailyReportAsOf_'.date('Ymd', strtotime($startDate)).'To'.date('Ymd', strtotime($endDate)).'.xlsx';

        $data = [
            'data'  => $result,
            'dateRange' => date('M j', strtotime($startDate)) .'-'. date('M j, Y', strtotime($endDate)),
            'total' => ReportServices::overAllReport($bcode, date('Y-m-d', strtotime($startDate)), date('Y-m-d', strtotime($endDate))),
            'countDays' => count($dates)
        ];

        return Excel::download(new ExportPerDCPerDayReport($data), $filename);
    }

    /**
     * Export Per Branch
     */
    public function exportPerBranchReport(Request $request)
    {
        $params = base64_decode($request->segment(5));
        $detail = explode('@@', $params);
        $bcode  = $detail[0];
        $startDate = $detail[1];
        $endDate   = $detail[2];
        $dateRange = ReportServices::dateRange($startDate, $endDate);
        $result = ReportServices::extractPerDCReport($startDate, $endDate, $bcode);
        $filename = 'DCStoreReportAsOf_'.date('Ymd', strtotime($startDate)).'To'.date('Ymd', strtotime($endDate)).'.xlsx';

        $data = [
            'data' => $result['data'],
            'dateRange' => date('M j', strtotime($startDate)) .'-'. date('M j, Y', strtotime($endDate)),
            'countDays' => count($dateRange),
            'total'     => $result['total']
        ];

        return Excel::download(new ExportPerStorePerDateRangeReport($data), $filename);
    }

    /**
     * EXPORT PER STORE
     */
    public function exportStoreDailyReport(Request $request)
    {
        $params = base64_decode($request->segment(5));
        $detail = explode('@@', $params);
        $scode  = $detail[0];
        $startDate = $detail[1];
        $endDate   = $detail[2];
        $dateRange = ReportServices::dateRange($startDate, $endDate);
        $result    = ReportServices::exportStoreDailyReport($startDate, $endDate, $scode);
        $filename  = 'StoreReportAsOf_'.date('Ymd', strtotime($startDate)).'To'.date('Ymd', strtotime($endDate)).'.xlsx';

        $data = [
            'data' => $result,
            'dateRange' => date('M j', strtotime($startDate)) .'-'. date('M j, Y', strtotime($endDate)),
            'countDays' => count($dateRange),
            'total'     => ReportServices::reportPerDCPerStorePerRange($scode, date('Y-m-d', strtotime($startDate)), date('Y-m-d', strtotime($endDate)))
        ];

        return Excel::download(new ExportPerStorePerDayReport($data), $filename);
    }

    /**
     * EXPORT TOP 15 PRODUCTS
     */
    public function exportTopProducts(Request $request)
    {
        $startDate = $request->segment(4);
        $endDate   = $request->segment(5);
        $dateRange = ReportServices::dateRange($startDate, $endDate);
        $filename  = 'TopProductsReportAsOf_'.date('Ymd', strtotime($startDate)).'To'.date('Ymd', strtotime($endDate)).'.xlsx';
        $result = ReportServices::exportTopProducts($startDate, $endDate);

        $data = [
            'data' => $result,
            'dateRange' => $dateRange,
            'countDays' => count($dateRange),
        ];

        return Excel::download(new ExportTopProductsReport($data), $filename);
    }

    public function optionStores(Request $request)
    {
        $branchCode = $request->input('bcode');
        $result = CMS::getStorePerDC($branchCode);
        $output = '<option><option>';
        foreach($result as $row) :
            $output .= '<option value="'.$row->store_code.'">'.$row->store_code.' - '. strtoupper($row->store_name) .'</option>';
        endforeach;

        return json_encode($output);
    }


}
