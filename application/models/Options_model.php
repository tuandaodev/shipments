<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Options_model extends CI_Model {
    
    private $table = "options";
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_option($name)
    {   
        $query = $this->db->query("SELECT value FROM {$this->table} WHERE name='$name'");
        
        if ($query->num_rows() > 0) {
            return $query->row()->value;
        } else {
            return FALSE;
        }
    }
    public function get_option_obj($name)
    {   
        $query = $this->db->query("SELECT * FROM {$this->table} WHERE name='$name'");
        
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return FALSE;
        }
    }
    

    public function get_options()
    {   
        $query = $this->db->query("SELECT * FROM {$this->table}");
        
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }
    
    public function get_options_text()
    {   
        $query = $this->db->query("SELECT * FROM {$this->table} WHERE type = 'text'");
        
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }
    
//    public function get_option_gift()
//    {  
//        $query = $this->db->query("SELECT * FROM {$this->table} WHERE name = 'list_gift'");
//        
//        if ($query->num_rows() > 0) {
//            return $query->row_array();
//        } else {
//            return FALSE;
//        }
//    }

    public function update_option($name, $value)
    {
        $data = array('value' => $value);
        
        $this->db->where('name', $name);
        $result = $this->db->update($this->table, $data);
        
        return $result;
    }
}
