<?php

namespace App\Controllers\Api;

use App\Models\FacilityTypeModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class FacilityType extends ResourceController
{
    use ResponseTrait;

    protected $facilityTypeModel;

    public function __construct()
    {
        $this->facilityTypeModel = new FacilityTypeModel();

    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $contents = $this->facilityTypeModel->get_list_facility_type_package()->getResult();
        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success get list of Facility Type"
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
        $facilityType = $this->facilityTypeModel->get_facility_type_by_id($id)->getRowArray();

        $response = [
            'data' => $facilityType,
            'status' => 200,
            'message' => [
                "Success display detail information of Facility Type"
            ]
        ];
        return $this->respond($response);
    }

    public function delete($id = null)
    {
        $deleteFT = $this->facilityTypeModel->delete(['id' => $id]);

        if ($deleteFT) {
            $response = [
                'status' => 200,
                'message' => [
                    "Success delete Facility Type"
                ]
            ];
            return $this->respondDeleted($response);
        }
    }

    // public function new($id = null)
    // {
    //     $facilityType = $this->facilityTypeModel->get_package_by_id($id)->getRowArray();

    //     $response = [
    //         'data' => $facilityType,
    //         'status' => 200,
    //         'message' => [
    //             "Success display detail information of Facility Type"
    //         ]
    //     ];
    //     return $this->respond($response);
    // }

    
}
