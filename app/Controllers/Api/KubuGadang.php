<?php

namespace App\Controllers\Api;

use App\Models\KubuGadangModel;
use App\Models\GalleryKubuGadangModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class KubuGadang extends ResourceController
{
    use ResponseTrait;

    protected $kubuGadangModel;
    protected $galleryKubuGadangModel;

    public function __construct()
    {
        $this->kubuGadangModel = new KubuGadangModel();
        $this->galleryKubuGadangModel = new GalleryKubuGadangModel();
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $contents = $this->kubuGadangModel->get_kubuGadang()->getResultArray();

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
        $kubuGadang = $this->kubuGadangModel->get_kubuGadang_marker($id)->getRowArray();

        $response = [
            'data' => $kubuGadang,
            'status' => 200,
            'message' => [
                "Success display detail information of KubuGadang"
            ]
        ];
        return $this->respond($response);
    }

    public function tourismVillageInfo()
    {
        $contents = $this->kubuGadangModel->get_desa_wisata_info()->getResultArray();
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
        $contents = $this->kubuGadangModel->get_id_province_desa_wisata_info()->getResultArray();

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
        
        $deleteAN = $this->kubuGadangModel->delete_announcement($id);
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
