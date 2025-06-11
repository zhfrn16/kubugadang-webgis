<?php

namespace App\Controllers\Web;

use App\Models\CartModel;
use App\Models\KubuGadangModel;
use App\Models\GalleryPackageModel;
use App\Models\AccountModel;

use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\Files\File;

class Cart extends ResourcePresenter
{
    protected $cartModel;
    protected $KubuGadangModel;
    protected $galleryPackageModel;
    protected $accountModel;

    /**
     * Instance of the main Request object.
     *
     * @var HTTP\IncomingRequest
     */
    protected $request;

    protected $helpers = ['auth', 'url', 'filesystem'];

    public function __construct()
    {
        $this->cartModel = new CartModel();
        $this->KubuGadangModel = new KubuGadangModel();
        $this->galleryPackageModel = new GalleryPackageModel();
        $this->accountModel = new AccountModel();
    }

    /**
     * Present a view of resource objects
     *
     * @return mixed
     */


    public function index()
    {
        // $user = user()->username;
        $user = user()->id;
        $contents = $this->cartModel->get_list_cart_by_user_id($user)->getResultArray();
        $contents2 = $this->KubuGadangModel->get_desa_wisata_info()->getResultArray();

        foreach ($contents as &$package) {
            $id = $package['package_id'];
            $gallery = $this->galleryPackageModel->get_gallery($id)->getRowArray();

            // Assuming you want to associate the gallery with each package
            if (!empty($gallery)) {
                foreach ($gallery as $item) {
                    $package['gallery'] = $item;
                }
            } else {
                $package['gallery'] = 'default.jpg';
            }
        }
        $data = [
            'title' => 'Cart',
            'data' => $contents,
            'data2' => $contents2,
        ];
        return view('web/manage-cart', $data);
    }

    public function addCart()
    {
        $request = $this->request->getPost();

        $id = $this->cartModel->get_new_id();
        $username = user()->username;
        $user_id_data = $this->accountModel->get_id_profil($username)->getRow();
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
            $session = session();
            $session->setFlashdata('error', 'The data already exists');
            $response = [
                'status' => 400,
                'message' => [
                    "Data already exists"
                ]
            ];
            return $this->respond($response, 400);
        }

        $addCart = $this->cartModel->add_new_cart($requestData);
        return $this->respond($response, 200);

        if ($addCart) {
            $response = [
                'status' => 200,
                'message' => [
                    "Success add to cart"
                ]
            ];
            return $this->respond($response, 200);
        } else {
            $response = [
                'status' => 500,
                'message' => [
                    "Fail add to cart"
                ]
            ];
            return $this->respond($response, 500);
        }
    }



    public function usercarttotal()
    {
        // $user_id = '19';
        $user = user()->username;
        $contents = $this->cartModel->get_total_cart_by_user_id($user)->getResult();
        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success get total Cart by User ID"
            ]
        ];

        return $this->respond($response, 400);

        // return view('web/layouts/header', ['totalCart' => $totalCart]);

    }

    // public function deleteCart($package_id = null, $user_id = null)
    // {
    //     $request = $this->request->getPost();

    //     $package_id = $request['package_id'];
    //     $user_id = $request['user_id'];


    //     //jika success
    //     $array1 = array('package_id' => $package_id, 'user_id' => $user_id);
    //     $cart = $this->cartModel->where($array1)->find();
    //     // dd($packageDay);
    //     $deleteRE = $this->cartModel->where($array1)->delete();

    //     if ($deleteRE) {
    //         session()->setFlashdata('success', 'Cart Deleted Successfully.');
    //         return redirect()->back();
    //     }
    //     $response = [
    //         'status' => 200,
    //         'message' => [
    //             "Package not found"
    //         ]
    //     ];
    //     return $this->respond($response, 200);
    // }
}
