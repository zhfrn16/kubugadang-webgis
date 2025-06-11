<?php

namespace App\Controllers\Web;

use App\Models\AttractionModel;
use App\Models\GalleryAttractionModel;
use App\Models\KubuGadangModel;
use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\Files\File;

class Attraction extends ResourcePresenter
{
    protected $attractionModel;
    protected $galleryAttractionModel;
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
        $this->KubuGadangModel = new KubuGadangModel();
        $this->attractionModel = new AttractionModel();
        $this->galleryAttractionModel = new GalleryAttractionModel();
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
        $attraction = $this->attractionModel->get_list_attraction()->getResultArray();

        $data = [
            'title' => 'New Attraction',
            'attraction' => $attraction
        ];
        return view('dashboard/attraction-form', $data);
    }


    public function show($id = null)
    {
        $attraction = $this->attractionModel->get_attraction_by_id($id)->getRowArray();
        $contents2 = $this->KubuGadangModel->get_desa_wisata_info()->getResultArray();

        if (empty($attraction)) {
            return redirect()->to(substr(current_url(), 0, -strlen($id)));
        }

        $list_gallery = $this->galleryAttractionModel->get_gallery($id)->getResultArray();
        $galleries = array();
        foreach ($list_gallery as $gallery) {
            $galleries[] = $gallery['url'];
        }
        $attraction['gallery'] = $galleries;

        $data = [
            'title' => $attraction['name'],
            'data' => $attraction,
            'data2' => $contents2,
            'folder' => 'attraction'
        ];

        if (url_is('*dashboard*')) {
            return view('dashboard/detail_attraction', $data);
        } else{
            return view('web/detail_attraction', $data);
        }
    }

    public function create()
    {
        $request = $this->request->getPost();

        $id = $this->attractionModel->get_new_id();

        $requestData = [
            'id' => $id,
            'name' => $request['name'],
            'type' => $request['type'],
            'price' => $request['price'],
            'description' => $request['description'],
        ];
        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $geom = $request['multipolygon'];
        // $geojson = $request['geo-json'];

        $addAT = $this->attractionModel->add_new_attraction($requestData, $geom);

        if (isset($request['gallery'])) {
            $folders = $request['gallery'];
            $gallery = array();
            foreach ($folders as $folder) {
                $filepath = WRITEPATH . 'uploads/' . $folder;
                $filenames = get_filenames($filepath);
                $fileImg = new File($filepath . '/' . $filenames[0]);
                $fileImg->move(FCPATH . 'media/photos/attraction');
                delete_files($filepath);
                rmdir($filepath);
                $gallery[] = $fileImg->getFilename();
            }
            $this->galleryAttractionModel->add_new_gallery($id, $gallery);
        }

        if ($addAT) {
            return redirect()->to(base_url('dashboard/attraction'));
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function edit($id = null)
    {
        $attraction = $this->attractionModel->get_attraction_by_id($id)->getRowArray();
        if (empty($attraction)) {
            return redirect()->to('dashboard/attraction');
        }

        $list_gallery = $this->galleryAttractionModel->get_gallery($id)->getResultArray();
        $galleries = array();
        foreach ($list_gallery as $gallery) {
            $galleries[] = $gallery['url'];
        }
        $attraction['gallery'] = $galleries;

        $data = [
            'title' => 'Edit Attraction',
            'data' => $attraction,
        ];
        return view('dashboard/attraction-form', $data);
    }

    public function update($id = null)
    {
        $request = $this->request->getPost();
        $requestData = [
            'id' => $id,
            'name' => $request['name'],
            'type' => $request['type'],
            'price' => $request['price'],
            'description' => $request['description']
        ];
        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $geom = $request['multipolygon'];
        // $geojson = $request['geo-json'];

        if (isset($request['video'])) {
            $folder = $request['video'];
            $filepath = WRITEPATH . 'uploads/' . $folder;
            $filenames = get_filenames($filepath);
            $vidFile = new File($filepath . '/' . $filenames[0]);
            $vidFile->move(FCPATH . 'media/videos');
            delete_files($filepath);
            rmdir($filepath);
            $requestData['video_url'] = $vidFile->getFilename();
        } else {
            $requestData['video_url'] = null;
        }
        $updateAT = $this->attractionModel->update_attraction($id, $requestData);
        $updateGeom = $this->attractionModel->update_geom($id, $geom);

        if (isset($request['gallery'])) {
            $folders = $request['gallery'];
            $gallery = array();
            foreach ($folders as $folder) {
                $filepath = WRITEPATH . 'uploads/' . $folder;
                $filenames = get_filenames($filepath);
                $fileImg = new File($filepath . '/' . $filenames[0]);
                $fileImg->move(FCPATH . 'media/photos/attraction');
                delete_files($filepath);
                rmdir($filepath);
                $gallery[] = $fileImg->getFilename();
            }
            $this->galleryAttractionModel->update_gallery($id, $gallery);
        } else {
            $this->galleryAttractionModel->delete_gallery($id);
        }

        if ($updateAT) {
            return redirect()->to(base_url('dashboard/attraction') . '/' . $id);
        } else {
            return redirect()->back()->withInput();
        }
    }
}
