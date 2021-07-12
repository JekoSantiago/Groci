<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CmsServices;
use App\Services\HelperServices;
use App\Exports\ExportItemsPrice;
use App\Cms;
use Storage;
use Illuminate\Support\Facades\Session;

use File;
use Maatwebsite\Excel\Excel;
use SimpleXLSX;
use Illuminate\Support\Facades\Redirect;

class CmsController extends Controller
{/*
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if(base64_decode(Session::get('Role_ID')) != 5) :
                return Redirect::to('/403')->send();
            endif;

            return $next($request);
        });
    } */

    public function index()
    {

        return view('pages.maintenance.products.items.index',
            [
                'page'  => 'Product Items Page',
                'items' => CmsServices::productItems()
            ]
        );
    }

    public function addItem()
    {
        return view('pages.maintenance.products.items.modals.add',
            [
                'category' => Cms::getProductCategory(NULL, 1),
                'stores'   => Cms::getStores(NULL, 1)
            ]
        );
    }

    public function saveItems(Request $request)
    {
        $file = $request->file('img_file');
        $fileName = (is_null($file)) ? NULL : time().'.'.$file->getClientOriginalExtension();
        $codes = ($request->input('codes') == 'null') ? NULL : $request->input('codes');

        $data = [
            'category'  => $request->input('category'),
            'sku'       => $request->input('sku'),
            'itemName'  => $request->input('itemName'),
            'img_file'  => $fileName,
            'createdBy' => base64_decode(Session::get('Emp_Name')),
        ];

        $result = Cms::saveProductItems($data);

        if($result > 0) :
            if(!is_null($file)) :
                $path = 'public/products/item';
                $this->uploadFiles($file, $fileName, $path);
            endif;

            if(!is_null($codes)) :
                CmsServices::saveExcludeStores($result, $request->input('codes'));
            endif;

            $response = [
                'status'  => 'ok',
                'message' => HelperServices::errorMessage(100)
            ];
        else :
            $response = [
                'status'  => 'fail',
                'message' => HelperServices::errorMessage($result)
            ];
        endif;

        echo json_encode($response);
    }

    public function editItem(Request $request)
    {
        return view('pages.maintenance.products.items.modals.edit',
            [
                'category' => Cms::getProductCategory(NULL, 1),
                'stores'   => Cms::getStores(NULL, 1),
                'detail'   => Cms::getProductItems($request->segment(5))
            ]
        );
    }

    public function updateItems(Request $request)
    {
        $file = $request->file('img_file');
        $fileName = (is_null($file)) ? $request->input('curFile') : time().'.'.$file->getClientOriginalExtension();

        $data = [
            'category'   => $request->input('category'),
            'sku'        => $request->input('sku'),
            'itemName'   => $request->input('itemName'),
            'img_file'   => $fileName,
            'modifiedBy' => base64_decode(Session::get('Emp_Name')),
            'itemID'     => $request->input('itemID'),
        ];

        $result = Cms::updateProductItems($data);

        if($result == 200) :
            if(!is_null($file)) :
                $path = 'public/products/item';
                $this->uploadFiles($file, $fileName, $path);
            endif;

           // var_dump($request->input('codes'));

            if($request->input('codes') != 'null') :
                CmsServices::updateExcludeStores($request->input('itemID'), $request->input('codes'));
            endif;

            $response = [
                'status'  => 'ok',
                'message' => HelperServices::errorMessage($result)
            ];
        else :
            $response = [
                'status'  => 'fail',
                'message' => HelperServices::errorMessage($result)
            ];
        endif;

        echo json_encode($response);
    }

    public function updateItemStatus(Request $request)
    {
        $action = $request->segment(5);
    	$val  = ($action == 'activate') ? 1 : 0;
    	$data = [
            'value'      => $val,
            'modifiedBy' => base64_decode(Session::get('Emp_Name')),
    		'itemID'     => $request->input('itemID')
    	];

    	$result = Cms::modifyItemStatus($data);
        $update = CmsServices::modifyStatus($action, $result);

        echo json_encode($update);
    }

