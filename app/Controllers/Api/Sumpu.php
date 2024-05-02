<?php

namespace App\Controllers\Api;

use App\Models\SumpuModel;
use App\Models\GallerySumpuModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Sumpu extends ResourceController
{
    use ResponseTrait;

    protected $sumpuModel;
    protected $gallerySumpuModel;

    public function __construct()
    {
        $this->sumpuModel = new SumpuModel();
        $this->gallerySumpuModel = new GallerySumpuModel();
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $contents = $this->sumpuModel->get_sumpu()->getResultArray();

        for ($index = 0; $index < count($contents); $index++) {
            $list_gallery = $this->gallerySumpuModel->get_gallery($contents[$index]['id'])->getResultArray();
            $galleries = array();
            foreach ($list_gallery as $gallery) {
                $galleries[] = $gallery['url'];
            }
            $contents[$index]['gallery'] = $galleries;
        }

        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success"
            ]
        ];

        return $this->respond($response);
    }

    public function show($id = null)
    {
        $sumpu = $this->sumpuModel->get_sumpu_marker($id)->getRowArray();

        $response = [
            'data' => $sumpu,
            'status' => 200,
            'message' => [
                "Success display detail information of Sumpu"
            ]
        ];
        return $this->respond($response);
    }

    public function tourismVillageInfo()
    {
        $contents = $this->sumpuModel->get_desa_wisata_info()->getResultArray();
        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success"
            ]
        ];

        return $this->respond($response);
    }

    public function getIdProvince()
    {
        $contents = $this->sumpuModel->get_id_province_desa_wisata_info()->getResultArray();

        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success"
            ]
        ];

        return $this->respond($response);
    }
}
