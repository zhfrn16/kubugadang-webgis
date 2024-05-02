<?php

namespace App\Controllers\Web;

use App\Models\KecamatanModel;
use App\Models\VillageModel;
use App\Models\SumpuModel;
// use App\Models\GalleryGtpModel;
use CodeIgniter\RESTful\ResourcePresenter;

class Sumpur extends ResourcePresenter
{
    protected $kecamatanModel;
    protected $villageModel;
    protected $sumpuModel;

    protected $helpers = ['auth', 'url', 'filesystem'];

    public function __construct()
    {
        $this->kecamatanModel = new KecamatanModel();
        $this->villageModel = new VillageModel();
        $this->sumpuModel = new SumpuModel();
        // $this->galleryGtpModel = new GalleryGtpModel();
    }

    /**
     * Present a view of resource objects
     *
     * @return mixed
     */
    public function index()
    {
        // $contents = $this->gtpModel->get_gtp()->getResultArray();

        // for ($index = 0; $index < count($contents); $index++) {
        //     $list_gallery = $this->galleryGtpModel->get_gallery($contents[$index]['id'])->getResultArray();
        //     $galleries = array();
        //     foreach ($list_gallery as $gallery) {
        //         $galleries[] = $gallery['url'];
        //     }
        //     $contents[$index]['gallery'] = $galleries;
        // }

        // $contents = $this->villageModel->get_wilayah()->getResultArray();

        $kecamatanModel =  new KecamatanModel();
        $kecamatans = $this->villageModel->findAll(); // Ambil semua data kecamatan
        $contents2 = $this->sumpuModel->get_desa_wisata_info()->getResultArray();

        $data = [
            'title' => 'Explore Sumpu',
            'data'=>$kecamatans,
            'data2'=>$contents2,

            // 'data' => $contents
        ];
// dd($data);
        return view('web/explore_sumpu', $data);
    }
}