    /**
     * Item Price section
     */
    public function viewItemPrice(Request $request)
    {
        return view('pages.maintenance.products.items.modals.view_cost',
            [
                'itemPrice' => Cms::getItemPrice($request->segment(6)),
                'itemID'    => $request->segment(6)
            ]
        );
    }

    public function addItemPriceForm(Request $request)
    {
        return view('pages.maintenance.products.items.modals.add_cost',
            [
                'detail' => Cms::getProductItems($request->segment(6)),
                'itemID' => $request->segment(6)
            ]
        );
    }

    public function saveItemPrice(Request $request)
    {
        $data = [
            'itemID'         => $request->input('itemID'),
            'price'          => $request->input('itemPrice'),
            'effective_date' => $request->input('effDate'),
            'isPromo'        => $request->input('isPromo'),
            'createdBy'      => base64_decode(Session::get('Emp_Name')),
        ];

        $save = Cms::savePrice($data);

        if($save == 100) :
            $response = [
                'status'  => 'ok',
                'message' => HelperServices::errorMessage($save)
            ];
        else :
            $response = [
                'status'  => 'fail',
                'message' => HelperServices::errorMessage($save)
            ];
        endif;

        echo json_encode($response);
    }

    public function updatePriceForm()
    {
        return view('pages.maintenance.products.items.modals.price');
    }

    public function save(Request $request)
    {
        $response = CmsServices::savePrice();

        echo json_encode($response);
    }

    public function download()
    {
        $filename  = 'ItemPriceAsOf'.date('YmdHi').'.xlsx';
    	$data = [
            'data' => Cms::getProductItems()
        ];

        return Excel::download(new ExportItemsPrice($data), $filename);
    }

    public function xlsContent($docs)
    {
        if($xlsx = SimpleXLSX::parse($docs)) :
            $rows = [];
            foreach($xlsx->rows() as $k => $r) :
                if($k == 0) continue;
                $rows[$r[0]] = $r;
            endforeach;
        endif;

        return (count($rows) > 0) ? $rows : "";
    }

    /**
     * Product categroy section
     */
    public function category()
    {
        $result = Cms::getProductCategory();
        $data   = CmsServices::dataToArray($result);

        return view('pages.maintenance.products.category.index',
            [
                'page'  => 'Product Category Page',
                'items' => $data
            ]
        );
    }

    public function addCategory()
    {
        return view('pages.maintenance.products.category.modals.add');
    }

    public function saveCategory(Request $request)
    {
        $file = $request->file('img_file');
        $fileName = (is_null($file)) ? NULL : time().'.'.$file->getClientOriginalExtension();

        $data = [
            'categoryName' => $request->input('categoryName'),
            'img_file'     => $fileName,
            'createdBy'    => base64_decode(Session::get('Emp_Name')),
        ];

        $result = Cms::saveCategory($data);

        if($result == 100) :
            if(!is_null($file)) :
                $path = 'public/products/small';
                $this->uploadFiles($file, $fileName, $path);
            endif;

            $response = [
                'status'  => 'ok',
                'message' => HelperServices::errorMessage($result)
            ];
        else :
            $response = [
                'status'  => 'fail',
                'message' => HelperServices::errorMessage($result)
            ];
        endif;

        echo json_encode($response);
    }

    public function editCategory(Request $request)
    {
        return view('pages.maintenance.products.category.modals.edit',
            [
                'detail' => Cms::getProductCategory($request->segment(5)),
            ]
        );
    }

    public function updateCategory(Request $request)
    {
        $file = $request->file('img_file');
        $fileName = (is_null($file)) ? $request->input('curFile') : time().'.'.$file->getClientOriginalExtension();

        $data = [
            'categoryName' => $request->input('categoryName'),
            'img_file'     => $fileName,
            'modifiedBy'   => base64_decode(Session::get('Emp_Name')),
            'catID'        => $request->input('catID'),
        ];

        $result = Cms::updateCategory($data);

        if($result == 200) :
            if(!is_null($file)) :
                $path = 'public/products/small';
                $this->uploadFiles($file, $fileName, $path);
            endif;

            $response = [
                'status'  => 'ok',
                'message' => HelperServices::errorMessage($result)
            ];
        else :
            $response = [
                'status'  => 'fail',
                'message' => HelperServices::errorMessage($result)
            ];
        endif;

        echo json_encode($response);
    }

