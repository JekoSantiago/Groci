<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ContentServices;
use App\Services\AccountServices;
use App\Services\ProductServices;
use App\Services\TextServices;
use App\Models\Account;
use App\Models\Product;
use App\Models\Content;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendConfirmation;
use App\Mail\SendConfirmationCode;
use App\Mail\SendForgotPasswordNotification;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

use Laravel\Socialite\Facades\Socialite;

class AccountController extends Controller
{
    /**
     * Login Page
     */
    public function index(Request $request)
    {
        $code = (is_null($request->segment(2))) ? '' : base64_decode($request->segment(2));

        return view('pages.login',
            [
                'page' => 'Login Page',
                'code' => $code
            ]
        );
    }

    public function userLogin(Request $request)
    {
        $result = AccountServices::doLogin(trim($request->input('email')), $request->input('password'));

        if($result == -1) :
            $response = [
                'status'  => 'error',
                'url'     => '',
                'message' => 'You account is not yet activated. Please check your email, we send an activation link.',
            ];
        elseif($result == -2) :
            $response = [
                'status'  => 'fail',
                'url'     => '',
                'message' => 'Invalid email address or password. Try again!',
            ];
        else :
            $customerName = ucwords(strtolower($result[0]->firstname)).' '.ucwords(strtolower($result[0]->lastname));
            $charges = AccountServices::orderCharges($request->input('addressID'));

            Session::put('isLogged', true);
            Session::put('CustomerID', base64_encode($result[0]->customer_id));
            Session::put('CustomerName', $customerName);
            Session::put('email', $result[0]->email_address);
            Session::put('MobileNum', $result[0]->contact_num);
            Session::put('transType', $request->input('transType'));
            Session::put('deliveryTime', $request->input('schedule'));
            Session::put('addressID', $request->input('addressID'));
            Session::put('minimumCharge', ($charges['minimumCharge']) ? : 0);
            Session::put('deliveryCharge', ($charges['deliveryCharge']) ? : 0);
            Session::save();

            $response = [
                'status'  => 'success',
                'message' => '',
            ];
        endif;

        echo json_encode($response);
    }



    /**
     * Register Page
     */
    public function register(Request $request)
    {
        if(is_null($request->segment(2))) :
            $code = '';
            $provinceID = NULL;
            $cityID = NULL;
        else :
            $code = base64_decode($request->segment(2));
            $details = ContentServices::storeDetails($code);
            $item = Content::getProvince($details[0]->province);
            $ct = Content::getCityMunicipalityDetails($details[0]->city);
            $provinceID = $item[0]->province_id;
            $cityID = $ct[0]->municipal_id;
        endif;


        return view('pages.register',
            [
                'page'       => 'Registration Page',
                'code'       => $code,
                'province'   => Content::getProvince(),
                'provinceID' => $provinceID,
                'cityID'     => $cityID
            ]
        );
    }

    public function userRegister(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        $code = substr(str_shuffle('0123456789'), 0, 6);
        $province = explode('-', $request->input('province'));
        $isActive = (!empty($request->input('customerID'))) ? AccountServices::checkCustomerStatus($request->input('customerID')) : 0;

        $data = [
            'lastName'   => trim($request->input('lastName')),
            'firstName'  => trim($request->input('firstName')),
            'email'      => trim($request->input('email')),
            'contactNum' => $request->input('mobileNum'),
            'addType'    => $request->input('type'),
            'address'    => $request->input('address'),
            'city'       => $request->input('city'),
            'province'   => $province[0],
            'password'   => password_hash($request->input('password'), PASSWORD_DEFAULT),
            'storeCode'  => $request->input('store'),
            'regFrom'    => 'ONLINE',
            'regDate'    => date('Y-m-d H:i:s'),
            'code'       => $code,
            'landmark'   => $request->input('landmarks'),
            'customerID' => $request->input('customerID'),
            'addressID'  => $request->input('addressID'),
            'isConfirm'  => 1,
            'isActive'   => $isActive,
            'remarks'    => $request->input('remarks')
        ];

        // dd($data);

        $store  = ContentServices::storeName($request->input('store'));
        $result = AccountServices::doRegister($data);

        if($result[0]->_RETURN == 100) :
            $name = $request->input('firstName').' '. $request->input('lastName');
            $email = $request->input('email');
            $cid = $result[0]->customer_id;

            if($isActive == 0) :
                static::sentConfirmation($name, $email, $cid);
            endif;

            $status   = 'ok';
            $message  = ($isActive == 1) ? TextServices::message(103) : TextServices::message(102);
            $province = AccountServices::provinceOption();
            $store    = '<option value=""></option>';
        else :
            $msg      = ($result == -100) ? TextServices::message(-100) : str_replace('[store]', $store, TextServices::message(-200));
            $status   = 'fail';
            $message  = $msg;
            $province = '';
            $store    = '';
        endif;

        $response = [
            'status'   => $status,
            'message'  => $message,
            'province' => $province,
            'stores'   => $store
        ];

        echo json_encode($response);
    }

