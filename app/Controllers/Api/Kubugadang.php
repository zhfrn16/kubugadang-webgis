<?php

namespace App\Controllers\Api;

use App\Models\KubuGadangModel;
use App\Models\GalleryKubuGadangModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Kubugadang extends ResourceController
{
    use ResponseTrait;

    protected $KubuGadangModel;
    protected $galleryKubuGadangModel;

    public function __construct()
    {
        $this->KubuGadangModel = new KubuGadangModel();
        $this->galleryKubuGadangModel = new GalleryKubuGadangModel();
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $contents = $this->KubuGadangModel->get_sumpu()->getResultArray();

        for ($index = 0; $index < count($contents); $index++) {
            $list_gallery = $this->galleryKubuGadangModel->get_gallery($contents[$index]['id'])->getResultArray();
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
        $sumpu = $this->KubuGadangModel->get_sumpu_marker($id)->getRowArray();

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
        $contents = $this->KubuGadangModel->get_desa_wisata_info()->getResultArray();
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
        $contents = $this->KubuGadangModel->get_id_province_desa_wisata_info()->getResultArray();

        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success"
            ]
        ];

        return $this->respond($response);
    }

    public function deleteannouncement($id = null)
    {
        
        $deleteAN = $this->KubuGadangModel->delete_announcement($id);
        if ($deleteAN) {
            $response = [
                'status' => 200,
                'message' => [
                    "Success delete Announcement"
                ]
            ];
            return $this->respondDeleted($response);
        }
    }

}
