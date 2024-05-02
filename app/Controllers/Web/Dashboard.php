<?php

namespace App\Controllers\Web;

use App\Models\GtpModel;
use App\Models\SumpuModel;
use Myth\Auth\Models\UserModel;
use App\Models\GalleryGtpModel;
use App\Models\GallerySumpuModel;
use App\Models\AttractionModel;
use App\Models\EventModel;
use App\Models\PackageModel;
use App\Models\PackageTypeModel;

use App\Models\FacilityModel;
use App\Models\FacilityTypeModel;

use App\Models\CulinaryPlaceModel;
use App\Models\TraditionalHouseModel;
use App\Models\WorshipPlaceModel;
use App\Models\SouvenirPlaceModel;
use App\Models\ServicePackageModel;
use App\Models\HomestayModel;
use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    protected $gtpModel;
    protected $sumpuModel;
    protected $userModel;
    protected $galleryGtpModel;
    protected $gallerySumpuModel;
    protected $attractionModel;
    protected $eventModel;
    protected $packageModel;
    protected $packageTypeModel;

    protected $facilityModel;
    protected $facilityTypeModel;

    protected $souvenirPlaceModel;
    protected $culinaryPlaceModel;
    protected $traditionalHouseModel;
    protected $worshipPlaceModel;
    protected $servicePackageModel;
    protected $homestayModel;

    public function __construct()
    {
        $this->gtpModel = new GtpModel();
        $this->sumpuModel = new SumpuModel();
        $this->galleryGtpModel = new GalleryGtpModel();
        $this->gallerySumpuModel = new GallerySumpuModel();
        $this->userModel = new UserModel();
        $this->attractionModel = new AttractionModel();
        $this->eventModel = new EventModel();
        $this->packageModel = new PackageModel();
        $this->packageTypeModel = new PackageTypeModel();

        $this->facilityModel = new FacilityModel();
        $this->facilityTypeModel = new FacilityTypeModel();

        $this->culinaryPlaceModel = new CulinaryPlaceModel();
        $this->traditionalHouseModel = new TraditionalHouseModel();
        $this->souvenirPlaceModel = new SouvenirPlaceModel();
        $this->worshipPlaceModel = new WorshipPlaceModel();
        $this->servicePackageModel = new ServicePackageModel();
        $this->homestayModel = new HomestayModel();

    }
    
    public function index()
    {
        $data = [
            'title' => 'Dashboard',
        ];
        return view('dashboard/analytics', $data);
    }

    // public function gtp()
    // {
    //     $contents = $this->gtpModel->get_gtp()->getRowArray();

    //     $list_gallery = $this->galleryGtpModel->get_all_gallery()->getResultArray();
    //     $galleries = array();
    //     foreach ($list_gallery as $gallery) {
    //         $galleries[] = $gallery['url'];
    //     }
    //     $contents['gallery'] = $galleries;

    //     $data = [
    //         'title' => 'Manage GTP Information',
    //         'data' => $contents,
    //         'folder' => 'gtp'
    //     ];
    //     return view('dashboard/manage-gtp', $data);
    // }

    public function sumpu()
    {
        $contents = $this->sumpuModel->get_sumpu()->getRowArray();
        $contents2 = $this->sumpuModel->get_desa_wisata_info()->getResultArray();

        $list_gallery = $this->gallerySumpuModel->get_all_gallery()->getResultArray();
        $galleries = array();
        foreach ($list_gallery as $gallery) {
            $galleries[] = $gallery['url'];
        }
        $contents['gallery'] = $galleries;

        $data = [
            'title' => 'Manage Village Information',
            'data' => $contents,
            'data2' => $contents2,
            'folder' => 'sumpu'
        ];
        return view('dashboard/manage-sumpu', $data);
    }

    public function users()
    {
        $contentsAdmin = $this->userModel->get_admin()->getResultArray();
        $contentsCostumer = $this->userModel->get_users()->getResultArray();
        $contents2 = $this->sumpuModel->get_desa_wisata_info()->getResultArray();

        $data = [
            'title' => 'Manage Users',
            'manage' => 'Users',
            'adminData' => $contentsAdmin,
            'customerData' => $contentsCostumer,
            'data2' => $contents2,

        ];
        // DD($data);
        return view('dashboard/manage-users', $data);
    }

    public function attraction()
    {
        $contents = $this->attractionModel->get_list_attraction()->getResultArray();
        $contents2 = $this->sumpuModel->get_desa_wisata_info()->getResultArray();

        $data = [
            'title' => 'Manage Attraction',
            'manage' => 'Attraction',
            'data' => $contents,
            'data2' => $contents2,

        ];
        return view('dashboard/manage-page', $data);
    }

    public function event()
    {
        $contents = $this->eventModel->get_list_event()->getResultArray();
        $contents2 = $this->sumpuModel->get_desa_wisata_info()->getResultArray();

        $data = [
            'title' => 'Manage Event',
            'manage' => 'Event',
            'data' => $contents,
            'data2' => $contents2,

        ];
        return view('dashboard/manage-page', $data);
    }

    public function package()
    {
        $contents = $this->packageModel->get_list_package()->getResultArray();
        $contents2 = $this->sumpuModel->get_desa_wisata_info()->getResultArray();

        $data = [
            'title' => 'Manage Package',
            'manage' => 'Package',
            'data' => $contents,
            'data2' => $contents2,

        ];
        return view('dashboard/manage-page', $data);
    }

    public function facility()
    {
        $contents = $this->facilityModel->get_list_facility()->getResultArray();
        $contents2 = $this->sumpuModel->get_desa_wisata_info()->getResultArray();

        $data = [
            'title' => 'Manage Facility',
            'manage' => 'Facility',
            'data' => $contents,
            'data2' => $contents2,

        ];
        return view('dashboard/manage-page', $data);
    }
    
    
    public function culinaryplace()
    {
        $contents = $this->culinaryPlaceModel->get_list_cp()->getResultArray();
        $contents2 = $this->sumpuModel->get_desa_wisata_info()->getResultArray();

        $data = [
            'title' => 'Manage Culinary Place',
            'manage' => 'Culinary Place',
            'data' => $contents,
            'data2' => $contents2,

        ];
        return view('dashboard/manage-page', $data);
    }

    
    public function traditionalhouse()
    {
        $contents = $this->traditionalHouseModel->get_list_th()->getResultArray();
        $contents2 = $this->sumpuModel->get_desa_wisata_info()->getResultArray();

        $data = [
            'title' => 'Manage Traditional House',
            'manage' => 'Traditional House',
            'data' => $contents,
            'data2' => $contents2,

        ];
        return view('dashboard/manage-page', $data);
    }

    

    public function souvenirplace()
    {
        $contents = $this->souvenirPlaceModel->get_list_sp()->getResultArray();
        $contents2 = $this->sumpuModel->get_desa_wisata_info()->getResultArray();

        $data = [
            'title' => 'Manage Souvenir Place',
            'manage' => 'Souvenir Place',
            'data' => $contents,
            'data2' => $contents2,

        ];
        return view('dashboard/manage-page', $data);
    }

    public function worshipplace()
    {
        $contents = $this->worshipPlaceModel->get_list_wp()->getResultArray();
        $contents2 = $this->sumpuModel->get_desa_wisata_info()->getResultArray();

        $data = [
            'title' => 'Manage Worship Place',
            'manage' => 'Worship Place',
            'data' => $contents,
            'data2' => $contents2,

        ];
        return view('dashboard/manage-page', $data);
    }

    public function servicepackage()
    {
        $contents = $this->servicePackageModel->get_list_service_package()->getResultArray();
        $contents2 = $this->sumpuModel->get_desa_wisata_info()->getResultArray();

        $data = [
            'title' => 'Manage Service Package',
            'manage' => 'Service',
            'data' => $contents,
            'data2' => $contents2,

        ];

        return view('dashboard/manage-page', $data);
    }

    public function facilitytype()
    {
        $contents = $this->facilityTypeModel->get_list_facility_type()->getResultArray();
        $contents2 = $this->sumpuModel->get_desa_wisata_info()->getResultArray();

        $data = [
            'title' => 'Manage Facility Type',
            'manage' => 'Facility Type',
            'data' => $contents,
            'data2' => $contents2,

        ];

        return view('dashboard/manage-page', $data);
    }

    public function packagetype()
    {
        $contents = $this->packageTypeModel->get_list_package_type()->getResultArray();
        $contents2 = $this->sumpuModel->get_desa_wisata_info()->getResultArray();

        $data = [
            'title' => 'Manage Package Type',
            'manage' => 'Package Type',
            'data' => $contents,
            'data2' => $contents2,

        ];

        return view('dashboard/manage-page', $data);
    }

    public function homestay()
    {
        $contents = $this->homestayModel->get_list_homestay()->getResultArray();
        $contents2 = $this->sumpuModel->get_desa_wisata_info()->getResultArray();

        $data = [
            'title' => 'Manage Homestay',
            'manage' => 'Homestay',
            'data' => $contents,
            'data2' => $contents2,

        ];

        return view('dashboard/manage-homestay', $data);
    }
    
}
