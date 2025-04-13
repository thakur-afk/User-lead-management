<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lead_model extends CI_Model {
    public function get_all_leads($start, $length, $search) {
        $this->db->select('*');
        $this->db->from('leads');
        if (!empty($search)) {
            $this->db->group_start()
                ->like('name', $search)
                ->or_like('email', $search)
                ->or_like('phone', $search)
                ->group_end();
        }
        $this->db->limit($length, $start);
        return $this->db->get()->result();
    }

    public function get_leads_count($search = '') {
        $this->db->from('leads');
        if (!empty($search)) {
            $this->db->group_start()
                ->like('name', $search)
                ->or_like('email', $search)
                ->or_like('phone', $search)
                ->group_end();
        }
        return $this->db->count_all_results();
    }

    public function insert_lead($data) {
        $this->db->insert('leads', $data);
    }

    public function get_lead_by_id($id) {
        return $this->db->get_where('leads', ['id' => $id])->row();
    }

    public function update_lead($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('leads', $data);
    }

    public function delete_lead($id) {
        $this->db->delete('leads', ['id' => $id]);
    }
    public function assign_lead($lead_id, $user_id) {
        $this->db->where('id', $lead_id);
        return $this->db->update('leads', [
            'assigned_to' => $user_id,
            'assigned_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    public function get_unassigned_leads() {
        $this->db->where('assigned_to', null);
        return $this->db->get('leads')->result();
    }
}
