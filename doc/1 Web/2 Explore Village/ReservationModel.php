<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\I18n\Time;

class ReservationModel extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'reservation';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields    = ['id', 'user_id', 'request_date', 'check_in', 'total_people', 'deposite', 'total_price', 'status', 'note', 'feedback', 'rating'];

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

    public function get_list_reservation()
    {
        $query = $this->db->table($this->table)
            ->select("`reservation.id`, `reservation.user_id`, `users.username`,`reservation.package_id`,`reservation.total_people`, `package.name`, `package.custom`,
            `reservation.deposit`, `reservation.deposit_date`,
            `reservation.total_price`, `reservation.token_of_deposit`, `reservation.token_of_payment`,`reservation.payment_date`,`reservation.type_of_payment`,
            `reservation.proof_of_deposit`, `reservation.admin_deposit_check`,`reservation.proof_of_payment`,`reservation.admin_payment_check`,
            `reservation.request_date`, `reservation.check_in`, `reservation.note`,`reservation.deposit_channel`,`reservation.payment_channel`,
            `reservation.status`,`reservation.confirmation_date`,`reservation.feedback`,`reservation.admin_confirm`,
            `reservation.review`,`reservation.rating`,`reservation.response`,
            `reservation.cancel_date`,`reservation.cancel`,`reservation.account_refund`,
            `reservation.proof_refund`,`reservation.refund_date`,`reservation.admin_refund`,`users.username`,
            `reservation.refund_amount`,`reservation.deposit_check`,`reservation.payment_check`,`reservation.refund_check`")
            ->join('package', 'reservation.package_id = package.id')
            ->join('users', 'reservation.user_id = users.id')
            ->orderBy('reservation.request_date', 'DESC')
            ->get();
        return $query;
    }

    public function get_list_reservation_report()
    {
        $query = $this->db->table($this->table)
            ->select("`reservation.id`, `reservation.user_id`, `users.username`,`reservation.package_id`,`reservation.total_people`, `package.name`, `package.custom`,
            `reservation.deposit`,`reservation.deposit_date`,
            `reservation.total_price`, `reservation.token_of_deposit`, `reservation.token_of_payment`,`reservation.payment_date`,`reservation.type_of_payment`,
            `reservation.proof_of_deposit`, `reservation.admin_deposit_check`,`reservation.proof_of_payment`,`reservation.admin_payment_check`,
            `reservation.request_date`, `reservation.check_in`, `reservation.note`,`reservation.deposit_channel`,`reservation.payment_channel`,
            `reservation.status`,`reservation.confirmation_date`,`reservation.feedback`,`reservation.admin_confirm`,
            `reservation.review`,`reservation.rating`,`reservation.response`,
            `reservation.cancel_date`,`reservation.cancel`,`reservation.account_refund`,
            `reservation.proof_refund`,`reservation.refund_date`,`reservation.admin_refund`,`users.username`,
            `reservation.refund_amount`,`reservation.deposit_check`,`reservation.payment_check`,`reservation.refund_check`")
            ->join('package', 'reservation.package_id = package.id')
            ->join('users', 'reservation.user_id = users.id')
            ->orderBy('reservation.request_date', 'DESC')
            ->where('reservation.total_price <>', '0')
            ->where('reservation.status', '1')
            ->get();

        return $query;
    }

    public function sum_done_deposit()
    {
        $query = $this->db->table($this->table)
            ->selectSUM('deposit', 'deposit')
            ->where('deposit_check ==', '200')
            ->where('proof_refund', null)
            ->where('payment_check', null)
            ->where('total_price <>', '0')
            ->where('status', '1')
            ->get();
        return $query;
    }

    public function sum_done_refund()
    {
        $query = $this->db->table($this->table)
            ->selectSUM('deposit', 'refund')
            ->where('deposit_check ==', '200')
            ->where('proof_refund <>', null)
            ->where('total_price <>', '0')
            ->where('status', '1')
            ->get();
        return $query;
    }

    public function sum_done_total()
    {
        $query = $this->db->table($this->table)
            ->selectSUM('total_price', 'total_price')
            ->where('payment_check ==', 200)
            ->where('total_price <>', '0')
            ->where('status', '1')
            ->get();

        return $query;
    }

    public function get_list_reservation_by_user($username)
    {
        $query = $this->db->table($this->table)
            ->select("`reservation.id`, `reservation.user_id`, `users.username`,`reservation.package_id`,`package.custom`,`reservation.total_people`, `package.name`, 
            `reservation.deposit`, `reservation.deposit_date`,
            `reservation.total_price`, `reservation.token_of_deposit`, `reservation.token_of_payment`,`reservation.payment_date`,`reservation.type_of_payment`,
            `reservation.proof_of_deposit`, `reservation.admin_deposit_check`,`reservation.proof_of_payment`,`reservation.admin_payment_check`,
            `reservation.request_date`, `reservation.check_in`, `reservation.note`,`reservation.deposit_channel`,`reservation.payment_channel`,
            `reservation.status`,`reservation.confirmation_date`,`reservation.feedback`,`reservation.admin_confirm`,
            `reservation.review`,`reservation.rating`,`reservation.response`,
            `reservation.cancel_date`,`reservation.cancel`,`reservation.account_refund`,
            `reservation.proof_refund`,`reservation.refund_date`,`reservation.admin_refund`,`users.username`,
            `reservation.refund_amount`,`reservation.deposit_check`,`reservation.payment_check`,`reservation.refund_check`")
            ->join('package', 'reservation.package_id = package.id')
            ->join('users', 'reservation.user_id = users.id')
            ->orderBy('reservation.request_date', 'DESC')
            ->where('users.username', $username)
            ->get();

        return $query;
    }

    public function get_reservation_by_id($id = null)
    {
        $query = $this->db->table($this->table)
            ->select("`reservation.id`, `reservation.user_id`, `users.username`,`users.email`,`reservation.package_id`,`package.name AS package_name`,`package.custom`,`reservation.total_people`, `package.name`, 
            `reservation.deposit`,`reservation.deposit_date`,`reservation.type_of_payment`,
            `reservation.total_price`, `reservation.token_of_deposit`, `reservation.token_of_payment`,`reservation.payment_date`,`reservation.type_of_payment`,
            `reservation.proof_of_deposit`, `reservation.admin_deposit_check`,`reservation.proof_of_payment`,`reservation.admin_payment_check`,
            `reservation.request_date`, `reservation.check_in`, `reservation.note`,`reservation.deposit_channel`,`reservation.payment_channel`,
            `reservation.status`,`reservation.confirmation_date`,`reservation.feedback`,`reservation.admin_confirm`,
            `reservation.review`,`reservation.rating`,`reservation.response`,
            `reservation.cancel_date`,`reservation.cancel`,`reservation.account_refund`,
            `reservation.proof_refund`,`reservation.refund_date`,`reservation.admin_refund`,`users.username`,
            `reservation.refund_amount`,`reservation.token_of_deposit`,`reservation.deposit_check`,`reservation.payment_check`,`reservation.refund_check`")
            ->join('package', 'reservation.package_id = package.id')
            ->join('users', 'reservation.user_id = users.id')
            ->where('reservation.id', $id)
            ->get();
        return $query;
    }

    public function getReview($id = null)
    {
        $query = $this->db->table($this->table)
            ->select("`reservation.id`, `reservation.user_id`, `reservation.package_id`,`package.name`,`reservation.review`,`reservation.rating`,`users.username` ")
            ->join('package', 'reservation.package_id = package.id')
            ->join('users', 'reservation.user_id = users.id')
            ->where('reservation.package_id', $id)
            ->where('reservation.rating <>', 0)
            ->get();
        return $query;
    }

    public function getRating($id = null)
    {
        $query = $this->db->table($this->table)
            ->selectAVG('reservation.rating', 'rating')
            ->where('reservation.package_id', $id)
            ->where('reservation.rating <>', 0)
            ->get();
        return $query;
    }

    public function get_reservation_package_by_id($id = null)
    {
        $query = $this->db->table($this->table)
            ->select("`reservation.id`, `reservation.user_id`, `users.username`,`reservation.package_id`,`reservation.total_people`, `package.name`, 
            `reservation.deposit`,`reservation.deposit_date`,
            `reservation.total_price`, `reservation.token_of_deposit`, `reservation.token_of_payment`,`reservation.payment_date`,`reservation.type_of_payment`,
            `reservation.proof_of_deposit`, `reservation.admin_deposit_check`,`reservation.proof_of_payment`,`reservation.admin_payment_check`,
            `reservation.request_date`, `reservation.check_in`, `reservation.note`,`reservation.deposit_channel`,`reservation.payment_channel`,
            `reservation.status`,`reservation.confirmation_date`,`reservation.feedback`,`reservation.admin_confirm`,
            `reservation.review`,`reservation.rating`,`reservation.response`,
            `reservation.cancel_date`,`reservation.cancel`,`reservation.account_refund`,
            `reservation.proof_refund`,`reservation.refund_date`,`reservation.admin_refund`,`package.custom`,`users.username`,
            `reservation.refund_amount`,`reservation.deposit_check`,`reservation.payment_check`,`reservation.refund_check`")
            ->join('package', 'reservation.package_id = package.id')
            ->join('users', 'reservation.user_id = users.id')
            ->where('reservation.id', $id)
            ->get();
        return $query;
    }


    public function get_package_reservation_by_idp($id = null)
    {
        $query = $this->db->table($this->table)
            ->select("`reservation.id`, `reservation.user_id`, `users.username`,`reservation.package_id`,`reservation.total_people`, `package.name`, `package.min_capacity`,
            `reservation.deposit`,`reservation.deposit_date`,
            `reservation.total_price`, `reservation.token_of_deposit`, `reservation.token_of_payment`,`reservation.payment_date`,`reservation.type_of_payment`,
            `reservation.proof_of_deposit`, `reservation.admin_deposit_check`,`reservation.proof_of_payment`,`reservation.admin_payment_check`,
            `reservation.request_date`, `reservation.check_in`, `reservation.note`,`reservation.deposit_channel`,`reservation.payment_channel`,
            `reservation.status`,`reservation.confirmation_date`,`reservation.feedback`,`reservation.admin_confirm`,
            `reservation.review`,`reservation.rating`,`reservation.response`,
            `reservation.cancel_date`,`reservation.cancel`,`reservation.account_refund`,
            `reservation.proof_refund`,`reservation.refund_date`,`reservation.admin_refund`,`package.custom`,`users.username`,
            `reservation.refund_amount`,`reservation.deposit_check`,`reservation.payment_check`,`reservation.refund_check`")
            ->join('package', 'reservation.package_id = package.id')
            ->join('users', 'reservation.user_id = users.id')
            ->where('reservation.package_id', $id)
            ->get();
        return $query;
    }

    public function get_new_id()
    {
        $lastId = $this->db->table($this->table)->select('id')->orderBy('id', 'ASC')->get()->getLastRow('array');
        if (empty($lastId)) {
            $id = 'R0001';
        } else {
            $count = (int)substr($lastId['id'], 3);
            $id = sprintf('R%04d', $count + 1);
        }
        return $id;
    }

    public function add_new_reservation($reservation = null)
    {
        $insert = $this->db->table($this->table)
            ->insert($reservation);
        return $insert;
    }

    public function update_reservation($id = null, $data = null)
    {
        $query = $this->db->table('reservation')
            ->update($data, ['id' => $id]);
        return $query;
    }
    public function update_detail_reservation($id = null, $data = null)
    {
        $query = $this->db->table('detail_reservation')
            ->update($data, ['reservation_id' => $id]);
        return $query;
    }

    public function update_response($id = null, $data = null)
    {
        $query = $this->db->table('reservation')
            ->update($data, ['id' => $id]);
        return $query;
    }

    public function upload_deposit($id = null, $data = null) {
        $updateData = [
            'deposit_check' => null,
            'deposit_date' => $data['deposit_date'],
            'proof_of_deposit' => $data['proof_of_deposit']
        ];

        $query = $this->db->table('reservation')
                ->update($updateData, ['id' => $id]);
        return $query;
    }

    public function upload_fullpayment ($id = null, $data = null) {

        $updateData = [
            'payment_check' => null,
            'payment_date' => $data['payment_date'],
            'proof_of_payment' => $data['proof_of_payment']
        ];

        $query = $this->db->table('reservation')
            ->update($updateData, ['id' => $id]);
        return $query;
    }

    public function updateDepositCheck($reservation_id, $token_of_deposit, $deposit_check, $deposit_channel, $deposit_date)
    {
        $updateData = array(
            'token_of_deposit' => $token_of_deposit,
            'deposit_check' => $deposit_check,
            'deposit_channel' => $deposit_channel,
            'deposit_date' => $deposit_date,
        );
        $query = $this->db->table('reservation')
            ->update($updateData, ['id' => $reservation_id]);
        return $query;
    }

    public function updatePaymentCheck($reservation_id, $token_of_payment, $payment_check, $payment_channel, $payment_date)
    {
        $updateData = array(
            'token_of_payment' => $token_of_payment,
            'payment_check' => $payment_check,
            'payment_channel' => $payment_channel,
            'payment_date' => $payment_date,
        );
        $query = $this->db->table('reservation')
            ->update($updateData, ['id' => $reservation_id]);
        return $query;
    }

  

    public function update_cancel($id = null, $data = null)
    {

        $query = $this->db->table('reservation')
            ->set($data)
            ->where('id', $id)
            ->update();

        return $query;
    }

    public function upload_refund($id = null, $data = null)
    {

        $updateData = [
            'refund_check' => null,
            'refund_date' => $data['refund_date'],
            'admin_refund' => $data['admin_refund'],
            'proof_refund' => $data['proof_refund']
        ];

        $query = $this->db->table('reservation')
            ->update($updateData, ['id' => $id]);
        return $query;
    }

    // public function upload_fullpayment($id = null, $data = null)
    // {

    //     $updateData = [
    //         'payment_check' => null,
    //         'payment_date' => $data['payment_date'],
    //         'proof_of_payment' => $data['proof_of_payment']
    //     ];

    //     $query = $this->db->table('reservation')
    //         ->update($updateData, ['id' => $id]);
    //     return $query;
    // }
}
