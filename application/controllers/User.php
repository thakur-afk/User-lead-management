<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

public function __construct() {
    parent::__construct();
    $this->load->model('User_model');
    $this->load->library('form_validation');
    $this->load->helper('url'); 
}

public function index() {
    $this->load->view('users/index');
}

public function sync_round_robin_users()
{
    $users = $this->db->get('users')->result();
    $existing = $this->db->select('user_id')->get('round_robin_users')->result_array();
    $existing_ids = array_column($existing, 'user_id');

    $order = $this->db->select_max('order_no')->get('round_robin_users')->row()->order_no ?? 0;

    foreach ($users as $user) {
        if (!in_array($user->id, $existing_ids)) {
            $order++;
            $this->db->insert('round_robin_users', [
                'user_id' => $user->id,
                'projects' => NULL, // or '1,2,3'
                'order_no' => $order
            ]);
        }
    }

    echo "Round-robin users synced!";
}


public function fetch_users() {
    $start = $this->input->post('start');
    $length = $this->input->post('length');
    $search = $this->input->post('search')['value'];

    $users = $this->User_model->get_all_users($start, $length, $search);
    $total = $this->User_model->get_users_count($search);

    $data = [];
    foreach ($users as $user) {
        $data[] = [
            $user->id,
            $user->name,
            $user->email,
            $user->status,
            $user->created_at,
            $user->updated_at,
            '<button class="edit" data-id="'.$user->id.'">Edit</button> 
             <button class="delete" data-id="'.$user->id.'">Delete</button>'
        ];
    }

    echo json_encode([
        'draw' => intval($this->input->post('draw')),
        'recordsTotal' => $total,
        'recordsFiltered' => $total,
        'data' => $data
    ]);
}

public function create() {
    $this->form_validation->set_rules('name', 'Name', 'required');
    $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
    $this->form_validation->set_rules('status', 'Status', 'required');

    if ($this->form_validation->run() == FALSE) {
        echo json_encode(['error' => validation_errors()]);
    } else {
        $data = [
            'name' => $this->input->post('name'),
            'email' => $this->input->post('email'),
            'status' => $this->input->post('status'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $this->User_model->insert_user($data);
        $this->sync_new_round_robin_user($this->db->insert_id());

        echo json_encode(['success' => 'User created successfully.']);
    }
}

public function edit($id) {
    $user = $this->User_model->get_user_by_id($id);
    echo json_encode($user);
}

public function update($id) {
    $this->form_validation->set_rules('name', 'Name', 'required');
    $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
    $this->form_validation->set_rules('status', 'Status', 'required');

    if ($this->form_validation->run() == FALSE) {
        echo json_encode(['error' => validation_errors()]);
    } else {
        $data = [
            'name' => $this->input->post('name'),
            'email' => $this->input->post('email'),
            'status' => $this->input->post('status'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $this->User_model->update_user($id, $data);
        echo json_encode(['success' => 'User updated successfully.']);
    }
}

public function delete($id) {
    $this->User_model->delete_user($id);
    echo json_encode(['success' => 'User deleted successfully.']);
}
}
