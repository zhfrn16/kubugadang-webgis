<?php

namespace App\Controllers\Web;

use App\Models\KubuGadangModel;
use App\Models\ProvinsiModel;
use App\Models\GalleryKubuGadangModel;
use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\Files\File;

class Kubugadang extends ResourcePresenter
{
    protected $KubuGadangModel;
    protected $galleryKubuGadangModel;
    protected $provinsiModel;

    /**
     * Instance of the main Request object.
     *
     * @var HTTP\IncomingRequest
     */
    protected $request;

    protected $helpers = ['auth', 'url', 'filesystem'];

    public function __construct()
    {
        $this->KubuGadangModel = new KubuGadangModel();
        $this->provinsiModel = new ProvinsiModel();
        $this->galleryKubuGadangModel = new GalleryKubuGadangModel();
    }

    /**
     * Present a view of resource objects
     *
     * @return mixed
     */
    public function index()
    {
        $contents = $this->KubuGadangModel->get_sumpu()->getResultArray();
        $contents2 = $this->KubuGadangModel->get_desa_wisata_info()->getResultArray();
        $contents3 = $this->KubuGadangModel->get_announcement_info()->getResultArray();

        for ($index = 0; $index < count($contents); $index++) {
            $list_gallery = $this->galleryKubuGadangModel->get_gallery($contents[$index]['id'])->getResultArray();
            $galleries = array();
            foreach ($list_gallery as $gallery) {
                $galleries[] = $gallery['url'];
            }
            $contents[$index]['gallery'] = $galleries;
        }

        $data = [
            'title' => 'Home',
            'data' => $contents,
            'data2' => $contents2,
            'data3' => $contents3,

        ];
        $data2 = [
            'title' => 'Home',
            'data2' => $contents2,
        ];


        return view('web/info_home', $data);
        return view('web/layouts/header', $data2);
    }

    public function edit($id = null)
    {
        $contents = $this->KubuGadangModel->get_sumpu()->getRowArray();
        $contents2 = $this->KubuGadangModel->get_desa_wisata_info()->getResultArray();

        if (empty($contents)) {
            return redirect()->to('dashboard/sumpu');
        }

        $list_gallery = $this->galleryKubuGadangModel->get_gallery($id)->getResultArray();
        $galleries = array();
        foreach ($list_gallery as $gallery) {
            $galleries[] = $gallery['url'];
        }
        $contents['gallery'] = $galleries;

        $data = [
            'title' => 'Edit Village Information',
            'data' => $contents,
            'data2' => $contents2,
        ];
        return view('dashboard/sumpu-form', $data);
    }

    public function update($id = null)
    {
        $request = $this->request->getPost();
        $requestData = [
            'id' => $id,
            'name' => $request['name'],
            'type_of_tourism' => $request['type_of_tourism'],
            'address' => $request['address'],
            'open' => $request['open'],
            'close' => $request['close'],
            'ticket_price' => $request['ticket_price'],
            'contact_person' => $request['contact_person'],
            'bank_name' => $request['bank_name'],
            'bank_code' => $request['bank_code'],
            'bank_account_holder' => $request['bank_account_holder'],
            'bank_account_number' => $request['bank_account_number'],
        ];
        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }
        if (isset($request['qr'])) {
            $folder = $request['qr'];
            $filepath = WRITEPATH . 'uploads/' . $folder;
            $filenames = get_filenames($filepath);
            $qrFile = new File($filepath . '/' . $filenames[0]);
            $qrFile->move(FCPATH . 'media/photos/sumpu');
            delete_files($filepath);
            rmdir($filepath);
            $requestData['qr_url'] = $qrFile->getFilename();
        }

        $updateVillage = $this->KubuGadangModel->update_sumpu($id, $requestData);

        // Handle gallery files
        if (isset($request['gallery'])) {
            $folders = $request['gallery'];
            $gallery = array();
            foreach ($folders as $folder) {
                $filepath = WRITEPATH . 'uploads/' . $folder;
                $filenames = get_filenames($filepath);
                $fileImg = new File($filepath . '/' . $filenames[0]);

                // Remove old file with the same name, if exists
                $existingFile = FCPATH . 'media/photos/sumpu/' . $fileImg->getFilename();
                if (file_exists($existingFile)) {
                    unlink($existingFile);
                }

                $fileImg->move(FCPATH . 'media/photos/sumpu');
                delete_files($filepath);
                rmdir($filepath);
                $gallery[] = $fileImg->getFilename();
            }

            // Update or add gallery data
            if ($this->galleryKubuGadangModel->isGalleryExist($id)) {
                // Update gallery with the new or existing file names
                $this->galleryKubuGadangModel->update_gallery($id, $gallery);
            } else {
                // Add new gallery if it doesn't exist
                $this->galleryKubuGadangModel->add_new_gallery($id, $gallery);
            }
        } else {
            // Delete gallery if no files are uploaded
            $this->galleryKubuGadangModel->delete_gallery($id);
        }

        if ($updateVillage) {
            return redirect()->to(base_url('dashboard/sumpu'));
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function createannouncement()
    {
        $request = $this->request->getPost();

        $id = $this->KubuGadangModel->get_new_announcement_id();

        $requestData = [
            'id' => $id,
            'admin_id' => user()->id,
            'announcement' => $request['announcement'],
            'status' => $request['status'],
        ];

        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $addAN = $this->KubuGadangModel->add_new_announcement($requestData);

        if ($addAN) {
            return redirect()->back();
            // return redirect()->to(base_url('dashboard/servicepackage'));
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function updateannouncement($id = null)
    {
        $request = $this->request->getPost();
       

        $requestData = [
            'announcement' => $request['announcement'],
            'status' => $request['status'],
        ];


        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }



        $updateAN = $this->KubuGadangModel->update_announcement($id, $requestData);

        if ($updateAN) {
            return redirect()->back();
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function deleteobject($id = null)
    {
        $request = $this->request->getPost();  

        $id = $request['id'];    
        $array1 = array('id' => $id);
        $deleteAN = $this->KubuGadangModel->delete_announcement($id);

        if ($deleteAN) {
            $response = [
                'status' => 200,
                'message' => [
                    "Success delete Announcement"
                ]
            ];
            session()->setFlashdata('success', 'Announcement "' . $id . '" Deleted Successfully.');

            return redirect()->to(base_url('dashboard/homestay'));

        } else {
            $response = [
                'status' => 404,
                'message' => [
                    "Homestay failed to delete"
                ]
            ];
            return $this->failNotFound($response);
        }
    }

    
}
