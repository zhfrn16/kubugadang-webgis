<?php

namespace App\Controllers\Web;

use App\Models\TraditionalHouseModel;
use App\Models\GalleryTraditionalHouseModel;
use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\Files\File;

class TraditionalHouse extends ResourcePresenter
{
    protected $traditionalHouseModel;
    protected $galleryTraditionalHouseModel;

    protected $helpers = ['auth', 'url', 'filesystem'];

    public function __construct()
    {
        $this->traditionalHouseModel = new TraditionalHouseModel();
        $this->galleryTraditionalHouseModel = new GalleryTraditionalHouseModel();
    }

    /**
     * Present a view of resource objects
     *
     * @return mixed
     */
    public function index()
    {
    }

    public function new()
    {
        $traditionalhouse = $this->traditionalHouseModel->get_list_th()->getResultArray();

        $data = [
            'title' => 'New Traditional House',
            'traditionalhouse' => $traditionalhouse
        ];
        return view('dashboard/traditional-form', $data);
    }

    /**
     * Present a view to present a specific resource object
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $th = $this->traditionalHouseModel->get_th_by_id($id)->getRowArray();

        if (empty($th)) {
            return redirect()->to(substr(current_url(), 0, -strlen($id)));
        }

        $list_gallery = $this->galleryTraditionalHouseModel->get_gallery($id)->getResultArray();
        $galleries = array();
        foreach ($list_gallery as $gallery) {
            $galleries[] = $gallery['url'];
        }
        $th['gallery'] = $galleries;

        $data = [
            'title' => $th['name'],
            'data' => $th,
            'folder' => 'traditional_house'
        ];
        if (url_is('*dashboard*')) {
            return view('dashboard/detail_traditional_house', $data);
        }
        return view('web/detail_traditional_house', $data);
    }

    public function create()
    {
        $request = $this->request->getPost();

        $id = $this->traditionalHouseModel->get_new_id();

        $requestData = [
            'id' => $id,
            'name' => $request['name'],
            'ticket_price' => $request['ticket_price'],
            'address' => $request['address'],
            'contact_person' => $request['contact_person'],
            'open' => $request['open'],
            'close' => $request['close'],
            'description' => $request['description'],
        ];
        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $geom = $request['multipolygon'];
        $geojson = $request['geo-json'];

        $addFC = $this->traditionalHouseModel->add_new_th($requestData, $geom);

        if (isset($request['gallery'])) {
            $folders = $request['gallery'];
            $gallery = array();
            foreach ($folders as $folder) {
                $filepath = WRITEPATH . 'uploads/' . $folder;
                $filenames = get_filenames($filepath);
                $fileImg = new File($filepath . '/' . $filenames[0]);
                $fileImg->move(FCPATH . 'media/photos/traditional_house');
                delete_files($filepath);
                rmdir($filepath);
                $gallery[] = $fileImg->getFilename();
            }
            $this->galleryTraditionalHouseModel->add_new_gallery($id, $gallery);
        }

        if ($addFC) {
            return redirect()->to(base_url('dashboard/traditionalhouse'));
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function edit($id = null)
    {
        $th = $this->traditionalHouseModel->get_th_by_id($id)->getRowArray();
        if (empty($th)) {
            return redirect()->to('dashboard/traditionalhouse');
        }

        $list_gallery = $this->galleryTraditionalHouseModel->get_gallery($id)->getResultArray();
        $galleries = array();
        foreach ($list_gallery as $gallery) {
            $galleries[] = $gallery['url'];
        }
        $th['gallery'] = $galleries;

        $data = [
            'title' => 'Edit Traditional House ',
            'data' => $th
        ];

        // dd($data);
        return view('dashboard/traditional-form', $data);
    }

    public function update($id = null)
    {
        $request = $this->request->getPost();
        $requestData = [
            'id' => $id,
            'name' => $request['name'],
            'ticket_price' => $request['ticket_price'],
            'address' => $request['address'],
            'contact_person' => $request['contact_person'],
            'open' => $request['open'],
            'close' => $request['close'],
            'description' => $request['description'],
        ];
        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $geom = $request['multipolygon'];
        // $geojson = $request['geo-json'];

        $updateTH= $this->traditionalHouseModel->update_th($id, $requestData);
        $updateGeom = $this->traditionalHouseModel->update_geom($id, $geom);

        // Handle gallery files
        if (isset($request['gallery'])) {
            $folders = $request['gallery'];
            $gallery = array();
            foreach ($folders as $folder) {
                $filepath = WRITEPATH . 'uploads/' . $folder;
                $filenames = get_filenames($filepath);
                $fileImg = new File($filepath . '/' . $filenames[0]);
    
                // Remove old file with the same name, if exists
                $existingFile = FCPATH . 'media/photos/traditional_house/' . $fileImg->getFilename();
                if (file_exists($existingFile)) {
                    unlink($existingFile);
                }
    
                $fileImg->move(FCPATH . 'media/photos/traditional_house');
                delete_files($filepath);
                rmdir($filepath);
                $gallery[] = $fileImg->getFilename();
            }
    
            // Update or add gallery data
            if ($this->galleryTraditionalHouseModel->isGalleryExist($id)) {
                // Update gallery with the new or existing file names
                $this->galleryTraditionalHouseModel->update_gallery($id, $gallery);
            } else {
                // Add new gallery if it doesn't exist
                $this->galleryTraditionalHouseModel->add_new_gallery($id, $gallery);
            }
        } else {
            // Delete gallery if no files are uploaded
            $this->galleryTraditionalHouseModel->delete_gallery($id);
        }

        if ($updateTH) {
            return redirect()->to(base_url('dashboard/traditionalhouse') . '/' . $id);
        } else {
            return redirect()->back()->withInput();
        }
    }
}
