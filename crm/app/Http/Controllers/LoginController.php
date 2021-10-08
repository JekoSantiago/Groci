<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Users;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function index()
    {
        // abort(403, json_encode(config('app.btn_myhub')));
        return view('login');
    }

    public function verify(Request $request)
    {
        $empNo  = $request->input('username');
        $password = $request->input('password');

        $rules = array(
            'username' => 'required',
            'password' => 'required',
        );

        $validate = Validator::make($request->all(), $rules);

        if($validate->fails()) :
            return Redirect::to('/')->with('message', 'Username and password is required!');
        else :
            $data = [
                'username' => $request->input('username'),
                'password'  => $request->input('password')
            ];

            $result = Users::getUserDetails($empNo, 1);

        	if(empty($result)) :
        		return Redirect::to('/')->with('message', 'User does not exists!');
        	else :
                $encrypted = (strtotime($result[0]->LastUpdate) <  config('app.new_encryption_date')) ? static::passwordEncrypt($data) : static::passwordEncryptNew($data);

                if($request->input('password') == config('app.default_pass') || ($encrypted['username'] == $result[0]->EmployeeNo && $encrypted['password'] == $result[0]->Password)) :
                   //$uRole  = Users::userRole($result[0]->Usr_ID);

                	Session::put('isLogged', true);
                	Session::put('Usr_ID', base64_encode($result[0]->Usr_ID));
                	Session::put('Emp_Id', base64_encode($result[0]->Emp_ID));
                	Session::put('EmpNo', base64_encode($result[0]->EmployeeNo));
                	//Session::put('Role_ID', base64_encode($uRole[0]->Role_ID));
                	Session::put('Emp_Name',base64_encode($result[0]->empl_name));
                	//Session::put('PositionLevelCode', base64_encode($uRole[0]->PositionLevelCode));
               		Session::put('PositionLevel_ID', base64_encode($result[0]->PositionLevel_ID));
                	//Session::put('DivisionCode', base64_encode($uRole[0]->DivisionCode));
                	//Session::put('Division_ID', base64_encode($uRole[0]->Division_ID));
                	//Session::put('Company_ID', base64_encode($uRole[0]->Company_ID));
                	Session::put('Department_ID', base64_encode($result[0]->Department_ID));
                	//Session::put('DepartmentCode', base64_encode($uRole[0]->DepartmentCode));
                	Session::put('Department', base64_encode($result[0]->Department));
                	Session::put('Location_ID', base64_encode($result[0]->Location_ID));
                	Session::put('LocationCode', base64_encode($result[0]->LocationCode));
                	Session::put('Location', base64_encode($result[0]->Location));
                	Session::save();


                	if(base64_decode(Session::get('EmpNo')) == '8401') :
                    		return Redirect::to('cms/product/items');
                	else :
                    		return Redirect::to('orders');
                	endif;
                else :
                    return Redirect::to('/')->with('message', 'Username or password is invalid!');
                endif;
	       endif;
         endif;
    }

    public function doLogout()
    {
        Session::flush();

        return Redirect::to(config('app.myhub_url' . '?/logout'))->send();
    }

    private static function passwordEncrypt($data)
    {
        $username = $data['username'];
        $password = $data['password'];

        $method = 'aes-256-cbc';
        $$password = substr(hash('sha256', $password, true), 0, 32);
        $iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) .
            chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) .
            chr(0x0) .chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) .
            chr(0x0);

        $e_password = base64_encode(openssl_encrypt($username, $method, $password, OPENSSL_RAW_DATA, $iv));
        $encrypted = array(
            'username' => $data['username'],
            'password' => $e_password
        );

        return $encrypted;
    }

    private static function passwordEncryptNew($data)
    {
        $username = $data['username'];
        $password = $data['password'];

        $method = 'aes-256-cbc';
        $hashed = substr(hash('sha256', $password, true), 0, 32);
        $iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) .
            chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) .
            chr(0x0) .chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) .
            chr(0x0);

        $e_password =  base64_encode(openssl_encrypt($username, $method, $hashed, OPENSSL_RAW_DATA, $iv));
        $encrypted = array(
            'username' => $data['username'],
            'password' => $e_password
        );

        return $encrypted;
    }
}