    public function sentConfirmation($name, $email, $cid)
    {
        $data = [
            'name'   => $name,
            'email'  => $email,
            'status' => 0
        ];

        $body = ' Dear ' . $name . ' ,
        Welcome to Shop Alfamart!
        Please click the link below to complete your registration:
        ' . env('APP_URL') .'/activate/' . base64_encode($email) .'
        Connect with us! Follow Alfamart at Facebook Alfamart PH today.
        Need help? Email us at customercare@alfamart.com.ph
        This is a post-only mailing. Please do not reply to this email.';

        $subj = 'Verify your Shop Alfamart account';

        $logs = [
            $email,
            $subj,
            $body,
            $cid
        ];
        Mail::to($email)->send(new SendConfirmation($data));

        Account::emailLogs($logs);


    }

    /**
     * My Account Details, Addresses and Order Lists
     */
    public function viewProfile()
    {
        return view('pages.account.profile',
            [
                'page'    => 'My Profile Page',
                'details' => AccountServices::customerDetails(base64_decode(Session::get('CustomerID')))
            ]
        );
    }

    public function updateProfile(Request $request)
    {
        $password = ($request->input('change_pass') == 'Yes') ? password_hash($request->input('password'), PASSWORD_DEFAULT) : $request->input('cur_pass');
        $data = [
            'firstName'  => $request->input('firstName'),
            'lastName'   => $request->input('lastName'),
            'mobileNum'  => $request->input('mobileNum'),
            'password'   => $password,
            'customerID' => base64_decode($request->input('customerID'))
        ];

        $response = AccountServices::updateCustomerDetails($data);

        echo json_encode($response);
    }

    public function customerDetails(Request $request)
    {
        $email = trim($request->input('email'));
        $code  = ($request->input('code') == '') ? NULL : $request->input('code');

        $result = AccountServices::getCustomerDetails($email, $code);

        echo json_encode($result);
    }

    public function viewAddress()
    {
        return view('pages.account.address',
            [
                'page'    => 'My Address Page',
                'details' => AccountServices::customerDetails(base64_decode(Session::get('CustomerID'))),
                'address' => Account::getCustomerAddress(NULL, Session::get('email'), NULL, NULL),
                'provinceID' => NULL
            ]
        );
    }

    public function accountAddressSave(Request $request)
    {
        $code = substr(str_shuffle('0123456789'), 0, 6);
        $province = explode('-', $request->input('province'));
        $store = ContentServices::storeName($request->input('storeCode'));

        $data = [
            'customerID' => base64_decode($request->input('customerID')),
            'address'    => $request->input('address'),
            'city'       => $request->input('city'),
            'province'   => $province[0],
            'landmarks'  => $request->input('landmarks'),
            'type'       => $request->input('type'),
            'storeCode'  => $request->input('storeCode'),
            'isConfirm'  => 1,
            'code'       => $code,
        ];

        $save = AccountServices::saveCustomerAddress($data);

        if($save == 100) :
             static::sentConfirmation(Session::get('CustomerName'), Session::get('email'),base64_decode($request->input('customerID')));
            $response = [
                'status'  => 'success',
                'message' => 'You have successfully registered another store on your account.'
            ];
        else :
            $message = ($save == -100) ? 'ERROR: Unable to save data due to server error. Try again later!' : 'You have already registered to this store. You may try another one.';
            $response = [
                'status'  => 'fail',
                'message' => $message
            ];
        endif;

        echo json_encode($response);
    }

