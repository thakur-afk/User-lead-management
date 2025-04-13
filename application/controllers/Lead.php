<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lead extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Lead_model');
        $this->load->library('form_validation');
        $this->load->database(); 
    }

    public function index() {
        $this->load->view('leads/index');
    }

    public function fetch_leads() {
        $start = $this->input->post('start');
        $length = $this->input->post('length');
        $search = $this->input->post('search')['value'];

        $leads = $this->Lead_model->get_all_leads($start, $length, $search);
        $total = $this->Lead_model->get_leads_count($search);

        $data = [];
        foreach ($leads as $lead) {
            $data[] = [
                $lead->id,
                $lead->name,
                $lead->email,
                $lead->phone,
                $lead->project_id,
                $lead->status,
                $lead->assigned_to,
                $lead->assigned_at,
                $lead->created_at,
                $lead->updated_at,
                '<button class="btn btn-sm btn-primary edit" data-id="' . $lead->id . '">Edit</button>
                 <button class="btn btn-sm btn-danger delete" data-id="' . $lead->id . '">Delete</button>'
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
        $this->load->model('Lead_model');
        $this->load->model('RoundRobin_model');
    
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('phone', 'Phone', 'required');
        $this->form_validation->set_rules('project_id', 'Project', 'required');
        $this->form_validation->set_rules('status', 'Status', 'required');
    
        if ($this->form_validation->run() === FALSE) {
            echo json_encode(['error' => validation_errors()]);
        } else {
            $data = [
                'name'        => $this->input->post('name'),
                'email'       => $this->input->post('email'),
                'phone'       => $this->input->post('phone'),
                'project_id'  => $this->input->post('project_id'),
                'status'      => $this->input->post('status'),
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s')
            ];
    
            $this->db->insert('leads', $data);
            $lead_id = $this->db->insert_id();
    
            // Automatically assign the lead
            $project_id = $data['project_id'];
            $user = $this->RoundRobin_model->get_eligible_users($project_id);
    
            if ($user) {
                $this->Lead_model->assign_lead($lead_id, $user->user_id);
                $this->RoundRobin_model->rotate_order($user->user_id);
            }
    
            echo json_encode(['success' => true]);
        }
    }
    

    public function edit($id) {
        $lead = $this->Lead_model->get_lead_by_id($id);
        echo json_encode($lead);
    }

    public function update($id) {
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('phone', 'Phone', 'required');
        $this->form_validation->set_rules('project_id', 'Project', 'required');
        $this->form_validation->set_rules('status', 'Status', 'required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['error' => validation_errors()]);
        } else {
            $data = [
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
                'project_id' => $this->input->post('project_id'),
                'status' => $this->input->post('status'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $this->Lead_model->update_lead($id, $data);
            echo json_encode(['success' => 'Lead updated successfully.']);
        }
    }

    public function delete($id) {
        $this->Lead_model->delete_lead($id);
        echo json_encode(['success' => 'Lead deleted successfully.']);
    }
    public function assign_round_robin() {
        $this->load->model('RoundRobin_model');
    
        $unassigned_leads = $this->Lead_model->get_unassigned_leads();
    
        foreach ($unassigned_leads as $lead) {
            $user = $this->RoundRobin_model->get_eligible_users($lead->project_id);
    
            if ($user) {
                $this->Lead_model->assign_lead($lead->id, $user->user_id);
                $this->RoundRobin_model->rotate_order($user->user_id);
            }
        }
    
        echo "Round-robin assignment completed.";
    }
    
}
