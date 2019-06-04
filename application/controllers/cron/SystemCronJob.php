<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SystemCronJob extends Public_Controller {

    private $merchant_token = "";
    
    public function __construct()
    {
        parent::__construct();
    }

    public function main() {
        
        $cron_token = false;
        if (isset($_GET['cron_token']) && !empty($_GET['cron_token'])) {
            $cron_token = $_GET['cron_token'];
        } else {
            $message_403 = "You don't have access to the url you where trying to reach.";
            show_error($message_403 , 403 );
        }
        
        $my_cron_token = $this->option->get_option('cron_token');
        
        if ($cron_token != $my_cron_token) {
            $message_403 = "You don't have access to the url you where trying to reach.";
            show_error($message_403 , 403 );
        }
        
        $this->load->model('ems/merchant_token_model');
        $this->merchant_token = $this->merchant_token_model->get_token_cron();
        
        // Get thong tin dich vu
        $return = $this->get_services();
        $return = $this->get_services_addition();
        $return = $this->get_shipments_status();
        
        // Get thong tin vi tri
        $return = $this->get_location_province();
        $return = $this->get_location_district();
        $return = $this->get_location_ward();
        
        echo "DONE.";
    }
    
    private function get_location_province() {
        
        $this->load->model('ems/location_province_model', 'db_model');
        
        $method = "GET";
        $url = "https://call.ems.com.vn/location/province";
        $data = array(
            'merchant_token'    =>      $this->merchant_token,
        );
        
        $response = callAPI($method, $url, $data);
        
        if (isset($response['data']) && !empty($response['data'])) {
            foreach ($response['data'] as $item) {
                $item_result = $this->db_model->get_item_by_name($item['name']);
                if (!$item_result) {
                    $item_insert['name'] = $item['name'];
                    $item_insert['full_name'] = $item['full_name'];
                    $item_insert['code'] = $item['code'];
                    $this->db_model->insert($item_insert);
                }
            }
        }
        
        return true;
//        $return = $this->db_model->gets();
//        return $return;
    }
    
    private function get_location_district() {
        
        $this->load->model('ems/location_province_model');
        $this->load->model('ems/location_district_model', 'db_model');
        
        $parent_list = $this->location_province_model->gets();
        
        foreach ($parent_list as $parent_item) {
            $method = "GET";
            $url = "https://call.ems.com.vn/location/district";
            $data = array(
                'merchant_token'    =>      $this->merchant_token,
                'province_code'     =>      $parent_item['code'],
            );

            $response = callAPI($method, $url, $data);

            if (isset($response['data']) && !empty($response['data'])) {
                foreach ($response['data'] as $item) {
                    $item_result = $this->db_model->get_item_by_name($item['name']);
                    if (!$item_result) {
                        $item_insert['name'] = $item['name'];
                        $item_insert['full_name'] = $item['full_name'];
                        $item_insert['code'] = $item['code'];
                        $item_insert['province_id'] = $parent_item['code'];
                        $this->db_model->insert($item_insert);
                    }
                }
            }
        }
        
        return true;
//        $return = $this->db_model->gets();
//        return $return;
    }
    
    private function get_location_ward() {
        
        $this->load->model('ems/location_district_model');
        $this->load->model('ems/location_ward_model', 'db_model');
        
        $parent_list = $this->location_district_model->gets();
        
        foreach ($parent_list as $parent_item) {
            $method = "GET";
            $url = "https://call.ems.com.vn/location/ward";
            $data = array(
                'merchant_token'    =>      $this->merchant_token,
                'district_code'     =>      $parent_item['code'],
            );

            $response = callAPI($method, $url, $data);

            if (isset($response['data']) && !empty($response['data'])) {
                foreach ($response['data'] as $item) {
                    $item_result = $this->db_model->get_item_by_code($item['code']);
                    if (!$item_result) {
                        $item_insert['name'] = $item['name'];
                        $item_insert['full_name'] = $item['full_name'];
                        $item_insert['code'] = $item['code'];
                        $item_insert['district_id'] = $parent_item['code'];
                        $this->db_model->insert($item_insert);
                    }
                }
            } else {
                try {
                    log_message('error', json_encode($response));
                } catch (Exception $ex) {

                }
            }
        }
        
        return true;
//        $return = $this->db_model->gets();
//        return $return;
    }
    
    private function get_services() {
        
        $this->load->model('ems/services_model', 'db_model');
        
        $method = "GET";
        $url = "https://call.ems.com.vn/services";
        $data = array(
            'merchant_token'    =>      $this->merchant_token,
        );
        
        $response = callAPI($method, $url, $data);
        
        if (isset($response['data']) && !empty($response['data'])) {
            foreach ($response['data'] as $item) {
                $item_result = $this->db_model->get_item_by_name($item['name']);
                if (!$item_result) {
                    $item_insert['name'] = $item['name'];
                    $item_insert['code'] = $item['code'];
                    $this->db_model->insert($item_insert);
                }
            }
        }
        
        return true;
//        $return = $this->db_model->gets();
//        return $return;
    }
    
    private function get_services_addition() {
        
        $this->load->model('ems/services_addition_model', 'db_model');
        
        $method = "GET";
        $url = "https://call.ems.com.vn/services/addition";
        $data = array(
            'merchant_token'    =>      $this->merchant_token,
        );
        
        $response = callAPI($method, $url, $data);
        
        if (isset($response['data']) && !empty($response['data'])) {
            foreach ($response['data'] as $item) {
                $item_result = $this->db_model->get_item_by_name($item['name']);
                if (!$item_result) {
                    $item_insert['name'] = $item['name'];
                    $item_insert['code'] = $item['code'];
                    $this->db_model->insert($item_insert);
                }
            }
        }
        
        $return = $this->db_model->gets();
        return $return;
    }
    
    private function get_shipments_status() {
        
        $this->load->model('ems/shipments_status_model', 'db_model');
        
        $method = "GET";
        $url = "https://call.ems.com.vn/shipments/status";
        $data = array(
            'merchant_token'    =>      $this->merchant_token,
        );
        
        $response = callAPI($method, $url, $data);
        
        if (isset($response['data']) && !empty($response['data'])) {
            foreach ($response['data'] as $item) {
                $item_result = $this->db_model->get_item_by_name($item['name']);
                if (!$item_result) {
                    $item_insert['name'] = $item['name'];
                    $item_insert['code'] = $item['code'];
                    $this->db_model->insert($item_insert);
                }
            }
        }
        
        return true;
//        $return = $this->db_model->gets();
//        return $return;
    }

}
