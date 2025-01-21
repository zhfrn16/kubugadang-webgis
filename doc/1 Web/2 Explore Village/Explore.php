<?php

namespace App\Controllers\Web;

use App\Models\KecamatanModel;
use App\Models\VillageModel;
use App\Models\SumpuModel;
use App\Models\PackageModel;
use App\Models\GalleryPackageModel;
use App\Models\PackageDayModel;
use App\Models\DetailPackageModel;
// use App\Models\GalleryGtpModel;
use CodeIgniter\RESTful\ResourcePresenter;

class Explore extends ResourcePresenter
{
    protected $kecamatanModel;
    protected $villageModel;
    protected $sumpuModel;
    protected $packageModel;
    protected $galleryPackageModel;
    protected $packageDayModel;
    protected $detailPackageModel;

    protected $helpers = ['auth', 'url', 'filesystem'];

    public function __construct()
    {
        $this->kecamatanModel = new KecamatanModel();
        $this->villageModel = new VillageModel();
        $this->sumpuModel = new SumpuModel();
        $this->packageModel = new PackageModel();
        $this->galleryPackageModel = new GalleryPackageModel();
        $this->packageDayModel = new PackageDayModel();
        $this->detailPackageModel = new DetailPackageModel();
        // $this->galleryGtpModel = new GalleryGtpModel();
    }

    /**
     * Present a view of resource objects
     *
     * @return mixed
     */
    public function index()
    {

        $kecamatanModel =  new KecamatanModel();
        $kecamatans = $this->villageModel->findAll(); // Ambil semua data kecamatan
        $contents2 = $this->sumpuModel->get_desa_wisata_info()->getResultArray();

        $list_package = $this->packageModel->get_list_package_explore()->getResultArray();
        $packages = [];

        foreach ($list_package as $package) {
            $id = $package['id'];
            $package = $this->packageModel->get_package_by_id($id)->getRowArray();
            if (empty($package)) {
                return redirect()->to(substr(current_url(), 0, -strlen($id)));
            }

            $list_gallery = $this->galleryPackageModel->get_gallery($id)->getResultArray();
            $galleries = array();
            foreach ($list_gallery as $gallery) {
                $galleries[] = $gallery['url'];
            }
            $package['gallery'] = $galleries;

            $detailPackage = $this->detailPackageModel->get_detailPackage_by_id($id)->getResultArray();
            $getday = $this->packageDayModel->get_list_package_day($id)->getResultArray();
            $combinedData = $this->detailPackageModel->getCombinedData($id);

            $datapackage = [
                'title' => $package['name'],
                'data' => $package,
                'data2' => $contents2,
                'day' => $getday,
                'activity' => $combinedData,
                'folder' => 'package'
            ];

            $packages[] = $datapackage;
        }

        $data = [
            'title' => 'Explore Sumpu',
            'data' => $kecamatans,
            'data2' => $contents2,
            'datapackage' => $packages,

        ];

        return view('web/explore_village', $data);
    }

    public function exploremypackage()
    {

        $kecamatanModel =  new KecamatanModel();
        $kecamatans = $this->villageModel->findAll(); // Ambil semua data kecamatan
        $contents2 = $this->sumpuModel->get_desa_wisata_info()->getResultArray();

        $user_id = user()->id;

        $list_package = $this->packageModel->get_list_mypackage_explore($user_id)->getResultArray();
        $packages = [];

        foreach ($list_package as $package) {
            $id = $package['id'];
            $check_in = date('Y-m-d', strtotime($package['check_in']));
            $homestay_name = $package['homestay_name'];
            $lat = $package['lat'];
            $lng = $package['lng'];
            $package = $this->packageModel->get_package_by_id($id)->getRowArray();
            if (empty($package)) {
                return redirect()->to(substr(current_url(), 0, -strlen($id)));
            }

            $list_gallery = $this->galleryPackageModel->get_gallery($id)->getResultArray();
            $galleries = array();
            foreach ($list_gallery as $gallery) {
                $galleries[] = $gallery['url'];
            }
            $package['gallery'] = $galleries;

            $detailPackage = $this->detailPackageModel->get_detailPackage_by_id($id)->getResultArray();
            $getday = $this->packageDayModel->get_list_package_day($id)->getResultArray();
            $combinedData = $this->detailPackageModel->getCombinedData($id);

            $datapackage = [
                'title' => $package['name'],
                'check_in' => $check_in,
                'homestay_name' => $homestay_name,
                'lat' => $lat,
                'lng' => $lng,
                'data' => $package,
                'data2' => $contents2,
                'day' => $getday,
                'activity' => $combinedData,
                'folder' => 'package'
            ];

            $packages[] = $datapackage;
        }

        $data = [
            'title' => 'Explore Sumpu',
            'data' => $kecamatans,
            'data2' => $contents2,
            'datapackage' => $packages,

        ];

        return view('web/explore_village_mypackage', $data);
    }

