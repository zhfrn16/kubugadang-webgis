<?php

namespace App\Controllers\Web;

use App\Models\SumpuModel;
use App\Models\ProvinsiModel;
use App\Models\GallerySumpuModel;
use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\Files\File;

class Sumpu extends ResourcePresenter
{
    protected $sumpuModel;
    protected $gallerySumpuModel;
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
        $this->sumpuModel = new SumpuModel();
        $this->provinsiModel = new ProvinsiModel();
        $this->gallerySumpuModel = new GallerySumpuModel();
    }

    /**
     * Present a view of resource objects
     *
     * @return mixed
     */
    public function index()
    {
        $contents = $this->sumpuModel->get_sumpu()->getResultArray();
        $contents2 = $this->sumpuModel->get_desa_wisata_info()->getResultArray();

        for ($index = 0; $index < count($contents); $index++) {
            $list_gallery = $this->gallerySumpuModel->get_gallery($contents[$index]['id'])->getResultArray();
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
        $contents = $this->sumpuModel->get_sumpu()->getRowArray();
        $contents2 = $this->sumpuModel->get_desa_wisata_info()->getResultArray();

        if (empty($contents)) {
            return redirect()->to('dashboard/sumpu');
        }

        $list_gallery = $this->gallerySumpuModel->get_gallery($id)->getResultArray();
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

        $updateVillage = $this->sumpuModel->update_sumpu($id, $requestData);

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
            if ($this->gallerySumpuModel->isGalleryExist($id)) {
                // Update gallery with the new or existing file names
                $this->gallerySumpuModel->update_gallery($id, $gallery);
            } else {
                // Add new gallery if it doesn't exist
                $this->gallerySumpuModel->add_new_gallery($id, $gallery);
            }
        } else {
            // Delete gallery if no files are uploaded
            $this->gallerySumpuModel->delete_gallery($id);
        }

        if ($updateVillage) {
            return redirect()->to(base_url('dashboard/sumpu'));
        } else {
            return redirect()->back()->withInput();
        }
    }
}
