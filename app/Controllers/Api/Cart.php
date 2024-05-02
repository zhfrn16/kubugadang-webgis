<?php

namespace App\Controllers\Api;

use App\Models\CartModel;
use App\Models\AccountModel;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Cart extends ResourceController
{
    use ResponseTrait;

    protected $cartModel;
    protected $accountModel;



    protected $helpers = ['auth', 'url', 'filesystem'];


    public function __construct()
    {
        $this->cartModel = new CartModel();
        $this->accountModel = new AccountModel();
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        // $user = user()->username;
        $user = user()->id;
        // $user = 19;
        $contents = $this->cartModel->get_list_cart_by_user_id_api($user)->getResult();
        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success get list of Cart by User ID username"
            ]
        ];
        return $this->respond($response);
    }

    public function usercart()
    {
        // $id = 'C0001';
        $user_id = '19';
        // $user_id = user()->id;

        $contents = $this->cartModel->get_list_cart_by_user_id_api($user_id)->getResult();
        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success get list of Cart by User ID"
            ]
        ];
        return $this->respond($response);
    }

    public function usercarttotal()
    {
        // $user_id = '19';
        $user = user()->username;
        // $user = 'mutia';
        $contents = $this->cartModel->get_total_cart_by_user_id($user)->getResult();
        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success get total Cart by User ID"
            ]
        ];
        return $this->respond($response);

        // return view('web/layouts/header', ['totalCart' => $totalCart]);

    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $cart = $this->cartModel->get_cart_by_id($id)->getRowArray();

        $response = [
            'data' => $cart,
            'status' => 200,
            'message' => [
                "Success display detail information of Facility Type"
            ]
        ];
        return $this->respond($response);
    }

    public function delete($id = null)
    {
        // $user = user()->id;
        // $deleteFT = $this->cartModel->delete(['user_id' => $user],['package_id' => $id]);

        // if ($deleteFT) {
        //     $response = [
        //         'status' => 200,
        //         'message' => [
        //             "Success delete Cart"
        //         ]
        //     ];
        //     return $this->respondDeleted($response);
    }


    public function addCart()
    {
        $request = $this->request->getPost();

        $id = $this->cartModel->get_new_id();
        $username = user()->username;
        $user_id_data = $this->accountModel->get_id_profil2($username)->getRow();
        $user_id = ($user_id_data) ? $user_id_data->id : null;

        $status = '1';

        $requestData = [
            'id' => $id,
            'user_id' => $user_id,
            'package_id' => $request['package_id'],
            'status' => $status,
        ];

        // Tambahkan pengecekan data yang sudah ada di sini
        $checkExistingData = $this->cartModel->checkIfDataExists($requestData);

        if ($checkExistingData) {
            // Data sudah ada, set pesan error flash data

            $response = [
                'status' => 400,
                'message' => [
                    "Data already exists"
                ]
            ];
            return $this->respond($response, 400);
        } else {

            $addCart = $this->cartModel->add_new_cart($requestData);
            $response = [
                'status' => 200,
                'message' => [
                    "Success add to cart"
                ]
            ];
            return $this->respond($response, 200);
        }
    }


    public function deleteCart($package_id = null, $user_id = null)
    {
        $request = $this->request->getPost();

        $package_id = $request['package_id'];
        $user_id = $request['user_id'];
        $status = '1';


        //jika success
        $array1 = array('package_id' => $package_id, 'user_id' => $user_id,'status' => $status);
        $cart = $this->cartModel->where($array1)->find();
        // dd($packageDay);
        $deleteRE = $this->cartModel->where($array1)->delete();

        if ($deleteRE) {
            $response = [
                'status' => 200,
                'message' => [
                    "Cart deleted successfully"
                ]
            ];
            return $this->respond($response, 200);
        }
        $response = [
            'status' => 200,
            'message' => [
                "Package not found"
            ]
        ];
        return $this->respond($response, 200);
    }
}
