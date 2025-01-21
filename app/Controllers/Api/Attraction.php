<?php

namespace App\Controllers\Api;

use App\Models\AttractionModel;
use App\Models\GalleryAttractionModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class Attraction extends ResourceController
{
    use ResponseTrait;

    protected $attractionModel;
    protected $galleryAttractionModel;

    public function __construct()
    {
        $this->attractionModel = new AttractionModel();
        $this->galleryAttractionModel = new GalleryAttractionModel();
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $contents = $this->attractionModel->get_list_attraction()->getResult();
        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success get list of Attraction"
            ]
        ];
        return $this->respond($response);
    }
    public function attractionLSA()
    {
        $contents = $this->attractionModel->get_list_attractionLSA()->getResult();
        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success get list of Attraction Lake Singkarak Tourism"
            ]
        ];
        return $this->respond($response);
    }
    public function attractionNT()
    {
        $contents = $this->attractionModel->get_list_attractionNT()->getResult();
        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success get list of Attraction Nature Tourism"
            ]
        ];
        return $this->respond($response);
    }
    public function attractionCT()
    {
        $contents = $this->attractionModel->get_list_attractionCT()->getResult();
        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success get list of Attraction Cultural Tourism"
            ]
        ];
        return $this->respond($response);
    }
    public function attractionET()
    {
        $contents = $this->attractionModel->get_list_attractionET()->getResult();
        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success get list of Attraction Educational Tourism"
            ]
        ];
        return $this->respond($response);
    }

    public function show($id = null)
    {
        $attraction = $this->attractionModel->get_attraction_by_id($id)->getRowArray();

        $response = [
            'data' => $attraction,
            'status' => 200,
            'message' => [
                "Success display detail information of Attraction"
            ]
        ];
        return $this->respond($response);
    }

    public function detail($id = null)
    {
        $attraction = $this->attractionModel->get_attraction_by_id($id)->getRowArray();

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
            'folder' => 'attraction'
        ];

        return view('maps/detail_attraction', $data);
    }

    public function delete($id = null)
    {
        $deleteGAT = $this->galleryAttractionModel->delete_gallery($id);
        $deleteAT = $this->attractionModel->delete(['id' => $id]);
        if ($deleteAT) {
            $response = [
                'status' => 200,
                'message' => [
                    "Success delete Attraction"
                ]
            ];
            return $this->respondDeleted($response);
        }
    }

    public function maps() {
        $contents = $this->attractionModel->get_list_attraction_api()->getResultArray();
        $data = [
            'title' => 'Attraction',
            'data' => $contents,
        ];
        // dd($data);
        return view('maps/attraction', $data);
    }

    public function getData()
    {
        $request = $this->request->getPost();
        $digitasi = $request['digitasi'];

        for($h=1; $h<20; $h++){
            if ($h < 10) {
                $value= 'AT00'.$h;
            } elseif ($h > 9) {
                $value= 'AT0'.$h;
            }

            if ($digitasi == $value) {
                $digiProperty = $this->attractionModel->get_object($value)->getRowArray();
                $geoJson = json_decode($this->attractionModel->get_geoJson($value)->getRowArray()['geoJson']);
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

    public function findlsaAll()
    {
        // $request = $this->request->getPost();
        $contents = $this->attractionModel->get_list_attractionLSA()->getResult();

        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success find all attraction LSA"
            ]
        ];
        return $this->respond($response);
    }
    public function findAll()
    {
        // $request = $this->request->getPost();
        $contents = $this->attractionModel->get_list_attraction_without_lsa()->getResult();

        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success find all attraction"
            ]
        ];
        return $this->respond($response);
    }

    public function findByRadiuslsa()
    {
        $request = $this->request->getPost();
        $contents = $this->attractionModel->get_lsa_by_radius($request)->getResult();

        $response = [
            'data' => $contents,
            'status' => 200,
            'message' => [
                "Success find Singkarak Lake Activity by radius"
            ]
        ];
        return $this->respond($response);
    }

    
}
