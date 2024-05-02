<?php

namespace App\Controllers\Web;

use App\Models\FacilityTypeModel;
use App\Models\DetailFacilityTypeModel;
use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\Files\File;

class FacilityType extends ResourcePresenter
{
    protected $facilityTypeModel;

    /**
     * Instance of the main Request object.
     *
     * @var HTTP\IncomingRequest
     */
    protected $request;

    protected $helpers = ['auth', 'url', 'filesystem'];

    public function __construct()
    {
        $this->facilityTypeModel = new FacilityTypeModel();
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
        $ft = $this->facilityTypeModel->get_facility_type_by_id($id)->getRowArray();

        if (empty($ft)) {
            return redirect()->to(substr(current_url(), 0, -strlen($id)));
        }

        $data = [
            'title' => $ft['type'],
            'data' => $ft,
        ];

        if (url_is('*dashboard*')) {
            return view('dashboard/detail_facilitytype', $data);
        }
    }

    /**
     * Present a view to present a new single resource object
     *
     * @return mixed
     */
    public function new()
    {
        $facilityType = $this->facilityTypeModel->get_list_facility_type()->getResultArray();

        $data = [
            'title' => 'New Facility Type',
            'facility' => $facilityType
        ];
        return view('dashboard/facility-type-form', $data);
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

        $id = $this->facilityTypeModel->get_new_id();

        $requestData = [
            'id' => $id,
            'type' => $request['type'],
        ];

        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $addFT = $this->facilityTypeModel->add_new_facility_type($requestData);

        if ($addFT) {
            return redirect()->to(base_url('dashboard/facilitytype') . '/' . $id);
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function edit($id = null)
    {
        $ft = $this->facilityTypeModel->get_facility_type_by_id($id)->getRowArray();

        if (empty($ft)) {
            return redirect()->to('dashboard/facilitytype');
        }

        $facilityType = $this->facilityTypeModel->get_list_facility_type()->getResultArray();

        $data = [
            'title' => 'Edit Facility Type',
            'data' => $ft,
            'facility' => $facilityType
        ];
        return view('dashboard/facility-type-form', $data);
    }

    public function update($id = null)
    {
        $request = $this->request->getPost();
        $requestData = [
            'id' => $id,
            'type' => $request['type'],
        ];

        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $updateFT = $this->facilityTypeModel->update_facility_type($id, $requestData);

        if ($updateFT) {
            return redirect()->to(base_url('dashboard/facilitytype') . '/' . $id);
        } else {
            return redirect()->back()->withInput();
        }
    }

  
}