    public function updateCategoryStatus(Request $request)
    {
        $action = $request->segment(5);
    	$val  = ($action == 'activate') ? 1 : 0;
    	$data = [
            'value'      => $val,
            'modifiedBy' => base64_decode(Session::get('Emp_Name')),
    		'catID'      => $request->input('catID')
    	];

    	$result = Cms::modifyCategoryStatus($data);
        $update = CmsServices::modifyStatus($action, $result);

        echo json_encode($update);
    }

    /**
     * Minimum and Delivery Charges section
     */
    public function charges()
    {
        $result = Cms::getProductCategory();
        $data   = CmsServices::dataToArray($result);

        return view('pages.maintenance.charges.index',
            [
                'page'    => 'Minumum and Delivery Charge Page',
                'mCharge' => Cms::getMinimumCharge(),
                'dCharge' => Cms::getDeliveryCharge()
            ]
        );
    }

    public function addMinCharges()
    {
        return view('pages.maintenance.charges.modals.minimum.add',
            [
                'stores'   => Cms::getStores(NULL, 1)
            ]
        );
    }

    public function saveMinCharges(Request $request)
    {
        $data = [
            'amount'    => $request->input('amount'),
            'effDate'   => $request->input('effDate'),
            'createdBy' => base64_decode(Session::get('Emp_Name'))
        ];

        $result = Cms::saveMinimumCharge($data);

        if($result > 0) :
            $save = CmsServices::saveNoMinimumChargeStores($result, $request->input('codes'));
            $return = $save;
        else :
            $return = -100;
        endif;

        $response   = CmsServices::processData('save', $return);

        echo json_encode($response);
    }

    public function editMinCharges(Request $request)
    {
        return view('pages.maintenance.charges.modals.minimum.edit',
            [
                'details' => Cms::getMinimumCharge($request->segment(5)),
                'stores'  => Cms::getStores(NULL, 1)
            ]
        );
    }

    public function modifyMinCharges(Request $request)
    {
        $data = [
            'amount'     => $request->input('amount'),
            'effDate'    => $request->input('effDate'),
            'modifiedBy' => base64_decode(Session::get('Emp_Name')),
            'id'         => $request->input('id')
        ];

        $result = Cms::updateMinimumCharge($data);

        if($result == 200) :
            $update = CmsServices::updateNoMinimumChargeStores($request->input('id'), $request->input('codes'));

            $return = $update;
        else :
            $return = $result;
        endif;

        $response = CmsServices::processData('update', $return);

        echo json_encode($response);
    }

    public function addDeliveryCharges()
    {
        return view('pages.maintenance.charges.modals.delivery.add',
            [
                'stores'   => Cms::getStores(NULL, 1)
            ]
        );
    }

    public function saveDeliveryCharges(Request $request)
    {
        $data = [
            'amount'    => $request->input('amount'),
            'effDate'   => $request->input('effDate'),
            'createdBy' => base64_decode(Session::get('Emp_Name'))
        ];

        $result = Cms::saveDeliveryCharge($data);

        if($result > 0) :
            $save = CmsServices::saveNoDeliveryChargeStores($result, $request->input('codes'));
            $return = $save;
        else :
            $return = -100;
        endif;

        $response = CmsServices::processData('save', $return);

        echo json_encode($response);
    }

    public function editDeliveryCharges(Request $request)
    {
        return view('pages.maintenance.charges.modals.delivery.edit',
            [
                'details' => Cms::getDeliveryCharge($request->segment(5)),
                'stores'  => Cms::getStores(NULL, 1)
            ]
        );
    }

