
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class shipments_model extends CI_Model {

    private $db_name = 'shipments';

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

    public function get_list()
    {
        $sql = 'SELECT 
                ship.*, 
                ss.name as status_name, 
                lw.name as address_ward, 
                ld.name as address_district, 
                lp.name as address_province
            FROM shipments ship
            INNER JOIN shipments_status ss ON ship.status = ss.code
            INNER JOIN location_ward lw ON lw.code = ship.address_ward_code
            INNER JOIN location_district ld ON ld.code = ship.address_district_code
            INNER JOIN location_province lp ON lp.code = ship.address_province_code
            ';

        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }
    
    // for cron jobs to check exists info
    public function get_item_by_id($id)
    {
        $sql = "SELECT * FROM {$this->db_name} WHERE name = ? LIMIT 1";
        $query = $this->db->query($sql, array($id));
        
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
    
    public function get_item_by_order_and_tracking_code($tracking_code, $order_code)
    {
        $sql = "SELECT * FROM {$this->db_name} WHERE code = ? AND order_code = ? LIMIT 1";
        $query = $this->db->query($sql, array($tracking_code, $order_code));
        
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
    
    public function update($id, $data)
    {
        $where = " id = {$id} ";
        $this->db->set('updated', 'NOW()', FALSE);
        return $this->db->update($this->db_name, $data, $where);
    }

    public function update_by_code($code, $data)
    {
        $where = " code = '{$code}' ";
        return $this->db->update($this->db_name, $data, $where);
    }
    
}
