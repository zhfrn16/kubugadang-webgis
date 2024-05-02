<?php

namespace App\Controllers\Web;

use App\Models\PackageTypeModel;
use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\Files\File;

class PackageType extends ResourcePresenter
{
    protected $packageTypeModel;

    /**
     * Instance of the main Request object.
     *
     * @var HTTP\IncomingRequest
     */
    protected $request;

    protected $helpers = ['auth', 'url', 'filesystem'];

    public function __construct()
    {
        $this->packageTypeModel = new PackageTypeModel();
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
        $pt = $this->packageTypeModel->get_package_type_by_id($id)->getRowArray();

        if (empty($pt)) {
            return redirect()->to(substr(current_url(), 0, -strlen($id)));
        }

        $data = [
            'title' => $pt['type_name'],
            'data' => $pt,
        ];

        if (url_is('*dashboard*')) {
            return view('dashboard/detail_packagetype', $data);
        }
    }

    /**
     * Present a view to present a new single resource object
     *
     * @return mixed
     */
    public function new()
    {
        $packageType = $this->packageTypeModel->get_list_package_type()->getResultArray();

        $data = [
            'title' => 'New Package Type',
            'package' => $packageType
        ];
        return view('dashboard/package-type-form', $data);
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

        $id = $this->packageTypeModel->get_new_id();

        $requestData = [
            'id' => $id,
            'type_name' => $request['type_name'],
        ];

        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $addPT = $this->packageTypeModel->add_new_package_type($requestData);

        if ($addPT) {
            return redirect()->to(base_url('dashboard/packagetype'));
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function edit($id = null)
    {
        $pt = $this->packageTypeModel->get_package_type_by_id($id)->getRowArray();

        if (empty($pt)) {
            return redirect()->to('dashboard/packagetype');
        }

        $packageType = $this->packageTypeModel->get_list_package_type()->getResultArray();

        $data = [
            'title' => 'Edit Package Type',
            'data' => $pt,
            'package' => $packageType
        ];
        return view('dashboard/package-type-form', $data);
    }

    public function update($id = null)
    {
        $request = $this->request->getPost();
        $requestData = [
            'id' => $id,
            'type_name' => $request['type_name'],
        ];

        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $updatePT = $this->packageTypeModel->update_package_type($id, $requestData);

        if ($updatePT) {
            return redirect()->to(base_url('dashboard/packagetype') . '/' . $id);
        } else {
            return redirect()->back()->withInput();
        }
    }

  
}
