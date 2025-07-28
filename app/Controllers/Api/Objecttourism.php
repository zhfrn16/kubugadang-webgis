<?php

namespace App\Controllers\Api;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;
use App\Models\ObjectTourismModel;

class Objecttourism extends ResourceController
{
    use ResponseTrait;

    protected $objectTourismModel;

    public function __construct()
    {
        $this->objectTourismModel = new ObjectTourismModel();
    }

    public function index()
    {
        $contents = $this->objectTourismModel->get_list_object_tourism()->getResult();
        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success get list of Object Tourism"
            ]
        ];
        return $this->respond($response);
    }

    public function show($id = null)
    {
        $object = $this->objectTourismModel->get_object_tourism_by_id($id)->getRowArray();

        $response = [
            'data' => $object,
            'status' => 200,
            'message' => [
                "Success display detail information of Object Tourism"
            ]
        ];
        return $this->respond($response);
    }

    public function detail($id = null)
    {
        $object = $this->objectTourismModel->get_object_tourism_by_id($id)->getRowArray();

        if (empty($object)) {
            return redirect()->to(substr(current_url(), 0, -strlen($id)));
        }

        $geoJson = json_decode($object['geoJson']);
        $object['geometry'] = $geoJson;

        $data = [
            'title' => $object['name'],
            'data' => $object,
            'folder' => 'objecttourism'
        ];

        return view('maps/detail_objecttourism', $data);
    }

    public function delete($id = null)
    {
        $delete = $this->objectTourismModel->delete(['id' => $id]);
        if ($delete) {
            $response = [
                'status' => 200,
                'message' => [
                    "Success delete Object Tourism"
                ]
            ];
            return $this->respondDeleted($response);
        }
    }

    public function getData()
    {
        $request = $this->request->getPost();
        $digitasi = $request['digitasi'];
        for ($h = 1; $h < 20; $h++) {
            if ($h < 10) {
                $value = 'OT00' . $h;
            } elseif ($h > 9) {
                $value = 'OT0' . $h;
            }

            if ($digitasi == $value) {
                $digiProperty = $this->objectTourismModel->get_object($value)->getRowArray();
                $geoJson = json_decode($this->objectTourismModel->get_geoJson($value)->getRowArray()['geoJson']);
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

    public function findAll()
    {
        $contents = $this->objectTourismModel->get_list_object_tourism_api()->getResult();

        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success find all object tourism"
            ]
        ];
        return $this->respond($response);
    }
}