    public function addressList()
    {
        $data = Account::getCustomerAddress(NULL, Session::get('email'), NULL, NULL);
        $count = 0;

        foreach($data as $c)
        {
            $count ++;
        }

        $output = '';
        $disable = '';

        if ($count <= 1)
        {
            $disable = 'style=display:none';
        }

        foreach($data as $row) :
            $output .= '<div class="col-sm-12 ">
                            <div class="form-group">
                                <label class="control-label" style="width: 100%"> STORE: <b>'. $row->store_name .'</b>
                                    <button type="button" data-toggle="modal" data-target="#modal_edit_address" data-popup="tooltip" title="Edit" data-placement="top" class="btn btn-error btn-sm text-right" data-add ="'.$row->address.'" data-lm="'.$row->landmarks.'" data-typ="'.$row->type.'" data-aid="'.$row->address_id.'">
                                    <i class="mdi mdi-pencil text-info"></i>
                                    </button>
                                    <button type="button" class="btn btn-error btn-sm text-right deladd" data-aid ="'. $row->address_id.'" '.$disable.' data-popup="tooltip" title="Delete" data-placement="top">
                                    <i class="mdi mdi-close text-danger"></i>
                                    </button>
                                </label>
                                <textarea rows=3 class="form-control border-form-control" style="text-align: left" readonly>Address : '. $row->address.' '.$row->city.' '.$row->province_name .'&#13;&#10;Landmark : '.  $row->landmarks .' &#13;&#10;Type : '. $row->type . '  </textarea>
                            </div>
                        </div>';
        endforeach;

        echo $output;
    }

    public function viewOrders()
    {
        $storeCode = (is_null(Session::get('addressID'))) ? NULL : AccountServices::customerAddressAssignedStore(Session::get('addressID'));
        return view('pages.account.orders',
            [
                'page' => 'My Orders Page',
                'details' => AccountServices::customerDetails(base64_decode(Session::get('CustomerID'))),
                'orders'  => AccountServices::customerOrders(base64_decode(Session::get('CustomerID')), $storeCode),
            ]
        );
    }

    public function showStorePerProvince(Request $request)
    {
        if($request->input('province') == '') :
            $province = NULL;
            $provinceID = NULL;
        else :
            $value = explode('-', $request->input('province'));
            $province = ($value[0] == 51) ? 'MOA,NCR,NCR NORTH,METRO MANILA' : $value[1];
            $provinceID = $value[0];
        endif;

        $stores = ContentServices::storeOption($province);
        $city   = ContentServices::cityOption($provinceID);

        $response = [
            'stores' => $stores,
            'city'   => $city
        ];

        echo json_encode($response);
    }

    public function showStorePerCity(Request $request)
    {
        $city = ($request->input('city') == '') ? NULL : $request->input('city');
        $stores = ContentServices::storePerCityOption($city);

        $response = [
            'stores' => $stores
        ];

        echo json_encode($response);
    }

    public function orderDetails(Request $request)
    {
        return view('pages.account.order_details',
            [
                'orderID'     => $request->segment(4),
                'details'     => Product::getOrderDetails($request->segment(4)),
                'orderItems'  => Account::viewOrderItems($request->segment(4)),
                'orderStatus' => Product::getOrderStatus($request->segment(4))
            ]
        );
    }

    public function reOrder(Request $request)
    {
        $soID = $request->input('orderID');

        $result = AccountServices::reOrderItems($soID, Session::get('orderBasket'));

        if($result > 0) :
            $response = [
                'status'  => 'success',
                'message' => TextServices::message(101)
            ];
        else :
            $response = [
                'status'  => 'fail',
                'message' => TextServices::message(-5)
            ];
        endif;

        echo json_encode($response);
    }

    public function customerDeliveryAddress(Request $request)
    {
        $result = AccountServices::loginCustomerAddress(trim($request->input('email')), $request->input('code'));

        echo json_encode($result);
    }

    public function activateAccount(Request $request)
    {
        $email = base64_decode($request->segment(2));
        $result = Account::activateAccount($email);

        return Redirect::to('/');
    }

