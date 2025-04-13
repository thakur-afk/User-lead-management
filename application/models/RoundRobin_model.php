<?php
class RoundRobin_model extends CI_Model {

    public function get_eligible_users($project_id) {
        $this->db->order_by('order_no', 'ASC');
        $users = $this->db->get('round_robin_users')->result();

        foreach ($users as $user) {
            if (empty($user->projects)) return $user; // eligible for all
            $project_ids = explode(',', $user->projects);
            if (in_array($project_id, $project_ids)) return $user;
        }
        return null;
    }

    public function rotate_order($user_id) {
        // Move assigned user to the end of the queue
        $this->db->select_max('order_no');
        $max = $this->db->get('round_robin_users')->row()->order_no;

        $this->db->where('user_id', $user_id);
        $this->db->update('round_robin_users', ['order_no' => $max + 1]);
    }
}
