<?php

namespace App\Models;

use CodeIgniter\Model;

class UnitHomestayModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'unit_homestay';
    // protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields    = ['id', 'homestay_id', 'unit_name', 'description', 'price', 'capacity'];

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

    public function get_unit_homestay_all()
    {
        $query = $this->db->table($this->table)
            ->select('*')
            ->join('homestay', 'unit_homestay.homestay_id=homestay.id')
            ->join('homestay_unit_type', 'unit_homestay.unit_type=homestay_unit_type.id')
            ->orderby('homestay.id', 'ASC')
            ->orderby('homestay_unit_type.id', 'ASC')
            ->get();
        return $query;
    }



    public function get_unit_homestay_reserved($homestay_id =  null, $checkInDate = null)
    {
        $query = $this->db->table('detail_reservation')
            ->select('detail_reservation.unit_number')
            ->where('date', $checkInDate)
            ->where('homestay_id', $homestay_id)
            ->get();
        return $query;
    }

    public function get_unit_homestay_search($homestay_id =  null, $checkInDate = null)
    {
        $query = $this->db->table('unit_homestay AS un')
            ->select('un.*')
            ->join('detail_reservation AS dr', 'dr.unit_number = un.unit_number AND dr.homestay_id = un.homestay_id AND dr.date = ' . $this->db->escape($checkInDate), 'left')
            ->where('un.homestay_id', $homestay_id)
            ->where('dr.homestay_id IS NULL')
            ->get();


        return $query;
    }

    public function get_unit_homestay_recommendation()
    {
        $query = $this->db->table($this->table)
            ->select("*")
            ->where('homestay_id', 'HO001')
            ->get();

        return $query;
    }

    public function get_homestay_by_statistic()
    {
        $query = $this->db->table($this->table)
            ->select("unit_homestay.homestay_id,  COALESCE(SUM(CASE WHEN DATEDIFF(NOW(), DATE) <= 10 THEN 1 ELSE 0 END), 0) AS total_reservations")
            ->join('detail_reservation', 'unit_homestay.homestay_id = detail_reservation.homestay_id', 'LEFT')
            ->groupBy("unit_homestay.homestay_id")
            ->orderBy("total_reservations ASC")
            ->get();
        return $query;
    }

    public function get_homestay_by_reserved($checkInDate)
    {
        $subquery = $this->db->table('unit_homestay AS uh')
            ->select('uh.homestay_id, uh.unit_name, uh.description, uh.price, uh.capacity, gu.url , h.name, uh.unit_type, uh.unit_number, COUNT(dr.reservation_id) AS total_reservations, COALESCE(SUM(uh.capacity), 0) AS unit_capacity, 
        COALESCE(SUM(dr.unit_guest), 0) AS unit_guest, (COALESCE(SUM(uh.capacity), 0) - COALESCE(SUM(dr.unit_guest), 0)) AS unit_remaining')
            ->join('detail_reservation AS dr', "uh.homestay_id = dr.homestay_id AND uh.unit_type = dr.unit_type AND uh.unit_number = dr.unit_number AND dr.date = '$checkInDate'", 'left')
            ->join('gallery_unit as gu', 'uh.homestay_id = gu.homestay_id')
            ->join('homestay as h', 'h.id = uh.homestay_id')
            ->where('uh.unit_type = gu.unit_type')
            ->where('uh.unit_number = gu.unit_number')
            ->groupBy('uh.homestay_id, uh.unit_type, uh.unit_number, gu.url');

        // Tambahkan kondisi untuk unit_remaining di sini
        $subquery->having('unit_remaining >', 0);

        // Jalankan subquery dan urutkan hasilnya
        $compiledSubquery = $subquery->getCompiledSelect();
        $query = $this->db->query("SELECT * FROM ($compiledSubquery) AS subquery ORDER BY total_reservations DESC");

        return $query;
    }


    public function get_homestay_by_prioritas_real($checkInDate)
    {
        $query = $this->db->query("
            SELECT
                homestay_id,
                unit_type,
                unit_number,
                unit_name,
                description,
                price,
                capacity as room_capacity,
                url,
                total_reservations,
                avg_rating,
                unit_guest,
                unit_capacity - unit_guest AS unit_remaining
            FROM (
                SELECT
                    uh.homestay_id,
                    uh.unit_type,
                    uh.unit_number,
                    uh.unit_name,
                    uh.description,
                    uh.price,
                    uh.capacity,
                    gu.url,
                    COUNT(dr.reservation_id) AS total_reservations,
                    AVG(dr.rating) AS avg_rating,
                    COALESCE(SUM(dr.unit_guest), 0) AS unit_guest,
                    COALESCE((uh.capacity), 0) AS unit_capacity
                FROM
                    unit_homestay uh
                LEFT JOIN
                    detail_reservation dr ON uh.homestay_id = dr.homestay_id
                    AND uh.unit_type = dr.unit_type
                    AND uh.unit_number = dr.unit_number
                    AND dr.date = ?
                JOIN 
                        gallery_unit gu ON uh.homestay_id = gu.homestay_id
                        AND uh.unit_type = gu.unit_type
                        AND uh.unit_number = gu.unit_number
                GROUP BY
                    uh.homestay_id,
                    uh.unit_type,
                    uh.unit_number,
                    gu.url
                HAVING
                    unit_capacity - unit_guest > 1
            ) AS subquery
            ORDER BY            
            total_reservations DESC,
            homestay_id ASC
        ", array($checkInDate));

        return $query;
    }





    public function get_list_homestay()
    {
        $query = $this->db->table($this->table)
            ->select("unit_homestay.homestay_id")
            ->groupBy("unit_homestay.homestay_id")
            ->get();
        return $query;
    }

    public function get_available_units()
    {
        $currentYear = date('Y');

        $query = $this->db->table('unit_homestay uh')
            // ->select('uh.homestay_id, uh.unit_type, uh.unit_number, COUNT(dr.reservation_id) AS total_reservations')
            ->select('uh.homestay_id, uh.unit_type, uh.unit_number, COUNT(CASE WHEN dr.unit_status IS NULL THEN dr.reservation_id END) AS total_reservations')
            ->join('detail_reservation dr', 'uh.homestay_id = dr.homestay_id AND uh.unit_type = dr.unit_type AND uh.unit_number = dr.unit_number', 'left')
            ->where("(YEAR(dr.date) = $currentYear OR dr.date IS NULL)", null, false)
            ->groupBy('uh.homestay_id, uh.unit_type, uh.unit_number')
            ->orderBy('total_reservations', 'ASC')
            ->get();

        return $query;
    }
    public function get_available_units_by_rating()
    {
        $currentYear = date('Y');

        $query = $this->db->table('unit_homestay uh')
            ->select('uh.homestay_id, uh.unit_type, uh.unit_number,COUNT(CASE WHEN dr.unit_status IS NULL THEN dr.reservation_id END) AS total_reservations,COALESCE(AVG(dr.rating), 5,0) AS avg_rating')
            // ->select('uh.homestay_id, uh.unit_type, uh.unit_number,COUNT(dr.reservation_id) AS total_reservations,COALESCE(AVG(dr.rating), 5,0) AS avg_rating')
            ->join('detail_reservation dr', 'uh.homestay_id = dr.homestay_id AND uh.unit_type = dr.unit_type AND uh.unit_number = dr.unit_number', 'left')
            ->where("(YEAR(dr.date) = $currentYear OR dr.date IS NULL)", null, false)
            ->groupBy('uh.homestay_id, uh.unit_type, uh.unit_number')
            ->orderBy('total_reservations', 'ASC')
            ->orderBy('avg_rating', 'DESC')
            ->get();

        return $query;
    }


    public function get_homestay_by_custom($totalPeople)
    {
        if ($totalPeople < 11) {
            $parameter = '5';
        } else if ($totalPeople > 10) {
            $parameter = '21';
        } else if ($totalPeople == 0) {
            $parameter = '0';
        }
        $query = $this->db->table($this->table)
            ->select("unit_homestay.homestay_id,")
            ->join('detail_reservation', 'unit_homestay.homestay_id = detail_reservation.homestay_id', 'LEFT')
            ->where('unit_homestay.capacity <', $parameter)
            ->groupBy("unit_homestay.homestay_id")
            // ->orderBy("total_reservations ASC")
            ->get();
        return $query;
    }


    public function get_unit_homestay($homestay_id =  null, $checkInDate = null)
    {
        $query = $this->db->table($this->table)
            ->select('*')
            ->join('homestay_unit_type', 'unit_homestay.unit_type=homestay_unit_type.id')
            ->where('homestay_id', $homestay_id)
            ->get();
        return $query;
    }

    public function get_unit_homestay_with_gallery_small($homestay_id = null, $checkInDate = null, $totalPeople = null)
    {
        $query = $this->db->table($this->table)
            ->select('*')
            ->join('homestay_unit_type', 'unit_homestay.unit_type = homestay_unit_type.id')
            ->join('gallery_unit', 'unit_homestay.homestay_id = gallery_unit.homestay_id')
            ->join('detail_reservation', 'detail_reservation.unit_number = unit_homestay.unit_number AND detail_reservation.homestay_id = unit_homestay.homestay_id AND detail_reservation.date = ' . $this->db->escape($checkInDate),)
            // ->join('detail_reservation', 'detail_reservation.unit_number = unit_homestay.unit_number AND detail_reservation.homestay_id = unit_homestay.homestay_id AND detail_reservation.date ='. $checkInDate.'', 'left')
            ->where('unit_homestay.homestay_id', $homestay_id)
            ->where('unit_homestay.unit_type = gallery_unit.unit_type')
            ->where('unit_homestay.unit_number = gallery_unit.unit_number')
            ->where('unit_homestay.capacity <=', '5')
            ->select('unit_homestay.homestay_id,unit_homestay.unit_type,unit_homestay.unit_number')
            ->orderby('unit_homestay.capacity', 'ASC')
            ->orderby('unit_homestay.homestay_id', 'ASC')
            ->where('detail_reservation.date IS NULL')
            ->get();

        return $query;
    }



    //kodingan ini bisa menambah unit tambahan tp blm adil
    public function unit_tersedia($homestay_id, $unit_type, $unit_number, $checkInDate, $totalPeople)
    {
        $query = $this->db->table($this->table)
            ->select('unit_homestay.homestay_id, gallery_unit.url, unit_homestay.capacity as room_capacity, unit_homestay.unit_type, unit_homestay.description, unit_homestay.price, unit_homestay.unit_name, unit_homestay.unit_number, unit_homestay.capacity, COUNT(detail_reservation.reservation_id) AS total_reservations')
            ->join('homestay_unit_type', 'unit_homestay.unit_type = homestay_unit_type.id')
            // ->join('gallery_unit', 'unit_homestay.homestay_id = gallery_unit.homestay_id')
            ->join('gallery_unit', 'unit_homestay.homestay_id = gallery_unit.homestay_id AND unit_homestay.unit_type = gallery_unit.unit_type AND unit_homestay.unit_number = gallery_unit.unit_number')
            ->join('detail_reservation', 'unit_homestay.homestay_id = detail_reservation.homestay_id AND unit_homestay.unit_type = detail_reservation.unit_type AND unit_homestay.unit_number = detail_reservation.unit_number AND detail_reservation.date = ' . $this->db->escape($checkInDate), 'left')
            ->where('unit_homestay.homestay_id', $homestay_id)
            ->where('unit_homestay.unit_type', $unit_type)
            ->where('unit_homestay.unit_number', $unit_number)
            ->where('unit_homestay.unit_type = gallery_unit.unit_type')
            ->where('unit_homestay.unit_number = gallery_unit.unit_number')
            ->where('detail_reservation.date IS NULL')
            ->groupby('unit_homestay.homestay_id, unit_homestay.unit_type, unit_homestay.unit_number, gallery_unit.url')
            ->orderby('unit_homestay.capacity', 'ASC')
            ->orderby('total_reservations', 'ASC')
            ->get();

        return $query;
    }



    public function isUnitSelected($requestData)
    {
        return $this->table($this->table)
            ->where('unit_number', $requestData['unit_number'])
            ->where('homestay_id', $requestData['homestay_id'])
            ->where('unit_type', $requestData['unit_type'])
            ->get()
            ->getRow();
    }


    public function bagikanTotalPeople($units, $totalPeople)
    {
        $units_selected = [];
        $totalPeopleRemaining = $totalPeople;

        foreach ($units as $unit) {
            // Determine the capacity of the selected unit (maximum between unit capacity and totalPeople remaining)
            $unit_capacity = min($totalPeopleRemaining, $unit['unit_remaining']);

            // Add the selected unit to the array
            $units_selected[] = [
                'homestay_id' => $unit['homestay_id'],
                'unit_type' => $unit['unit_type'],
                'unit_number' => $unit['unit_number'],
                'unit_name' => $unit['unit_name'],
                'description' => $unit['description'],
                'price' => $unit['price'],
                'room_capacity' => $unit_capacity,
                'totalPeopleRemaining' => $totalPeopleRemaining - $unit_capacity,
                'url' => $unit['url'],
                // Additional data as needed
            ];

            // Reduce the totalPeople remaining by the capacity of the selected unit
            $totalPeopleRemaining -= $unit_capacity;

            // If there are no more totalPeople remaining, exit the loop
            if ($totalPeopleRemaining <= 0) {
                break;
            }
        }

        return $units_selected;
    }




    public function get_unit_homestay_with_gallery_medium($homestay_id = null, $checkInDate = null, $totalPeople = null)
    {
        $subquery = $this->db->table('detail_reservation')
            ->select('homestay_id, unit_number')
            ->where('date', $checkInDate)
            ->getCompiledSelect();

        $query = $this->db->table($this->table)
            ->select('unit_homestay.homestay_id, unit_homestay.unit_type, unit_homestay.unit_number')
            ->join('detail_reservation', 'detail_reservation.unit_number = unit_homestay.unit_number AND detail_reservation.homestay_id = unit_homestay.homestay_id AND detail_reservation.date = ' . $this->db->escape($checkInDate), 'left')
            ->where('unit_homestay.homestay_id', $homestay_id)
            ->where("NOT EXISTS ($subquery)", null, false)
            ->groupBy('unit_homestay.homestay_id, unit_homestay.unit_type, unit_homestay.unit_number')
            ->orderBy('unit_homestay.capacity', 'ASC')
            ->orderBy('unit_homestay.homestay_id', 'ASC')
            ->get();

        return $query;
    }

    public function get_unit($homestay_id = null, $unit_type = null, $unit_number = null, $checkInDate = null, $totalPeople = null)
    {

        $query = $this->db->table($this->table)
            ->select('*')
            ->join('homestay_unit_type', 'unit_homestay.unit_type = homestay_unit_type.id')
            ->join('detail_reservation', 'detail_reservation.unit_number = unit_homestay.unit_number AND detail_reservation.homestay_id = unit_homestay.homestay_id AND detail_reservation.date = ' . $this->db->escape($checkInDate), 'left')
            // ->join('detail_reservation', 'detail_reservation.unit_number = unit_homestay.unit_number AND detail_reservation.homestay_id = unit_homestay.homestay_id AND detail_reservation.date ='. $checkInDate.'', 'left')
            ->where('unit_homestay.homestay_id', $homestay_id)
            ->where('unit_homestay.unit_type', $unit_type)
            ->where('unit_homestay.unit_number', $unit_number)
            // ->where('unit_homestay.capacity <=', $totalPeople)          
            ->get();

        return $query;
    }

    public function get_unit_homestay_with_gallery_large($homestay_id = null, $checkInDate = null, $totalPeople = null)
    {

        $query = $this->db->table($this->table)
            ->select('*')
            ->join('homestay_unit_type', 'unit_homestay.unit_type = homestay_unit_type.id')
            ->join('gallery_unit', 'unit_homestay.homestay_id = gallery_unit.homestay_id')
            ->join('detail_reservation', 'detail_reservation.unit_number = unit_homestay.unit_number AND detail_reservation.homestay_id = unit_homestay.homestay_id AND detail_reservation.date = ' . $this->db->escape($checkInDate), 'left')
            // ->join('detail_reservation', 'detail_reservation.unit_number = unit_homestay.unit_number AND detail_reservation.homestay_id = unit_homestay.homestay_id AND detail_reservation.date ='. $checkInDate.'', 'left')

            ->where('unit_homestay.homestay_id', $homestay_id)
            ->where('unit_homestay.unit_type = gallery_unit.unit_type')
            ->where('unit_homestay.unit_number = gallery_unit.unit_number')
            // ->where('unit_homestay.capacity <', $totalPeople)
            ->select('unit_homestay.homestay_id,unit_homestay.unit_type,unit_homestay.unit_number')
            ->orderby('unit_homestay.capacity', 'ASC')
            ->orderby('unit_homestay.homestay_id', 'ASC')
            ->where('detail_reservation.date IS NULL')
            ->get();

        return $query;
    }


    public function get_unit_homestay_by_id($homestay_id)
    {
        $query = $this->db->table($this->table)
            ->select("*")
            ->where('homestay_id', $homestay_id)
            ->get();

        return $query;
    }

    public function get_list_unit_homestay()
    {
        $query = $this->db->table($this->table)
            ->select("*")
            ->get();

        return $query;
    }

    public function get_unit_homestay_selected($unit_number, $homestay_id, $unit_type)
    {
        $query = $this->db->table($this->table)
            ->select("*")
            ->where('homestay_id', $homestay_id)
            ->where('unit_type', $unit_type)
            ->where('unit_number', $unit_number)
            ->get();
        return $query;
    }

    public function get_new_unit_number($id, $type)
    {
        $lastId = $this->db->table($this->table)
            ->select('unit_number')
            ->where('homestay_id', $id)
            // ->where('unit_type', $type)
            ->orderBy('unit_number', 'ASC')->get()->getLastRow('array');

        if (empty($lastId)) {
            $unit_number = '01';
        } else {
            $count = (int)substr($lastId['unit_number'], 1);
            $unit_number = sprintf('%02d', $count + 1);
        }


        return $unit_number;
    }

    public function add_new_unitHomestay($requestData = null)
    {
        $insert = $this->db->table($this->table)
            ->insert($requestData);
        return $insert;
    }

    public function delete_unit($array2 = null)
    {
        // dd($array2);
        return $this->db->table($this->table)->delete($array2);
    }

    public function update_unit_homestay($unit_number = null, $homestay_id = null, $unit_type = null, $data = null)
    {
        foreach ($data as $key => $value) {
            if (empty($value)) {
                unset($data[$key]);
            }
        }
        $query = $this->db->table($this->table)
            ->where('unit_number', $unit_number)
            ->where('homestay_id', $homestay_id)
            ->where('unit_type', $unit_type)
            ->update($data);
        return $query;
    }
}