    public function showCustomerAddress(Request $request)
    {
        $email = trim($request->input('email'));
        $type  = $request->input('type');
        $storeCode = $request->input('code');

        $result = AccountServices::customerAddress($type, $email, $storeCode);

        echo json_encode($result);
    }

    public function selectTransaction(Request $request)
    {
        $charges = AccountServices::orderCharges($request->input('addressID'));
        $storeCode = AccountServices::customerAddressAssignedStore($request->input('addressID'));
        $categoryIDs = ContentServices::catArr($storeCode);
        $param = explode('@@', base64_decode($request->input('param')));
        $catID = $param[4];
        $isLogged = (!Session::get('isLogged')) ? 0 : 1;


        Session::put('transType', $request->input('transType'));
        Session::put('deliveryTime', $request->input('schedule'));
        Session::put('addressID', $request->input('addressID'));
        Session::put('minimumCharge', ($charges['minimumCharge']) ? : 0);
        Session::put('deliveryCharge', ($charges['deliveryCharge']) ? : 0);
        Session::save();

        if(in_array($catID, $categoryIDs)) :
            $items = ProductServices::arrItems($catID, $storeCode, $isLogged);

            if(in_array($param[0], $items)) :
                $result = ProductServices::addItemToCart(Session::get('orderBasket'), $request->input('param'), $request->input('qty'), $storeCode);

                if($result[0]->_RETURN  == 2) :
                    $response = [
                        'status'  => 'ok',
                        'message' => '',
                        'totalAmount' => $result[0]->totalAmount
                    ];
                else :
                    $response = [
                        'status'  => 'fail',
                        'message' => TextServices::message($result),
                        'totalAmount' => $result[0]->totalAmount
                    ];
                endif;
            else :
                $response = [
                    'status'  => 'error',
                    'message' => 'Item you have selected is not available to the store you have selected.',
                    'totalAmount' => 0
                ];
            endif;
        else :
            $response = [
                'status'  => 'error',
                'message' => 'Item you have selected is not available to the store you have selected.',
                'totalAmount' => 0
            ];
        endif;

        echo json_encode($response);
    }

    /**
     * Redirect Page
     */
    public function redirectPage(Request $request)
    {
        $page = (is_null($request->segment(3))) ? '/'.$request->segment(2) : '/'.$request->segment(2).'/'.$request->segment(3);

        if(Session::get('isSocial') == true) :
            Session::flush();
        endif;

        return Redirect::to($page);
    }

    /**
     * Social media login and registration
     */
    public function redirect(Request $request)
    {
        Session::put('action', $request->segment(4));
        Session::put('code', $request->segment(5));
        Session::save();

        return Socialite::driver($request->segment(3))->redirect();
    }

    public function callback($provider)
    {
        $user = Socialite::driver($provider)->user();

        Session::put('isSocial', true);
        Session::put('userEmail', $user['email']);
        Session::save();

        if(Session::get('action') == 'register') :
            $redirectURL = (!Session::get('code')) ? '/register' : '/register/'.Session::get('code');
        else :
            $redirectURL = (!Session::get('code')) ? '/login' : '/login/'.Session::get('code');
        endif;

        return Redirect::to($redirectURL);
    }

    public function forgotPassword()
    {
        return view('pages.forgot_pass',
            [
                'page' => 'Forgot Password Page',
            ]
        );
    }