    public function modifyDeliveryCharges(Request $request)
    {
        $data = [
            'amount'     => $request->input('amount'),
            'effDate'    => $request->input('effDate'),
            'modifiedBy' => base64_decode(Session::get('Emp_Name')),
            'id'         => $request->input('id')
        ];

        $result = Cms::updateDeliveryCharge($data);

        if($result == 200) :
            $update = CmsServices::updateNoDeliveryChargeStores($request->input('id'), $request->input('codes'));

            $return = $update;
        else :
            $return = $result;
        endif;

        $response = CmsServices::processData('update', $return);

        echo json_encode($response);
    }


    /**
     * Slider Section
     */
    public function sliders()
    {
        $result = Cms::getSliders();
        $data   = CmsServices::dataToArray($result);

        return view('pages.maintenance.sliders.index',
            [
                'page'  => 'Slider Page',
                'items' => $data
            ]
        );
    }

    public function addSlider()
    {
        return view('pages.maintenance.sliders.modals.add');
    }

    public function saveSlider(Request $request)
    {
        $file = $request->file('img_file');
        $fileName = (is_null($file)) ? NULL : time().'.'.$file->getClientOriginalExtension();

        $data = [
            'sliderName' => $request->input('sliderName'),
            'img_file'   => $fileName,
            'createdBy'  => base64_decode(Session::get('Emp_Name')),
        ];

        $result = Cms::saveSlider($data);

        if($result == 100) :
            if(!is_null($file)) :
                $path = 'public/slider';
                $this->uploadFiles($file, $fileName, $path);
            endif;

            $response = [
                'status'  => 'ok',
                'message' => HelperServices::errorMessage($result)
            ];
        else :
            $response = [
                'status'  => 'fail',
                'message' => HelperServices::errorMessage($result)
            ];
        endif;

        echo json_encode($response);
    }

    public function editSlider(Request $request)
    {
        return view('pages.maintenance.sliders.modals.edit',
            [
                'detail' => Cms::getSliders($request->segment(4)),
            ]
        );
    }

    public function updateSlider(Request $request)
    {
        $file = $request->file('img_file');
        $fileName = (is_null($file)) ? $request->input('curFile') : time().'.'.$file->getClientOriginalExtension();

        $data = [
            'sliderName' => $request->input('sliderName'),
            'img_file'   => $fileName,
            'modifiedBy' => base64_decode(Session::get('Emp_Name')),
            'sliderID'   => $request->input('sliderID'),
        ];

        $result = Cms::updateSlider($data);

        if($result == 200) :
            if(!is_null($file)) :
                $path = 'public/slider';
                $this->uploadFiles($file, $fileName, $path);
            endif;
            $response = [
                'status'  => 'ok',
                'message' => HelperServices::errorMessage($result)
            ];
        else :
            $response = [
                'status'  => 'fail',
                'message' => HelperServices::errorMessage($result)
            ];
        endif;

        echo json_encode($response);
    }

    public function updateSliderStatus(Request $request)
    {
        $action = $request->segment(4);
    	$val  = ($action == 'activate') ? 1 : 0;
    	$data = [
            'value'      => $val,
            'modifiedBy' => base64_decode(Session::get('Emp_Name')),
    		'sliderID'   => $request->input('sliderID')
    	];

    	$result = Cms::modifySliderStatus($data);
        $update = CmsServices::modifyStatus($action, $result);

        echo json_encode($update);
    }

    /**
     * Banner Ads Section
     */
    public function ads()
    {
        $result = Cms::getAds();
        $data   = CmsServices::dataToArray($result);

        return view('pages.maintenance.ads.index',
            [
                'page'  => 'Banner Ads Page',
                'items' => $data
            ]
        );
    }

    public function addAds()
    {
        return view('pages.maintenance.ads.modals.add');
    }

