
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class services_model extends CI_Model {

    private $db_name = 'services';

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
    
    // for cron jobs to check exists info
    public function get_item_by_name($name)
    {
        $sql = "SELECT * FROM {$this->db_name} WHERE name = ? LIMIT 1";
        $query = $this->db->query($sql, array($name));
        
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return FALSE;
        }
    }

    public function get_item_by_code($code)
    {
        $sql = "SELECT * FROM {$this->db_name} WHERE code = ? LIMIT 1";
        $query = $this->db->query($sql, array($code));
        
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return FALSE;
        }
    }
    
    public function insert($data)
    {
        $this->db->insert($this->db_name, $data);
        $last_id = $this->db->insert_id();
        
        return $last_id;
    }
    
}
