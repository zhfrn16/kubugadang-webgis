<?php

namespace App\Models;

use CodeIgniter\Model;

class ObjectTourismModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'object_tourism';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $allowedFields    = ['id', 'name', 'price', 'category', 'open', 'close', 'min_capacity', 'description', 'video_url', 'geom',];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;


    public function get_list_object_tourism()
    {
        $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.price,{$this->table}.open,{$this->table}.close,{$this->table}.category,{$this->table}.min_capacity,{$this->table}.description,{$this->table}.video_url";
        $query = $this->db->table($this->table)
            ->select("{$columns}, {$coords}")
            ->get();
        return $query;
    }

    public function get_object_tourism_by_id($id = null)
    {
        $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.price,{$this->table}.category,{$this->table}.open,{$this->table}.close,{$this->table}.description,{$this->table}.video_url";
        $geoJson = "ST_AsGeoJSON({$this->table}.geom) AS geoJson";
        $query = $this->db->table($this->table)
            ->select("{$columns}, {$coords}, {$geoJson}")
            ->where('id', $id)
            ->get();
        return $query;
    }

    public function get_geoJson($id = null)
    {
        $geoJson = "ST_AsGeoJSON({$this->table}.geom) AS geoJson";
        $query = $this->db->table($this->table)
            ->select("{$geoJson}")
            ->where('id', $id)
            ->get();
        return $query;
    }

    public function get_object_tourism2_by_id($id = null)
    {
        $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.price,{$this->table}.open,{$this->table}.close,{$this->table}.description,{$this->table}.video_url";
        $geoJson = "ST_AsGeoJSON({$this->table}.geom) AS geoJson";
        $query = $this->db->table($this->table)
            ->select("{$columns}, {$coords}, {$geoJson}")
            ->where('id', $id)
            ->get();
        return $query;
    }

    public function get_new_id()
    {
        $lastId = $this->db->table($this->table)->select('id')->orderBy('id', 'ASC')->get()->getLastRow('array');
        if (empty($lastId)) {
            $id = 'OT001';
        } else {
            $count = (int)substr($lastId['id'], 3);
            $id = sprintf('OT%03d', $count + 1);
        }
        return $id;
    }

    public function add_new_object_tourism($objectTourism = null, $geom = null)
    {
        $insert = $this->db->table($this->table)
            ->insert($objectTourism);
        $update = $this->db->table($this->table)
            ->set('geom', "ST_GeomFromText('{$geom}')", false)
            ->where('id', $objectTourism['id'])
            ->update();
        return $insert && $update;
    }

    public function update_object_tourism($id = null, $objectTourism = null)
    {
        foreach ($objectTourism as $key => $value) {
            if (empty($value) && $key !== 'price') {
                unset($objectTourism[$key]);
            }
        }
        $query = $this->db->table($this->table)
            ->where('id', $id)
            ->update($objectTourism);
        return $query;
    }

    public function update_geom($id = null, $geom = null)
    {
        $query = $this->db->table($this->table)
            ->set('geom', "ST_GeomFromText('{$geom}')", false)
            ->where('id', $id)
            ->update();
        return $query;
    }

    public function get_object($id = null)
    {
        $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
        $query = $this->db->table($this->table)
            ->select("id, name, {$coords}")
            ->where('id', $id)
            ->get();
        return $query;
    }


    function get_object_tourism()
    {

        $object = "<option value='0'>--pilih--</pilih>";

        $this->db->order_by('name', 'ASC');
        $ob = $this->db->get();

        foreach ($ob->result_array() as $data) {
            $object .= "<option value='$data[id]'>$data[name]</option>";
        }

        return $object;
    }

    public function get_list_object_tourism_api()
    {
        $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.price,{$this->table}.open,{$this->table}.close,{$this->table}.description,{$this->table}.video_url";
        $query = $this->db->table($this->table)
            ->select("{$columns}, {$coords}")
            ->get();
        return $query;
    }

    public function get_object_tourism_by_radius($data = null)
    {
        $radius = (int)$data['radius'] / 1000;
        $lat = $data['lat'];
        $long = $data['long'];
        $distance = "(6371 * acos(cos(radians({$lat})) * cos(radians(ST_Y(ST_CENTROID({$this->table}.geom)))) 
                    * cos(radians(ST_X(ST_CENTROID({$this->table}.geom))) - radians({$long})) 
                    + sin(radians({$lat}))* sin(radians(ST_Y(ST_CENTROID({$this->table}.geom))))))";
        $coords = "ST_Y(ST_Centroid({$this->table}.geom)) AS lat, ST_X(ST_Centroid({$this->table}.geom)) AS lng";
        $columns = "{$this->table}.id,{$this->table}.name,{$this->table}.price,{$this->table}.open,{$this->table}.close,{$this->table}.description,{$this->table}.video_url";
        $query = $this->db->table($this->table)
            ->select("{$columns}, {$coords}, {$distance} as distance")
            ->having(['distance <=' => $radius])
            ->get();
        return $query;
    }
}
