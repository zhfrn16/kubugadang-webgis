<?php

namespace App\Controllers\Web;

use App\Models\PackageDayModel;
use App\Models\PackageModel;
use App\Models\KubuGadangModel;
use App\Models\DetailPackageModel;
use App\Models\DetailServicePackageModel;

use App\Models\CulinaryPlaceModel;
use App\Models\TraditionalHouseModel;
use App\Models\WorshipPlaceModel;
use App\Models\FacilityModel;
use App\Models\SouvenirPlaceModel;
use App\Models\AttractionModel;
use App\Models\EventModel;
use App\Models\HomestayModel;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\Files\File;

class PackageDay extends ResourcePresenter
{

    use ResponseTrait;

    protected $packageDayModel;
    protected $packageModel;
    protected $KubuGadangModel;
    protected $detailPackageModel;
    protected $culinaryPlaceModel;
    protected $traditionalHouseModel;
    protected $worshipPlaceModel;
    protected $facilityModel;
    protected $souvenirPlaceModel;
    protected $attractionModel;
    protected $eventModel;
    protected $homestayModel;
    protected $detailServicePackageModel;

    protected $db, $builder;


    /**
     * Instance of the main Request object.
     *
     * @var HTTP\IncomingRequest
     */
    protected $request;

    protected $helpers = ['auth', 'url', 'filesystem'];

    public function __construct()
    {
        $this->packageDayModel = new PackageDayModel();
        $this->packageModel = new PackageModel();
        $this->KubuGadangModel = new KubuGadangModel();
        $this->detailPackageModel = new DetailPackageModel();
        $this->culinaryPlaceModel = new CulinaryPlaceModel();
        $this->traditionalHouseModel = new TraditionalHouseModel();
        $this->worshipPlaceModel = new WorshipPlaceModel();
        $this->facilityModel = new FacilityModel();
        $this->souvenirPlaceModel = new SouvenirPlaceModel();
        $this->attractionModel = new AttractionModel();
        $this->eventModel = new EventModel();
        $this->homestayModel = new HomestayModel();
        $this->detailServicePackageModel = new DetailServicePackageModel();

        $this->db = \Config\Database::connect();
        $this->builder = $this->db->table('detail_package', 'culinary_place');;
    }

    /**
     * Present a view of resource objects
     *
     * @return mixed
     */
    public function index()
    {
    }

    public function show($id = null)
    {
        $sp = $this->packageDayModel->get_servicePackage_by_id($id)->getRowArray();

        if (empty($sp)) {
            return redirect()->to(substr(current_url(), 0, -strlen($id)));
        }

        $data = [
            'title' => $sp['name'],
            'data' => $sp,
        ];

        if (url_is('*dashboard*')) {
            return view('dashboard/detail_servicepackage', $data);
        }
    }

    /**
     * Present a view to present a new single resource object
     *
     * @return mixed
     */
    public function newday($id)
    {
        $contents2 = $this->KubuGadangModel->get_desa_wisata_info()->getResultArray();

        $package = $this->packageModel->get_package_by_id($id)->getRowArray();
        $package_id = $package['id'];
        $packageDay = $this->packageDayModel->get_package_day_by_id($package_id)->getResultArray();

        $culinary = $this->culinaryPlaceModel->get_list_cp()->getResultArray();
        $traditional = $this->traditionalHouseModel->get_list_th()->getResultArray();
        $worship = $this->worshipPlaceModel->get_list_wp()->getResultArray();
        $facility = $this->facilityModel->get_list_facility()->getResultArray();
        $souvenir = $this->souvenirPlaceModel->get_list_sp()->getResultArray();
        $attraction = $this->attractionModel->get_list_attraction()->getResultArray();
        $event = $this->eventModel->get_list_event()->getResultArray();
        $homestay = $this->homestayModel->get_list_homestay_homestay()->getResultArray();

        $data_object = array_merge($culinary, $worship, $facility, $souvenir, $attraction, $event, $homestay);

        $detailPackage = $this->detailPackageModel->get_detailPackage_by_id($package_id)->getResultArray();

        $combinedData = $this->detailPackageModel->getCombinedData($package_id);
        $combinedDataPrice = $this->detailPackageModel->getCombinedDataPrice($package_id);

        $object = [
            'culinary' => $culinary,
            'traditional' => $traditional,
            'worship' => $worship,
            'facility' => $facility,
            'souvenir' => $souvenir,
            'attraction' => $attraction,
            'event' => $event,
            'homestay' => $homestay
        ];

        $data = [
            'title' => 'Detail Package ' . $package['name'],
            'data' => $package,
            'data2' => $contents2,
            'day' => $packageDay,
            'activity' => $detailPackage,
            'data_package' => $combinedData,
            'expectedPrice' => $combinedDataPrice, // Ubah nama variabel di sini
            'object' => $object
        ];
        // dd( $data);

        return view('dashboard/detail-package-form', $data, $object);
    }

