<?php

namespace App\Controllers\Web;

use App\Models\ServicePackageModel;
use App\Models\DetailServicePackageModel;
use App\Models\PackageModel;
use App\Models\DetailPackageModel;
use App\Models\PackageDayModel;
use App\Models\KubuGadangModel;

use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\Files\File;

class Servicepackage extends ResourcePresenter
{
    protected $servicePackageModel;
    protected $detailServicePackageModel;
    protected $packageModel;
    protected $detailPackageModel;
    protected $packageDayModel;
    protected $KubuGadangModel;


    /**
     * Instance of the main Request object.
     *
     * @var HTTP\IncomingRequest
     */
    protected $request;

    protected $helpers = ['auth', 'url', 'filesystem'];

    public function __construct()
    {
        $this->servicePackageModel = new ServicePackageModel();
        $this->detailServicePackageModel = new DetailServicePackageModel();
        $this->packageModel = new PackageModel();
        $this->detailPackageModel = new DetailPackageModel();
        $this->packageDayModel = new PackageDayModel();
        $this->KubuGadangModel = new KubuGadangModel();

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
        $contents2 = $this->KubuGadangModel->get_desa_wisata_info()->getResultArray();

        $sp = $this->servicePackageModel->get_servicePackage_by_id($id)->getRowArray();

        if (empty($sp)) {
            return redirect()->to(substr(current_url(), 0, -strlen($id)));
        }

        $data = [
            'title' => $sp['name'],
            'data' => $sp,
            'data2' => $contents2,

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
    public function new()
    {
        $contents2 = $this->KubuGadangModel->get_desa_wisata_info()->getResultArray();

        $servicePackage = $this->servicePackageModel->get_list_service_package()->getResultArray();

        $data = [
            'title' => 'New Service Package',
            'facility' => $servicePackage,
            'data2' => $contents2,

        ];
        return view('dashboard/service-package-form', $data);
    }

    /**
     * Process the creation/insertion of a new resource object.
     * This should be a POST.
     *
     * @return mixed
     */
    public function create()
    {
        $request = $this->request->getPost();

        $id = $this->servicePackageModel->get_new_id();

        $requestData = [
            'id' => $id,
            'name' => $request['name'],
            'category' => $request['category'],
            'min_capacity' => $request['min_capacity'],
            'price' => $request['price'],
        ];

        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $addSP = $this->servicePackageModel->add_new_servicePackage($requestData);

        if ($addSP) {
            // return redirect()->back();
            // return redirect()->to(base_url('dashboard/servicepackage'));
            return redirect()->to(base_url('dashboard/servicepackage'));
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function edit($id = null)
    {
        $contents2 = $this->KubuGadangModel->get_desa_wisata_info()->getResultArray();

        $sp = $this->servicePackageModel->get_servicePackage_by_id($id)->getRowArray();

        if (empty($sp)) {
            return redirect()->to('dashboard/service-package');
        }

        $servicePackage = $this->servicePackageModel->get_list_service_package()->getResultArray();

        $data = [
            'title' => 'Edit Service Package',
            'data' => $sp,
            'facility' => $servicePackage,
            'data2' => $contents2,

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

        $updateSP = $this->servicePackageModel->update_servicePackage($id, $requestData);

        if ($updateSP) {
            return redirect()->back();
        } else {
            return redirect()->back()->withInput();
        }
    }


    public function createservicepackage($id)
    {
        $request = $this->request->getPost();

        $requestData = [
            'service_package_id' => $request['id_service'],
            'status' => $request['status_service'],
            'package_id' => $id,
            'status_created' => 1,
        ];

        $checkExistingData = $this->detailServicePackageModel->checkIfDataExists($requestData);

        if ($checkExistingData) {
            // Data sudah ada, set pesan error flash data
            session()->setFlashdata('failed', 'This service already exists.');

            return redirect()->back()->withInput();
        } else {
            // Jika service_package_id bukan S016 (homestay), jalankan query insert tanpa syarat
            $addSP = $this->detailServicePackageModel->add_new_detail_service($id, $requestData);

            if ($addSP) {
                $combinedDataPrice = $this->detailPackageModel->getCombinedDataPrice($id);
                $combinedServicePrice = $this->detailServicePackageModel->getCombinedServicePrice($id);

                $requestDataPrice = [
                    'id' => $id,
                    'price' => $combinedDataPrice + $combinedServicePrice
                ];
                $updatePA = $this->packageModel->update_package_price($id, $requestDataPrice);

                session()->setFlashdata('success', 'The service package has been successfully added.');

                return redirect()->back();
            } else {
                return redirect()->back()->withInput();
            }
        }
    }


    public function delete($id = null)
    {
        $request = $this->request->getPost();
    
        $package_id = $request['package_id'];
        $service_package_id = $request['service_package_id'];
        $name = $request['name'];
        $status = $request['status'];
    
        $array = array('package_id' => $package_id, 'service_package_id' => $service_package_id, 'status' => $status);
        $detailServicePackage = $this->detailServicePackageModel->where($array)->find();
        $deleteDSP = $this->detailServicePackageModel->where($array)->delete();
    
        if ($deleteDSP) {
            $combinedDataPrice = $this->detailPackageModel->getCombinedDataPrice($package_id);
            $combinedServicePrice = $this->detailServicePackageModel->getCombinedServicePrice($package_id);

            $requestDataPrice = [
                'id' => $package_id,
                'price' => $combinedDataPrice + $combinedServicePrice
            ];
            $updatePA = $this->packageModel->update_package_price($id, $requestDataPrice);
    
            if ($updatePA) {
                session()->setFlashdata('success', 'The service package has been successfully deleted.');
            } else {
                session()->setFlashdata('error', 'Failed to update package price.');
            }
    
            return redirect()->back();
        } else {
            session()->setFlashdata('error', 'Failed to delete service package.');
            return redirect()->back()->withInput();
        }
    }

    public function deleteobject($id = null)
    {
        $request = $this->request->getPost();  

        $id = $request['id'];    
        $array1 = array('id' => $id);
        $deleteSP = $this->servicePackageModel->where($array1)->delete();

        if ($deleteSP) {
            $response = [
                'status' => 200,
                'message' => [
                    "Success delete Service Package"
                ]
            ];
            session()->setFlashdata('success', 'Service Package "' . $id . '" Deleted Successfully.');

            return redirect()->to(base_url('dashboard/servicepackage'));

        } else {
            $response = [
                'status' => 404,
                'message' => [
                    "Service Package failed to delete"
                ]
            ];
            return $this->failNotFound($response);
        }

    }
    
}