    public function forgotPasswordNotification(Request $request)
    {
        $detail = Account::getCustomerDetails(NULL, trim($request->input('email')));

        if(count($detail) > 0) :
            if($request->input('option') == 'password') :
                $data = [
                    'name'  => $detail[0]->firstname.' '.$detail[0]->lastname,
                    'email' => $detail[0]->email_address,
                    'id'    => $detail[0]->customer_id
                ];

                $body = ' Dear ' . $detail[0]->firstname.' '.$detail[0]->lastname . ' ,
                Forgot your password? No worries! Just click the link below to reset this:
                '.url('/change-password/'.base64_encode($detail[0]->email_address.'&'.$detail[0]->customer_id)).'
                Connect with us! Follow Alfamart at Facebook (hyperlink to the Alfamart PH FB page) today.
                Need help? Email us at customercare@alfamart.com.ph.
                This is a post-only mailing. Please do not reply to this email. ';

                $subj = 'Shop Alfamart Account Password recovery';

                $logs = [
                    $detail[0]->email_address,
                    $subj,
                    $body
                ];
                Mail::to($detail[0]->email_address)->send(new SendForgotPasswordNotification($data));
                Account::emailLogs($logs);

            else :
                $item = AccountServices::getCode($request->input('email'));
                $store = ContentServices::storeName($item['scode']);
                $data = [
                    'name'  => $detail[0]->firstname.' '.$detail[0]->lastname,
                    'code'  => $item['code'],
                    'store' => $store
                ];

                 Mail::to($detail[0]->email_address)->send(new SendConfirmationCode($data));
            endif;

            if(count(Mail::failures()) > 0) :
                $response = [
                    'status'  => 'fail',
                    'message' => 'Unable to send notification due to server error. Try again later!'
                ];
            else :
                $response = [
                    'status'  => 'success',
                    'message' => 'We have successfully sent the instructions to the email address you provided.'
                ];
            endif;
        else :
            $response = [
                'status'  => 'fail',
                'message' => 'The email address you provide does not exist. Try another one or check if the spelling is correct.'
            ];
        endif;

        echo json_encode($response);
    }

    public function changePassword(Request $request)
    {
        $params = explode('&', base64_decode($request->segment(2)));
        return view('pages.reset_pass',
            [
                'page'   => 'Change Password Page',
                'email'  => $params[0],
                'custID' => $params[1]
            ]
        );
    }

    public function updateAccountPassword(Request $request)
    {
        $detail = Account::getCustomerDetails(NULL, trim($request->input('email')));
        $password = password_hash($request->input('password'), PASSWORD_DEFAULT);
        $data = [
            'firstName'  => $detail[0]->firstname,
            'lastName'   => $detail[0]->lastname,
            'mobileNum'  => $detail[0]->contact_num,
            'password'   => $password,
            'customerID' => $request->input('id')
        ];

        $result = Account::modifyCustomerDetail($data);

        if($result == 1) :
            $response = [
                'status'  => 'success',
                'message' => 'You have successfully change your password.'
            ];
        else :
            $response = [
                'status'  => 'fail',
                'message' => 'Unable to change your password due to server error. Try again alter!'
            ];
        endif;

        echo json_encode($response);
    }

    public function checkCode(Request $request)
    {
        $result = AccountServices::getCode($request->input('email'));

        if($result['count'] > 0) :
            $response = [
                'status'  => 'exist',
                'message' => 'One of your registered address is not yet confirmed.<br/>Would like us to resend the code?<br/>Please click the link Reset Code | Forgot Password below.'
            ];
        else :
            $response = [
                'status'  => 'none',
                'message' => ''
            ];
        endif;

        echo json_encode($response);
    }

    /**
     * Change store
     */
    public function changeStore(Request $request)
    {
        Session::forget('orderBasket');
        Session::forget('orderID');
        Session::forget('tempID');
        Session::forget('addressID');
        Session::forget('minimumCharge');
        Session::forget('deliveryCharge');

        $charges = AccountServices::orderCharges($request->input('addID'));

        Session::put('addressID', $request->input('addID'));
        Session::put('minimumCharge', ($charges['minimumCharge']) ? : 0);
        Session::put('deliveryCharge', ($charges['deliveryCharge']) ? : 0);
        Session::save();

        $response = [
            'status'  => 'success',
        ];

        echo json_encode($response);
    }

    /**
     * Logout
     */
    public function doLogout()
    {
        Session::flush();

        return Redirect::to('/');
    }

    /**
     * Delete Address
     */

    public function deleteAddress(Request $request)
    {
        // dd($request->aid);

        $param = [
            $request->aid
        ] ;

        $res = Account::deleteAddress($param);



        $num = $res[0]->RETURN;
        $msg = $res[0]->Message;

        $result = array('num' => $num, 'msg' => $msg);
        return $result;


    }

    public function updateAddress(Request $request)
    {
        $param = [
            $request->aid,
            $request->address,
            $request->landmark,
            $request->type
        ];
        $res = Account::updateAddress($param);

        $num = $res[0]->RETURN;
        $msg = $res[0]->Message;

        $result = array('num' => $num, 'msg' => $msg);
        return $result;
    }


}