    /**
     * Process the creation/insertion of a new resource object.
     * This should be a POST.
     *
     * @return mixed
     */
    public function createday($id)
    {

        $request = $this->request->getPost();

        $requestData = [
            'package_id' => $id,
            'day' => $request['day'],
            'description' => $request['description']
        ];

        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }
        $checkExistingData = $this->packageDayModel->checkIfDataExists($requestData);

        if ($checkExistingData) {
            // Data sudah ada, set pesan error flash data
            session()->setFlashdata('failed', 'The data for that day is already available.');

            return redirect()->back()->withInput();
        } else {
            // Data belum ada, jalankan query insert
            $addPD = $this->packageDayModel->add_new_packageDay($requestData);

            if ($addPD) {
                session()->setFlashdata('success', 'The data for the day has been added.');

                $package = $this->packageModel->get_package_by_id($id)->getRowArray();
                $id = $package['id'];
                $data = [
                    'title' => 'New Detail Package',
                    'data' => $package
                ];
                return redirect()->back();
            } else {
                return redirect()->back()->withInput();
            }
        }
    }

    public function createactivity($id)
    {
        $request = $this->request->getPost();

        $requestData = [
            'package_id' => $id,
            'day' => $request['dayselect'],
            'activity' => $request['activity'],
            'activity_type' => $request['activity_type'],
            'object_id' => $request['object'],
            'description' => $request['description_activity'],
        ];


        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }
        $checkExistingData = $this->detailPackageModel->checkIfDataExists($requestData);

        if ($checkExistingData) {
            // Data sudah ada, set pesan error flash data
            session()->setFlashdata('failed', 'Activity sequence ' . $requestData['activity'] . ' on day ' . $requestData['day'] . ' already available');
            return redirect()->back()->withInput();
        } else {
            // Data belum ada, jalankan query insert
            $addPA = $this->detailPackageModel->add_new_packageActivity($requestData);



            if ($addPA) {

                $combinedDataPrice = $this->detailPackageModel->getCombinedDataPrice($id);
                $combinedServicePrice = $this->detailServicePackageModel->getCombinedServicePrice($id);

                $requestDataPrice = [
                    'id' => $id,
                    'price' => $combinedDataPrice + $combinedServicePrice,
                ];
                $updatePA = $this->packageModel->update_package($id, $requestDataPrice);

                if ($updatePA) {
                    // return view('dashboard/detail-package-form');
                    session()->setFlashdata('success', 'The activity data was added successfully.');

                    return redirect()->back();
                }
            } else {
                return redirect()->back()->withInput();
            }
        }
    }

    public function edit($id = null)
    {
        $contents2 = $this->KubuGadangModel->get_desa_wisata_info()->getResultArray();

        $sp = $this->packageDayModel->get_servicePackage_by_id($id)->getRowArray();
        if (empty($sp)) {
            return redirect()->to('dashboard/service-package');
        }

        $servicePackage = $this->packageDayModel->get_list_service_package()->getResultArray();

        $data = [
            'title' => 'Edit Service Package',
            'data' => $sp,
            'data2' => $contents2,
            'facility' => $servicePackage
        ];
        return view('dashboard/service-package-form', $data);
    }

    public function update($id = null)
    {
        $request = $this->request->getPost();
        $requestData = [
            'id' => $id,
            'name' => $request['name'],
        ];
        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $updateSP = $this->packageDayModel->update_servicePackage($id, $requestData);

        if ($updateSP) {
            return redirect()->to(base_url('dashboard/servicepackage') . '/' . $id);
        } else {
            return redirect()->back()->withInput();
        }
    }

    
    public function deleteday($package_id = null, $day = null, $description = null)
    {
        $request = $this->request->getPost();

        $package_id = $request['package_id'];
        $day = $request['day'];
        $description = $request['description'];

        $array1 = array('package_id' => $package_id, 'day' => $day);
        $detailPackage = $this->detailPackageModel->where($array1)->find();
        $deleteDP = $this->detailPackageModel->where($array1)->delete();

        if ($deleteDP) {
            //jika success
            $array2 = array('package_id' => $package_id, 'day' => $day, 'description' => $description);
            $packageDay = $this->packageDayModel->where($array2)->find();
            // dd($packageDay);
            $deletePD = $this->packageDayModel->where($array2)->delete();

            if ($deletePD) {
                session()->setFlashdata('success', 'Day "' . $description . '" Successfully deleted.');

                $package = $this->packageModel->get_package_by_id($package_id)->getRowArray();
                $package_id = $package['id'];
                $packageDay = $this->packageDayModel->get_package_day_by_id($package_id)->getResultArray();
                $detailPackage = $this->detailPackageModel->get_detailPackage_by_id($package_id, $packageDay)->getResultArray();

                $combinedDataPrice = $this->detailPackageModel->getCombinedDataPrice($package_id);
                $combinedServicePrice = $this->detailServicePackageModel->getCombinedServicePrice($package_id);

                $requestDataPrice = [
                    'id' => $package_id,
                    'price' => $combinedDataPrice + $combinedServicePrice,
                ];
                $updatePA = $this->packageModel->update_package($package_id, $requestDataPrice);

                $data = [
                    'title' => 'New Detail Package',
                    'data' => $package,
                    'day' => $packageDay,
                    'activity' => $detailPackage
                ];

                return redirect()->to(base_url('dashboard/package/edit') . '/' . $package_id);
            }
        } else {
            $response = [
                'status' => 404,
                'message' => [
                    "Package not found"
                ]
            ];
            return $this->failNotFound($response);
        }
    }


    public function delete($package_id = null, $day = null, $activity = null, $description = null)
    {
        $request = $this->request->getPost();

        $day = $request['day'];
        $activity = $request['activity'];
        $description = $request['description'];

        $array = array('package_id' => $package_id, 'day' => $day, 'activity' => $activity);
        $detailPackage = $this->detailPackageModel->where($array)->find();
        $deleteDP = $this->detailPackageModel->where($array)->delete();

        if ($deleteDP) {
            session()->setFlashdata('success', 'Activity "' . $description . '" Successfully deleted.');
            //jika success
            $package = $this->packageModel->get_package_by_id($package_id)->getRowArray();

            $package_id = $package['id'];

            $packageDay = $this->packageDayModel->get_package_day_by_id($package_id)->getResultArray();

            // dd($packageDay);
            // foreach ($packageDay as $item):
            // $dayp=$item['day'];
            $detailPackage = $this->detailPackageModel->get_detailPackage_by_id($package_id, $packageDay)->getResultArray();

            $combinedDataPrice = $this->detailPackageModel->getCombinedDataPrice($package_id);
            $combinedServicePrice = $this->detailServicePackageModel->getCombinedServicePrice($package_id);

            $requestDataPrice = [
                'id' => $package_id,
                'price' => $combinedDataPrice + $combinedServicePrice,
            ];
            $updatePA = $this->packageModel->update_package($package_id, $requestDataPrice);

            $data = [
                'title' => 'New Detail Package',
                'data' => $package,
                'day' => $packageDay,
                'activity' => $detailPackage
            ];

            // endforeach;
            return redirect()->to(base_url('dashboard/package/edit') . '/' . $package_id);

            // return view('dashboard/detail-package-form', $data, $package, $packageDay, $detailPackage);

        } else {
            $response = [
                'status' => 404,
                'message' => [
                    "Package not found"
                ]
            ];
            return $this->failNotFound($response);
        }


        // return redirect()->to('/packageday/P0014');
    }
}
