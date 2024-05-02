<?php

namespace App\Controllers\Api;

use App\Models\TraditionalHouseModel;
use App\Models\GalleryTraditionalHouseModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class TraditionalHouse extends ResourceController
{
    use ResponseTrait;

    protected $traditionalHouseModel;
    protected $galleryTraditionalHouseModel;

    public function __construct()
    {
        $this->traditionalHouseModel = new TraditionalHouseModel();
        $this->galleryTraditionalHouseModel = new GalleryTraditionalHouseModel();
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $contents = $this->traditionalHouseModel->get_list_th()->getResult();
        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success get list of Traditional House"
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
        $th = $this->traditionalHouseModel->get_th_by_id($id)->getRowArray();

        $response = [
            'data' => $th,
            'status' => 200,
            'message' => [
                "Success display detail information of Traditional House"
            ]
        ];
        return $this->respond($response);
    }

    public function findByRadius()
    {
        $request = $this->request->getPost();
        $contents = $this->traditionalHouseModel->get_th_by_radius($request)->getResult();

        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success find traditional house by radius"
            ]
        ];
        return $this->respond($response);
    }

    public function delete($id = null)
    {
        $deleteGTH = $this->galleryTraditionalHouseModel->delete_gallery($id);
        $deleteTH = $this->traditionalHouseModel->delete(['id' => $id]);
        if ($deleteTH) {
            $response = [
                'status' => 200,
                'message' => [
                    "Success delete Traditional House"
                ]
            ];
            return $this->respondDeleted($response);
        }
    }

    public function getData()
    {
        $request = $this->request->getPost();
        $digitasi = $request['digitasi'];

        for($h=1; $h<20; $h++){
            if ($h < 10) {
                $value= 'TH00'.$h;
            } elseif ($h > 9) {
                $value= 'TH0'.$h;
            }

            if ($digitasi == $value) {
                $digiProperty = $this->traditionalHouseModel->get_object($value)->getRowArray();
                $geoJson = json_decode($this->traditionalHouseModel->get_geoJson($value)->getRowArray()['geoJson']);
            } 
        }
        
        $content = [
            'type' => 'Feature',
            'geometry' => $geoJson,
            'properties' => [
                'id' => $digiProperty['id'],
                'name' => $digiProperty['name'],
                'lat' => $digiProperty['lat'],
                'lng' => $digiProperty['lng'],
            ]
        ];
        $response = [
            'data' => $content,
            'status' => 200,
            'message' => [
                "Success"
            ]
        ];
        return $this->respond($response);
    }
}