    public function packagelistMobile()
    {

        $kecamatanModel =  new KecamatanModel();
        $kecamatans = $this->villageModel->findAll(); // Ambil semua data kecamatan
        $contents2 = $this->sumpuModel->get_desa_wisata_info()->getResultArray();

        $list_package = $this->packageModel->get_list_package_explore()->getResultArray();
        $packages = [];

        foreach ($list_package as $package) {
            $id = $package['id'];
            $package = $this->packageModel->get_package_by_id($id)->getRowArray();
            if (empty($package)) {
                return redirect()->to(substr(current_url(), 0, -strlen($id)));
            }

            $list_gallery = $this->galleryPackageModel->get_gallery($id)->getResultArray();
            $galleries = array();
            foreach ($list_gallery as $gallery) {
                $galleries[] = $gallery['url'];
            }
            $package['gallery'] = $galleries;

            $detailPackage = $this->detailPackageModel->get_detailPackage_by_id($id)->getResultArray();
            $getday = $this->packageDayModel->get_list_package_day($id)->getResultArray();
            $combinedData = $this->detailPackageModel->getCombinedData($id);

            $datapackage = [
                'title' => $package['name'],
                'data' => $package,
                'data2' => $contents2,
                'day' => $getday,
                'activity' => $combinedData,
                'folder' => 'package'
            ];

            $packages[] = $datapackage;
        }

        $data = [
            'title' => 'Explore Sumpu',
            'data' => $kecamatans,
            'data2' => $contents2,
            'datapackage' => $packages,

        ];

        return view('maps/explore_village', $data);
    }

    public function exploremypackageMobile()
    {

        $kecamatanModel =  new KecamatanModel();
        $kecamatans = $this->villageModel->findAll(); // Ambil semua data kecamatan
        $contents2 = $this->sumpuModel->get_desa_wisata_info()->getResultArray();

        $user_id = user()->id;

        $list_package = $this->packageModel->get_list_mypackage_explore($user_id)->getResultArray();
        $packages = [];

        foreach ($list_package as $package) {
            $id = $package['id'];
            $check_in = date('Y-m-d', strtotime($package['check_in']));
            $homestay_name = $package['homestay_name'];
            $lat = $package['lat'];
            $lng = $package['lng'];
            $package = $this->packageModel->get_package_by_id($id)->getRowArray();
            if (empty($package)) {
                return redirect()->to(substr(current_url(), 0, -strlen($id)));
            }

            $list_gallery = $this->galleryPackageModel->get_gallery($id)->getResultArray();
            $galleries = array();
            foreach ($list_gallery as $gallery) {
                $galleries[] = $gallery['url'];
            }
            $package['gallery'] = $galleries;

            $detailPackage = $this->detailPackageModel->get_detailPackage_by_id($id)->getResultArray();
            $getday = $this->packageDayModel->get_list_package_day($id)->getResultArray();
            $combinedData = $this->detailPackageModel->getCombinedData($id);

            $datapackage = [
                'title' => $package['name'],
                'check_in' => $check_in,
                'homestay_name' => $homestay_name,
                'lat' => $lat,
                'lng' => $lng,
                'data' => $package,
                'data2' => $contents2,
                'day' => $getday,
                'activity' => $combinedData,
                'folder' => 'package'
            ];

            $packages[] = $datapackage;
        }

        $data = [
            'title' => 'Explore Sumpu',
            'data' => $kecamatans,
            'data2' => $contents2,
            'datapackage' => $packages,

        ];

        return view('maps/explore_village_mypackage', $data);
    }
}