    public function saveAds(Request $request)
    {
        $file = $request->file('img_file');
        $fileName = (is_null($file)) ? NULL : time().'.'.$file->getClientOriginalExtension();

        $data = [
            'bannerName'   => $request->input('bannerName'),
            'pageLocation' => $request->input('pageLocation'),
            'img_file'     => $fileName,
            'createdBy'    => base64_decode(Session::get('Emp_Name')),
        ];

        $result = Cms::saveBannerAds($data);

        if($result == 100) :
            if(!is_null($file)) :
                $path = 'public/ad';
                $this->uploadFiles($file, $fileName, $path);
            endif;

            $response = [
                'status'  => 'ok',
                'message' => HelperServices::errorMessage($result)
            ];
        else :
            $response = [
                'status'  => 'fail',
                'message' => HelperServices::errorMessage($result)
            ];
        endif;

        echo json_encode($response);
    }

    public function editAds(Request $request)
    {
        return view('pages.maintenance.ads.modals.edit',
            [
                'detail' => Cms::getAds($request->segment(4)),
            ]
        );
    }

    public function updateAds(Request $request)
    {
        $file = $request->file('img_file');
        $fileName = (is_null($file)) ? $request->input('curFile') : time().'.'.$file->getClientOriginalExtension();

        $data = [
            'bannerName'   => $request->input('bannerName'),
            'pageLocation' => $request->input('pageLocation'),
            'img_file'     => $fileName,
            'modifiedBy'   => base64_decode(Session::get('Emp_Name')),
            'adID'         => $request->input('adID'),
        ];

        $result = Cms::updateBannerAds($data);

        if($result == 200) :
            if(!is_null($file)) :
                $path = 'public/ad';
                $this->uploadFiles($file, $fileName, $path);
            endif;
            $response = [
                'status'  => 'ok',
                'message' => HelperServices::errorMessage($result)
            ];
        else :
            $response = [
                'status'  => 'fail',
                'message' => HelperServices::errorMessage($result)
            ];
        endif;

        echo json_encode($response);
    }

    public function updateAdStatus(Request $request)
    {
        $action = $request->segment(4);
    	$val  = ($action == 'activate') ? 1 : 0;
    	$data = [
            'value'      => $val,
            'modifiedBy' => base64_decode(Session::get('Emp_Name')),
    		'adID'       => $request->input('adID')
    	];

    	$result = Cms::modifyAdStatus($data);
        $update = CmsServices::modifyStatus($action, $result);

        echo json_encode($update);
    }
    /**
     * Branch Section
     */
    public function storeBranch()
    {
        $result = CmsServices::storeBranch();

        return view('pages.maintenance.branch.index',
            [
                'page'  => 'Store Branch Page',
                'items' => $result
            ]
        );
    }

    public function updateBranch(Request $request)
    {
        $logUser = str_replace(',', '', base64_decode(Session::get('Emp_Name')));
        $result = CmsServices::refreshBranchList($logUser);

        echo json_encode($result);
    }

    public function updateBranchStatus(Request $request)
    {
        $action = $request->segment(5);
    	$val  = ($action == 'activate') ? 1 : 0;
    	$data = [
            'value'      => $val,
            'modifiedBy' => base64_decode(Session::get('Emp_Name')),
    		'branchID'   => $request->input('branchID')
    	];

    	$result = Cms::modifyBranchStatus($data);
        $update = CmsServices::modifyStatus($action, $result);

        echo json_encode($update);
    }

    public function stores()
    {
        $result = CmsServices::stores();

        return view('pages.maintenance.stores.index',
            [
                'page'  => 'Store Branch Page',
                'items' => $result
            ]
        );
    }

    public function updateStore(Request $request)
    {
        $logUser = str_replace(',', '', base64_decode(Session::get('Emp_Name')));
        $result = CmsServices::refreshStoreList($logUser);

        echo json_encode($result);
    }

    public function updateStoreStatus(Request $request)
    {
        $action = $request->segment(4);
    	$val  = ($action == 'activate') ? 1 : 0;
    	$data = [
            'value'      => $val,
            'modifiedBy' => base64_decode(Session::get('Emp_Name')),
    		'storeID'    => $request->input('storeID')
    	];

    	$result = Cms::modifyStoreStatus($data);
        $update = CmsServices::modifyStatus($action, $result);

        echo json_encode($update);
    }

    private function uploadFiles($file, $fileName, $path)
    {
        $file->storeAs($path, $fileName);
    }

}
