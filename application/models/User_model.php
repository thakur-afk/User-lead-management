<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class User_model extends CI_Model {

public function __construct() {
    parent::__construct();
    $this->load->database();  
}

public function get_all_users($start, $length, $search) {
    $this->db->like('name', $search);
    $this->db->or_like('email', $search);
    $this->db->limit($length, $start);
    return $this->db->get('users')->result();
}

public function get_users_count($search) {
    $this->db->like('name', $search);
    $this->db->or_like('email', $search);
    return $this->db->count_all_results('users');
}

public function insert_user($data) {
    return $this->db->insert('users', $data);
}

public function update_user($id, $data) {
    return $this->db->where('id', $id)->update('users', $data);
}

public function delete_user($id) {
    return $this->db->where('id', $id)->delete('users');
}

public function get_user_by_id($id) {
    return $this->db->get_where('users', ['id' => $id])->row();
}
}
