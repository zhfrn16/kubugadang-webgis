<?php

namespace App\Models;

use CodeIgniter\Model;

class GalleryTraditionalHouseModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'gallery_traditional_house';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields    = ['id', 'traditional_house_id', 'url', 'created_at', 'updated_at'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    public function get_gallery($traditional_house_id = null)
    {
        $query = $this->db->table($this->table)
            ->select('url')
            ->where('traditional_house_id', $traditional_house_id)
            ->get();
        return $query;
    }

    public function get_new_id()
    {
        $lastId = $this->db->table($this->table)->select('id')->orderBy('id', 'ASC')->get()->getLastRow('array');
        if(empty($lastId)){
            $id='GT001';
        }else{
        $count = (int)substr($lastId['id'], 3);
        $id = sprintf('GT%03d', $count + 1);
        }
        return $id;
    }

    public function add_new_gallery($id = null, $data = null)
    {
        $query = false;
        foreach ($data as $gallery) {
            $new_id = $this->get_new_id();
            $content = [
                'id' => $new_id,
                'traditional_house_id' => $id,
                'url' => $gallery
            ];
            $query = $this->db->table($this->table)->insert($content);
        }
        return $query;
    }

    public function isGalleryExist($id)
    {
        return $this->table($this->table)
            ->where('traditional_house_id', $id)
            ->get()
            ->getRow();
    }

    public function update_gallery($id = null, $data = null)
    {
        $queryDel = $this->delete_gallery($id);

        foreach ($data as $key => $value) {
            if (empty($value)) {
                unset($data[$key]);
            }
        }
        $queryIns = $this->add_new_gallery($id, $data);
        return $queryDel && $queryIns;
    }

    public function delete_gallery($id = null)
    {
        return $this->db->table($this->table)->delete(['traditional_house_id' => $id]);
    }
}
