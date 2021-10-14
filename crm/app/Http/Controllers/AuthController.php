<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;
use Illuminate\Support\Facades\Redirect, Illuminate\Support\Facades\Session;
use App\Services\CmsServices;

class AuthController extends Controller
{
    public function index(Request $request)
    {
	    // if(date('Y-m-d') == '2021-04-13') :
        //     abort(503);
        // endif;

        $empNo = static::decrypted($request->input('id'));
        $result = Users::logUserDetails($empNo);

        if($result[0]->SLocationCode == 1400) :
            $locationCode = 1895;
        elseif($result[0]->SLocationCode == 2777) :
            $locationCode = 3899;
        else :
            $locationCode = $result[0]->SLocationCode;
        endif;

        Session::put('isLogged', true);
        Session::put('Usr_ID', base64_encode($result[0]->Usr_ID));
        Session::put('Emp_Id', base64_encode($result[0]->Emp_ID));
        Session::put('EmpNo', base64_encode($result[0]->EmployeeNo));
        Session::put('Role_ID', base64_encode($result[0]->Role_ID));
        Session::put('Role_Name', base64_encode($result[0]->RoleName));
        Session::put('Emp_Name', base64_encode($result[0]->empl_name));
        Session::put('PositionLevel_ID', base64_encode($result[0]->PositionLevel_ID));
        Session::put('Department_ID', base64_encode($result[0]->Department_ID));
        Session::put('Department', base64_encode($result[0]->Department));
        Session::put('Location_ID', base64_encode($result[0]->SLocation_ID));
        Session::put('LocationCode', base64_encode($locationCode));
        Session::put('Location', base64_encode($result[0]->SLocation));
        Session::save();


        if(in_array(base64_decode(Session::get('EmpNo')), config('app.admin_users') )) :
            return Redirect::to('/cms/product/items');
        else :
            if(in_array(base64_decode(Session::get('Role_ID')), config('app.store_role_id')) && in_array(base64_decode(Session::get('LocationCode')), config('app.pilot_stores'))) :
                return Redirect::to('/orders');
            else :
                return Redirect::to('/403');
            endif;
        endif;
    }

    private static function decrypted($content, $hashKey = null)
    {
        if ($hashKey == null || $hashKey == '') {
            $hashKey = 'atp_dev';
        }

        $METHOD = 'aes-256-cbc';
        $IV = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);
        $key = substr(hash('sha256', $hashKey, true), 0, 32);
        $decrypted = openssl_decrypt(base64_decode($content), $METHOD, $key, OPENSSL_RAW_DATA, $IV);

        return $decrypted;
    }
}
