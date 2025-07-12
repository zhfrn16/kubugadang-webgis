<?php

namespace App\Controllers\Web;

use App\Models\KubuGadangModel;
use App\Models\AttractionModel;
use App\Models\GalleryAttractionModel;
use App\Models\FacilityTypeModel;
use CodeIgniter\RESTful\ResourcePresenter;

class Unik extends ResourcePresenter
{
    protected $KubuGadangModel;
    protected $attractionModel;
    protected $galleryAttractionModel;
    protected $facilityTypeModel;

    protected $helpers = ['auth', 'url', 'filesystem'];

    public function __construct()
    {
        $this->KubuGadangModel = new KubuGadangModel();
        $this->attractionModel = new AttractionModel();
        $this->galleryAttractionModel = new GalleryAttractionModel();
        $this->facilityTypeModel = new FacilityTypeModel();
    }

    public function index()
    {
        $contents = $this->attractionModel->get_tracking()->getResultArray();

        $facility = $this->facilityTypeModel->get_list_facility_type()->getResultArray();

        for ($index = 0; $index < count($contents); $index++) {
            $list_gallery = $this->galleryAttractionModel->get_gallery($contents[$index]['id'])->getResultArray();
            $galleries = array();
            foreach ($list_gallery as $gallery) {
                $galleries[] = $gallery['url'];
            }
            $contents[$index]['gallery'] = $galleries;
        }

        $data = [
            'title' => 'Tracking Mangrove',
            'folder' => 'attraction',
            'data' => $contents,
            'facility' => $facility
        ];

        return view('web/tracking_mangrove', $data);
    }

    public function silek()
    {
        $contents = $this->attractionModel->get_silek()->getResultArray();
        $contents2 = $this->KubuGadangModel->get_desa_wisata_info()->getResultArray();
        $contents3 = $this->KubuGadangModel->get_announcement_info()->getResultArray();

        $facility = $this->facilityTypeModel->get_list_facility_type()->getResultArray();

        for ($index = 0; $index < count($contents); $index++) {
            $list_gallery = $this->galleryAttractionModel->get_gallery($contents[$index]['id'])->getResultArray();
            $galleries = array();
            foreach ($list_gallery as $gallery) {
                $galleries[] = $gallery['url'];
            }
            $contents[$index]['gallery'] = $galleries;
        }

        $data = [
            'title' => 'Silek Lanyah',
            'folder' => 'attraction',
            'data' => $contents,
            'data2' => $contents2,
            'data3' => $contents3,
            'facility' => $facility
        ];

        return view('web/silek-lanyah', $data);
    }
}
