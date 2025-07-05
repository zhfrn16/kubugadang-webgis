<?php

namespace App\Controllers\Api;

use App\Models\EventModel;
use App\Models\GalleryEventModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Event extends ResourceController
{
    use ResponseTrait;

    protected $eventModel;
    protected $galleryEventModel;

    public function __construct()
    {
        $this->eventModel = new EventModel();
        $this->galleryEventModel = new GalleryEventModel();
    }
    /**
     * Instance of the main Request object.
     *
     * @var HTTP\IncomingRequest
     */
    protected $request;

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $contents = $this->eventModel->get_list_event_api()->getResult();
        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success get list of Event"
            ]
        ];
        return $this->respond($response);
    }

    public function show($id = null)
    {
        $event = $this->eventModel->get_event_by_id($id)->getRowArray();

        $response = [
            'data' => $event,
            'status' => 200,
            'message' => [
                "Success display detail information of Event"
            ]
        ];
        return $this->respond($response);
    }

    public function findAll()
    {
        $request = $this->request->getPost();
        $contents = $this->eventModel->get_list_event()->getResult();

        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success find all event"
            ]
        ];
        return $this->respond($response);
    }

    public function findByRadius()
    {
        $request = $this->request->getPost();
        $contents = $this->eventModel->get_event_by_radius($request)->getResult();

        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success find event by radius"
            ]
        ];
        return $this->respond($response);
    }

    public function findByTrack()
    {
        $request = $this->request->getPost();
        $contents = $this->eventModel->get_event_by_radius($request)->getResult();

        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success find event by track"
            ]
        ];
        return $this->respond($response);
    }

        public function getData()
    {
        $request = $this->request->getPost();
        $digitasi = $request['digitasi'];

        for($h=1; $h<20; $h++){
            if ($h < 10) {
                $value= 'EV00'.$h;
            } elseif ($h > 9) {
                $value= 'EV0'.$h;
            }

            if ($digitasi == $value) {
                $digiProperty = $this->eventModel->get_object($value)->getRowArray();
                $geoJson = json_decode($this->eventModel->get_geoJson($value)->getRowArray()['geoJson']);
            } 
        }
        
        $content = [
            'type' => 'Feature',
            'geometry' => $geoJson,
            'properties' => [
                'id' => $digiProperty['id'],
                'name' => $digiProperty['name'],
                'lat' => $digiProperty['lat'],
                'lng' => $digiProperty['lng'],
            ]
        ];
        $response = [
            'data' => $content,
            'status' => 200,
            'message' => [
                "Success"
            ]
        ];
        return $this->respond($response);
    }

    public function detail($id = null)
    {
        $event = $this->eventModel->get_event_by_id($id)->getRowArray();

        if (empty($event)) {
            return redirect()->to(substr(current_url(), 0, -strlen($id)));
        }

        $list_gallery = $this->galleryEventModel->get_gallery($id)->getResultArray();
        $galleries = array();
        foreach ($list_gallery as $gallery) {
            $galleries[] = $gallery['url'];
        }
        $event['gallery'] = $galleries;

        $data = [
            'title' => $event['name'],
            'data' => $event,
            'folder' => 'event'
        ];

        if (url_is('*dashboard*')) {
            return view('dashboard/detail_event', $data);
        }
        return view('maps/detail_event', $data);
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    // public function delete($id = null)
    // {
    //     $deleteGEV = $this->galleryEventModel->delete(['event_id' => $id]);
    //     $deleteEV = $this->eventModel->delete(['id' => $id]);
    //     if ($deleteEV) {
    //         $response = [
    //             'status' => 200,
    //             'message' => [
    //                 "Success delete event"
    //             ]
    //         ];
    //         return $this->respondDeleted($response);
    //     } else {
    //         $response = [
    //             'status' => 404,
    //             'message' => [
    //                 "Event not found"
    //             ]
    //         ];
    //         return $this->failNotFound($response);
    //     }
    // }

    public function deleteobject($id = null)
    {
        $request = $this->request->getPost();

        $id = $request['id'];
        $array1 = array('id' => $id);
        $deleteEV = $this->eventModel->where($array1)->delete();

        if ($deleteEV) {
            $response = [
                'status' => 200,
                'message' => [
                    "Success delete event"
                ]
            ];
            session()->setFlashdata('success', 'event "' . $id . '" Deleted Successfully.');

            return redirect()->to(base_url('dashboard/event'));
        } else {
            $response = [
                'status' => 404,
                'message' => [
                    "Event failed to delete"
                ]
            ];
            return $this->failNotFound($response);
        }
    }

    public function maps()
    {
        $contents = $this->eventModel->get_list_event_api()->getResultArray();
        $data = [
            'title' => 'Event',
            'data' => $contents,
        ];
        // dd($data);
        return view('maps/event', $data);
    }
}
