<?php

namespace App\Models;

use CodeIgniter\Model;

class FacilityTypeModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'facility_type';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields    = ['id', 'type'];

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

    public function get_list_facility_type()
    {
        $query = $this->db->table($this->table)
            ->select("*")
            ->orderBy('id', 'ASC')
            ->get();

        return $query;
    }

    public function get_facility_type_by_id($id = null)
    {
        $query = $this->db->table($this->table)
            ->select("*")
            ->where('id', $id)
            ->get();
        return $query;
    }

    
    public function get_new_id()
    {
        $lastId = $this->db->table($this->table)->select('id')->orderBy('id', 'ASC')->get()->getLastRow('array');
        if(empty($lastId)){
            $id='F0001';
        }else{
        $count = (int)substr($lastId['id'], 1);
        $id = sprintf('F0%03d', $count + 1);
        }
        return $id;
    }

    public function add_new_facility_type($facility_type = null)
    {
        $insert = $this->db->table($this->table)
            ->insert($facility_type);
        return $insert;
    }

    public function update_facility_type($id = null, $facility_type = null)
    {
        foreach ($facility_type as $key => $value) {
            if (empty($value)) {
                unset($facility_type[$key]);
            }
        }
        $query = $this->db->table($this->table)
            ->where('id', $id)
            ->update($facility_type);
        return $query;
    }
}
