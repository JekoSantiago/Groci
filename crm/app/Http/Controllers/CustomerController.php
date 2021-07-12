<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CustomerServices;
use App\Customer;
use App\Cms;
use Illuminate\Support\Facades\Session;

use Mail;
use App\Mail\SendConfirmation;

class CustomerController extends Controller
{
    public function index()
    {
        $result = Customer::getCustomers(NULL, NULL, NULL, base64_decode(Session::get('LocationCode')));
        $data   = CustomerServices::dataToArray($result);

        return view('pages.customers.index',
            [
                'page'  => 'Customer Page',
                'items' => $data
            ]
        );
    }

    public function details(Request $request)
    {
        $segment = explode('@@', $request->segment(3));
        $result  = Customer::getCustomerDetails($segment[0]);
        $row     = Customer::getCustomerAddress(NULL, NULL, $segment[1]);

        $response = [
            'customer_id' => $result[0]->customer_id,
            'lastname'    => $result[0]->lastname,
            'firstname'   => $result[0]->firstname,
            'email'       => $result[0]->email_address,
            'contact_num' => $result[0]->contact_num,
            'address'     => $row[0]->address,
            'city'        => $row[0]->city,
            'province_id' => $row[0]->province_id,
            'type'        => $row[0]->type,
            'landmarks'   => $row[0]->landmarks,
            'addressID'   => $row[0]->address_id
        ];

        echo json_encode($response);
    }

    public function addressView(Request $request)
    {
        $customerID = ($request->input('customerID') == "") ? NULL : $request->input('customerID');
        $result = Customer::getCustomerAddress($customerID, $request->input('type'));

        $response = [
            'address'     => ($result) ? $result[0]->address : '',
            'city'        => ($result) ? $result[0]->city : '',
            'province_id' => ($result) ? $result[0]->province_id : '',
            'landmarks'   => ($result) ? $result[0]->landmarks : '',
            'addressID'   => ($result) ? $result[0]->address_id : '',
        ];

        echo json_encode($response);
    }

    public function viewCustomerDetails(Request $request)
    {
        return view('pages.customers.modals.view',
            [
                'province' => Cms::getProvince(),
                'detail'   => Customer::getCustomers($request->segment(4), NULL, $request->segment(5), base64_decode(Session::get('LocationCode'))),
            ]
        );
    }

    public function validationForm(Request $request)
    {
        return view('pages.customers.modals.confirmation',
            [
                'customerID' => $request->segment(3),
                'addressID'  => $request->segment(4)
            ]
        );
    }

    public function validateCode(Request $request)
    {
        $data = [
            'code'      => $request->input('code'),
            'storeCode' => base64_decode(Session::get('LocationCode'))
        ];

        $result = Customer::checkConfirmationCode($data);

        if($result == 1) :
            $response = [ 'status' => 'ok' ];
        else :
            $response = [ 'status' => 'fail' ];
        endif;

        echo json_encode($response);
    }

    public function confirmStatus(Request $request)
    {
        $customerID = $request->input('customerID');
        $addressID  = $request->input('addressID');
        $action = $request->input('action');
        $detail = Customer::getCustomerDetails($customerID);
        $name   = $detail[0]->firstname.' '.$detail[0]->lastname;
        $email  = $detail[0]->email_address;
        $status = $detail[0]->is_active;
        $remarks = ($action == 'cancel') ? $request->input('remarks') : NULL;

        if($action == 'cancel') :
            $data = [
                'customerID' => $customerID,
                'addressID'  => $addressID,
                'isConfirm'  => 0,
                'remarks'    => $remarks
            ];

            $result = Customer::updateConfirmationStatus($data);
            if($result == 200) :
                $response = [
                    'status'  => 'success',
                    'message' => CustomerServices::message(2)
                ];
            else :
                $response = [
                    'status'  => 'fail',
                    'message' => CustomerServices::message($result)
                ];
            endif;
        else :
            $sent = static::confirmationMail($name, $email, $status);

            if($sent) :
                $data = [
                    'customerID' => $customerID,
                    'addressID'  => $addressID,
                    'isConfirm'  => 1,
                    'remarks'    => $remarks
                ];

                $result = Customer::updateConfirmationStatus($data);
                if($result == 200) :
                    $response = [
                        'status'  => 'success',
                        'message' => CustomerServices::message(1)
                    ];
                else :
                    $response = [
                        'status'  => 'fail',
                        'message' => CustomerServices::message($result)
                    ];
                endif;
            else :
                $response = [
                    'status'  => 'fail',
                    'message' => CustomerServices::message(3)
                ];
            endif;

        endif;

        echo json_encode($response);
    }

    public function viewAddress(Request $request)
    {
        return view('pages.customers.modals.address',
            [
                'address' => CustomerServices::customerAddressDetails($request->segment(4), $request->segment(5)),
                'email'   => $request->segment(4)
            ]
        );
    }

    public function checker()
    {
        $result = Customer::checkNewCustomer(base64_decode(Session::get('LocationCode')));
        $ids   = '';
        $count = 0;
        $tc    = 0;
        foreach($result as $row) :
            $ids .= $row->customer_id.',';
        endforeach;

        $customerID = substr($ids, 0, -1);

        $response = [
            'customerCount' => count($result),
            'tagCount'      => Customer::countTagCustomer($customerID),
            'ids'           => $customerID
        ];

        echo json_encode($response);
    }

    public function tagCustomer(Request $request)
    {
        $ids = explode(',', $request->input('ids'));

        foreach($ids as $id) :
            Customer::tagNewCustomer($id);
        endforeach;

        echo json_encode(['status' => 'ok']);
    }

    public function confirmationMail($name, $email, $status)
    {
        $data = [
            'name'   => $name,
            'email'  => $email,
            'status' => $status
        ];

        Mail::to($email)->send(new SendConfirmation($data));

        if(count(Mail::failures()) > 0) :
            return FALSE; // Not sent
        else :
            return TRUE; //Succesfully sent
        endif;
    }

    public function showAllCustomer()
    {
        $data   = CustomerServices::allCustomers();

        return view('pages.customers.all',
            [
                'page'  => 'All Customer Page',
                'items' => $data
            ]
        );
    }

    public function deleteCustomer(Request $request)
    {
        $delete = Customer::customerDelete($request->input('id'));

        if($delete == 1) :
            $response = [
                'status'  => 'success',
                'message' => 'Customer successfully deleted'
            ];
        else :
            $response = [
                'status'  => 'success',
                'message' => 'Unable to delete customer due to server error. Try again later!'
            ];
        endif;

        echo json_encode($response);
    }

    public function rejectRemarksForm(Request $request)
    {
        return view('pages.customers.modals.remarks',
            [
                'action'     => $request->segment(5),
                'customerID' => $request->segment(3),
                'addressID'  => $request->segment(4)
            ]
        );
    }

}
