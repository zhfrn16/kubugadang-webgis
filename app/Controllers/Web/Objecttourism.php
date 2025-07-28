<?php

namespace App\Controllers\Web;

use App\Models\ObjectTourismModel;
use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\Files\File;

class Objecttourism extends ResourcePresenter
{
    protected $objectTourismModel;
    protected $galleryObjectTourismModel;

    protected $helpers = ['auth', 'url', 'filesystem'];

    public function __construct()
    {
        $this->objectTourismModel = new ObjectTourismModel();
        $this->galleryObjectTourismModel = new \App\Models\GalleryObjectTourismModel();
    }

    public function index() {}

    public function new()
    {
        $data = [
            'title' => 'New Object Tourism'
        ];
        return view('dashboard/objecttourism-form', $data);
    }

    public function show($id = null)
    {
        $object = $this->objectTourismModel->get_object_tourism_by_id($id)->getRowArray();

        if (empty($object)) {
            return redirect()->to(substr(current_url(), 0, -strlen($id)));
        }

        $list_gallery = $this->galleryObjectTourismModel->get_gallery($id)->getResultArray();
        $galleries = array();
        foreach ($list_gallery as $gallery) {
            $galleries[] = $gallery['url'];
        }
        $object['gallery'] = $galleries;

        $data = [
            'title' => $object['name'],
            'data' => $object,
            'folder' => 'objecttourism'
        ];

        if (url_is('*dashboard*')) {
            return view('dashboard/detail_objecttourism', $data);
        } else {
            return view('web/detail_objecttourism', $data);
        }
    }

    public function create()
    {
        $request = $this->request->getPost();

        $id = $this->objectTourismModel->get_new_id();

        $requestData = [
            'id' => $id,
            'name' => $request['name'],
            'price' => $request['price'],
            'open' => $request['open'],
            'close' => $request['close'],
            'description' => $request['description'],
            'video_url' => $request['video_url'] ?? null,
        ];
        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $geom = $request['multipolygon'];

        $addObj = $this->objectTourismModel->add_new_object_tourism($requestData, $geom);

        if (isset($request['video'])) {
            $folder = $request['video'];
            $filepath = WRITEPATH . 'uploads/' . $folder;
            $filenames = get_filenames($filepath);
            $vidFile = new File($filepath . '/' . $filenames[0]);
            $vidFile->move(FCPATH . 'media/videos/objecttourism');
            delete_files($filepath);
            rmdir($filepath);
            $requestData['video_url'] = $vidFile->getFilename();
        } else {
            $requestData['video_url'] = null;
        }

        if (isset($request['gallery'])) {
            $folders = $request['gallery'];
            $gallery = array();
            foreach ($folders as $folder) {
                $filepath = WRITEPATH . 'uploads/' . $folder;
                $filenames = get_filenames($filepath);
                $fileImg = new File($filepath . '/' . $filenames[0]);
                $fileImg->move(FCPATH . 'media/photos/objecttourism');
                delete_files($filepath);
                rmdir($filepath);
                $gallery[] = $fileImg->getFilename();
            }
            $this->galleryObjectTourismModel->add_new_gallery($id, $gallery);
        }

        if ($addObj) {
            return redirect()->to(base_url('dashboard/objecttourism'));
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function edit($id = null)
    {
        $object = $this->objectTourismModel->get_object_tourism_by_id($id)->getRowArray();
        if (empty($object)) {
            return redirect()->to('dashboard/objecttourism');
        }

        $list_gallery = $this->galleryObjectTourismModel->get_gallery($id)->getResultArray();
        $galleries = array();
        foreach ($list_gallery as $gallery) {
            $galleries[] = $gallery['url'];
        }
        $object['gallery'] = $galleries;

        $data = [
            'title' => 'Edit Object Tourism',
            'data' => $object,
        ];
        return view('dashboard/objecttourism-form', $data);
    }

    public function update($id = null)
    {
        $request = $this->request->getPost();
        $requestData = [
            'id' => $id,
            'name' => $request['name'],
            'price' => $request['price'],
            'open' => $request['open'],
            'close' => $request['close'],
            'description' => $request['description'],
            'video_url' => $request['video_url'] ?? null,
        ];
        foreach ($requestData as $key => $value) {
            if (empty($value)) {
                unset($requestData[$key]);
            }
        }

        $geom = $request['multipolygon'];

        // Handle video upload before updating the database
        if (isset($request['video'])) {
            $folder = $request['video'];
            $filepath = WRITEPATH . 'uploads/' . $folder;
            $filenames = get_filenames($filepath);
            if (!empty($filenames)) {
                $vidFile = new File($filepath . '/' . $filenames[0]);
                $vidFile->move(FCPATH . 'media/videos/objecttourism');
                delete_files($filepath);
                rmdir($filepath);
                $requestData['video_url'] = 'media/videos/objecttourism/' . $vidFile->getFilename();
            }
        } else {
            $requestData['video_url'] = null;
        }

        $updateObj = $this->objectTourismModel->update_object_tourism($id, $requestData);
        $this->objectTourismModel->update_geom($id, $geom);
        if (isset($request['gallery'])) {
            $folders = $request['gallery'];
            $gallery = array();
            foreach ($folders as $folder) {
                $filepath = WRITEPATH . 'uploads/' . $folder;
                $filenames = get_filenames($filepath);
                $fileImg = new File($filepath . '/' . $filenames[0]);
                $fileImg->move(FCPATH . 'media/photos/objecttourism');
                delete_files($filepath);
                rmdir($filepath);
                $gallery[] = $fileImg->getFilename();
            }
            $this->galleryObjectTourismModel->update_gallery($id, $gallery);
        } else {
            $this->galleryObjectTourismModel->delete_gallery($id);
        }

        if ($updateObj) {
            return redirect()->to(base_url('dashboard/objecttourism') . '/' . $id);
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function deleteobject($id = null)
    {
        $request = $this->request->getPost();
        $id = $request['id'];
        $array1 = array('id' => $id);
        $deleteObj = $this->objectTourismModel->where($array1)->delete();

        if ($deleteObj) {
            $response = [
                'status' => 200,
                'message' => [
                    "Success delete object tourism"
                ]
            ];
            session()->setFlashdata('success', 'Object tourism "' . $id . '" Deleted Successfully.');

            return redirect()->to(base_url('dashboard/objecttourism'));
        } else {
            $response = [
                'status' => 404,
                'message' => [
                    "Object tourism failed to delete"
                ]
            ];
            return $this->failNotFound($response);
        }
    }
}
