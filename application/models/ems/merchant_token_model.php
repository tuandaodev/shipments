<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class merchant_token_model extends CI_Model {

    private $db_name = 'merchant_token';

    public function __construct()
    {
        parent::__construct();
    }
    
    public function get_tokens()
    {
        $query = $this->db->query("SELECT * FROM {$this->db_name}");
        
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }

    public function get_count()
    {
        $query = $this->db->query("SELECT count(*) as count FROM {$this->db_name}");
        
        if ($query->num_rows() > 0) {
            $return = $query->row_array();
            return $return['count'];
        } else {
            return FALSE;
        }
    }
    
    public function get_active_tokens()
    {
        $query = $this->db->query("SELECT * FROM {$this->db_name} WHERE status = 1");
        
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }

    public function get_token($id)
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
    
    public function get_token_cron()
    {
        $query = $this->db->query("SELECT value
                                    FROM {$this->db_name}
                                    WHERE status = 1
                                    ORDER BY RAND()
                                    LIMIT 1");
        
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            return $result['value'];
        } else {
            return FALSE;
        }
    }
    
}
