<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class CronJob extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        
    }

    public function main() {
        
        $cron_token = false;
        if (isset($_GET['cron_token']) && !empty($_GET['cron_token'])) {
            $cron_token = $_GET['cron_token'];
        } else {
            exit("Exit;");
        }
        
        $my_cron_token = $this->option->get_option('cron_token');
        
        if ($cron_token != $my_cron_token) {
            exit("Exit;");
        }
        

        $return = $this->get_danh_sach_buu_gui();
        
        echo "<pre>";
        print_r($return);
        echo "</pre>";
        exit;
        
    }

    public function get_buu_cuc() {
        $this->load->library('simple_html_dom');
        $html = file_get_html('https://ems.com.vn/Portal/Detail_VNDC.aspx?id=EF090091965VN');

        echo $html->find('table[id=GRV_THONG_TIN_DONG_CHUYEN] td', -1)->plaintext;
    }

    
    private function get_danh_sach_buu_gui() {
        
        $this->load->model('ems/merchant_token_model');
        $this->load->model('ems/shipments_model', 'db_model');

        //$this->load->model('ems/shipments_status_model', 'status_model');
        
        $merchant_tokens = $this->merchant_token_model->get_active_tokens();
        //$status_list = $this->status_model->gets();
        
        foreach ($merchant_tokens as $token) {

            $check_paging = true;
            $check_call_code = true;
            $page = 1;

            while ($check_paging && $check_call_code) {
                
                $method = "GET";
                $url = "https://call.ems.com.vn/shipments";
                $data = array(
                    'merchant_token'    =>      $token['value'],
                    'page'              =>      $page,
                    //'per_page'              =>      3,
                );

                $response = callAPI($method, $url, $data);

                if (isset($response['code']) && !empty($response['code'])) {
                    if ($response['code'] == 'success') {

                    } else {
                        $check_call_code = false;
                        log_message('error', "Call API code is not success: " . json_encode($response));
                    }
                } else {
                    $check_call_code = false;
                    log_message('error', "Call API return dont have CODE: " . json_encode($response));
                }

                if (isset($response['meta']) && !empty($response['meta'])) {
                    if (isset($response['meta']['current_page']) && $response['meta']['current_page']) {
                        if ($response['meta']['current_page'] == $response['meta']['last_page']) {
                            $check_paging = false;
                        } elseif ($response['meta']['current_page'] < $response['meta']['last_page']) {
                            $page++;
                        }
                    } else {
                        $check_paging = false;
                    }
                    
                } else {
                    $check_paging = false;
                    log_message('error', "Call API return dont have META: " . json_encode($response));
                }

                if (isset($response['data']) && !empty($response['data'])) {
                    foreach ($response['data'] as $item) {
                        $item_result = $this->db_model->get_item_by_code($item['code']);
                        if (!$item_result) {
                            //
                            $item_insert['token_id']                =       $token['id'];
                            //
                            $item_insert['code']                    =       $item['code'];
                            $item_insert['warehouse_code']          =       $item['warehouse_code'];
                            $item_insert['order_code']              =       $item['order_code'];
                            $item_insert['customer_code']           =       $item['customer_code'];
                            $item_insert['pos_code']                =       $item['pos_code'];
                            $item_insert['status']                  =       $item['status'];
                            //$item_insert['status_code']             =       null;
                            $item_insert['ref_code']                =       $item['ref_code'];
                            $item_insert['product_name']            =       $item['product_name'];
                            $item_insert['total_amount']            =       $item['total_amount'];
                            $item_insert['total_quantity']          =       $item['total_quantity'];
                            $item_insert['total_weight']            =       $item['total_weight'];
                            $item_insert['money_collect']           =       $item['money_collect'];
                            $item_insert['total_fee']               =       $item['total_fee'];
                            $item_insert['description']             =       $item['description'];
                            $item_insert['can_check']               =       $item['can_check'];
                            $item_insert['is_fragile']              =       $item['is_fragile'];
                            $item_insert['service_type']            =       $item['service_type'];
                            
                            if (!empty($item['addition_service']) && is_array($item['addition_service'])) {
                                $item_insert['addition_service']    = implode(",", $item['addition_service']);
                            }
                            
                            $item_insert['payment_config']          =       $item['payment_config'];
                            $item_insert['estimated_delivery_time'] =       $item['estimated_delivery_time'];

                            $time_temp              =       $item['created_at'];
                            $GMT = new DateTimeZone("GMT");
                            $date = new DateTime( $time_temp, $GMT );
                            $date->setTimezone( new DateTimeZone('Asia/Ho_Chi_Minh') );
                            $item_insert['created_at'] = $date->format('Y-m-d H:i:s'); 
                            
                            //shipping_address
                            if (isset($item['shipping_address']) && !empty($item['shipping_address'])) {
                                $shipping_address = $item['shipping_address'];
                                $item_insert['address_name']                    =       $shipping_address['name'];
                                $item_insert['address_organization']            =       $shipping_address['organization'];
                                $item_insert['address_email']                   =       $shipping_address['email'];
                                $item_insert['address_phone']                   =       $shipping_address['phone'];
                                $item_insert['address_postal_code']             =       $shipping_address['postal_code'];
                                $item_insert['address_country_code']            =       $shipping_address['country_code'];
                                $item_insert['address_street']                  =       $shipping_address['street'];
                                $item_insert['address_ward_code']               =       $shipping_address['ward_code'];
                                $item_insert['address_province_code']           =       $shipping_address['province_code'];
                                $item_insert['address_district_code']           =       $shipping_address['district_code'];
                                $item_insert['address_full']                    =       $shipping_address['full'];
                            }
                            
                            $this->db_model->insert($item_insert);
                        }
                    }
                }
            }   //while

        }   //foreach
        
//        return true;
        $return = $this->db_model->gets();
        return $return;
    }

}
