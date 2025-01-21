<?php

namespace App\Controllers\Api;

use App\Models\PackageTypeModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class PackageType extends ResourceController
{
    use ResponseTrait;

    protected $packageTypeModel;

    public function __construct()
    {
        $this->packageTypeModel = new PackageTypeModel();

    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $contents = $this->packageTypeModel->get_list_package_type_package()->getResult();
        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success get list of Package Type"
            ]
        ];
        return $this->respond($response);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $packageType = $this->packageTypeModel->get_package_type_by_id($id)->getRowArray();

        $response = [
            'data' => $packageType,
            'status' => 200,
            'message' => [
                "Success display detail information of Package Type"
            ]
        ];
        return $this->respond($response);
    }

    public function delete($id = null)
    {
        $deletePT = $this->packageTypeModel->delete(['id' => $id]);

        if ($deletePT) {
            $response = [
                'status' => 200,
                'message' => [
                    "Success delete Package Type"
                ]
            ];
            return $this->respondDeleted($response);
        }
    }

    // public function new($id = null)
    // {
    //     $packageType = $this->packageTypeModel->get_package_by_id($id)->getRowArray();

    //     $response = [
    //         'data' => $packageType,
    //         'status' => 200,
    //         'message' => [
    //             "Success display detail information of Package Type"
    //         ]
    //     ];
    //     return $this->respond($response);
    // }

    
}
