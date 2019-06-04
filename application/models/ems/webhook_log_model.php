<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class webhook_log_model extends CI_Model {

    private $db_name = 'webhook_log';

    public function __construct()
    {
        parent::__construct();
    }
    
    public function gets()
    {
        $query = $this->db->query("SELECT * FROM {$this->db_name}");
        
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }
    
    public function get($id)
    {
        $query = $this->db->query("SELECT * FROM {$this->db_name} WHERE id=?", array($id));
        
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return FALSE;
        }
    }
    
    public function update($data, $id)
    {
        $where = " id = $id ";
        return $this->db->update($this->db_name, $data, $where);
    }
    
    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->db_name);
    }
    
    public function insert($data)
    {
        $this->db->insert($this->db_name, $data);
        $last_id = $this->db->insert_id();
        
        return $last_id;
    }
}
