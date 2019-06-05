<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Webhook extends CI_Controller {

    function __construct() {
         parent::__construct();
    }

    private function webhook_log($ems_transaction, $_POSTDATA) {

        $this->load->model('ems/webhook_log_model');

        $item_insert['ems-transaction']         =       $ems_transaction;
        $item_insert['tracking_code']          =       isset($_POSTDATA['tracking_code']) ? $_POSTDATA['tracking_code'] : '';
        $item_insert['order_code']          =       isset($_POSTDATA['order_code']) ? $_POSTDATA['order_code'] : '';
        $item_insert['status_code']          =       isset($_POSTDATA['status_code']) ? $_POSTDATA['status_code'] : 0;
        $item_insert['status_name']          =       isset($_POSTDATA['status_name']) ? $_POSTDATA['status_name'] : '';
        $item_insert['note']          =       isset($_POSTDATA['note']) ? $_POSTDATA['note'] : '';
        $item_insert['locate']          =       isset($_POSTDATA['locate']) ? $_POSTDATA['locate'] : '';
        $item_insert['datetime']          =       isset($_POSTDATA['datetime']) ? $_POSTDATA['datetime'] : '';
        $item_insert['total_weight']          =       isset($_POSTDATA['total_weight']) ? $_POSTDATA['total_weight'] : '';

        $this->webhook_log_model->insert($item_insert);
    }
    
    public function index() {

        $inputJSON = file_get_contents('php://input');
        $_POSTDATA = json_decode($inputJSON, true);

        $headers = getallheaders();

        $api_key = false;
        if (isset($_REQUEST['key']) && !empty($_REQUEST['key'])) {
            $api_key = $_REQUEST['key'];
        } else {
            $response['code'] = 'error';
            $response['message'] = 'API Key is missing.';
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
        
        $my_api_key = $this->option->get_option('webhook_token');
        if ($api_key != $my_api_key) {
            $response['code'] = 'error';
            $response['message'] = 'API Key is invalid or expired.';
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        log_message('info', 'Webhook' . json_encode($headers) . ' | ' . json_encode($_REQUEST));

        if (isset($headers['ems-transaction']) && !empty($headers['ems-transaction'])) {
            //continue
        } else {
            $response['code'] = 'error';
            $response['message'] = 'ems-transaction is missing.';
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        $ems_transaction = $headers['ems-transaction'];
        $this->webhook_log($ems_transaction, $_POSTDATA);

        // Check POST data
        $missing_data = [];
        if (!isset($_POSTDATA['tracking_code'])) {
            $missing_data[] = 'tracking_code';
        }
        // if (!isset($_POSTDATA['order_code'])) {
        //     $missing_data[] = 'order_code';
        // }
        if (!isset($_POSTDATA['status_code'])) {
            $missing_data[] = 'status_code';
        }
        if (!isset($_POSTDATA['note'])) {
            $missing_data[] = 'note';
        }
        if (!isset($_POSTDATA['locate'])) {
            $missing_data[] = 'locate';
        }
        if (!isset($_POSTDATA['datetime'])) {
            $missing_data[] = 'datetime';
        }

        if (!empty($missing_data)) {
            $response['code'] = 'error';
            if (count($missing_data) > 1) {
                $response['message'] = implode(", ", $missing_data) . ' are missing.';
            } else {
                $response['message'] = implode(", ", $missing_data) . ' is missing.';
            }
            
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        // START MAIN PROCESS
        $this->load->model('ems/shipments_model', 'db_model');

        $tracking_code = isset($_POSTDATA['tracking_code']) ? $_POSTDATA['tracking_code'] : false;
        $order_code = isset($_POSTDATA['order_code']) ? $_POSTDATA['order_code'] : false;

        $detail = [];
        if ($tracking_code && $order_code) {
            $detail = $this->db_model->get_item_by_order_and_tracking_code($tracking_code, $order_code);
            if ($detail && !empty($detail)) {
                //continue
            } else {
                $response['code'] = 'error';
                $response['message'] = 'Tracking Code and Order Code are not available.';
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
        } else {
            $detail = $this->db_model->get_item_by_code($tracking_code);
        }

        if ($detail && !empty($detail)) {
            //continue
        } else {
            $response['code'] = 'error';
            $response['message'] = 'Tracking Code is not available.';
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
        
        // END MAIN PROCESS

        $item_update['status'] = isset($_POSTDATA['status_code']) ? $_POSTDATA['status_code'] : 0;
        $item_update['note'] = isset($_POSTDATA['note']) ? $_POSTDATA['note'] : '';
        $item_update['locate'] = isset($_POSTDATA['locate']) ? $_POSTDATA['locate'] : '';
        $item_update['datetime'] = isset($_POSTDATA['datetime']) ? $_POSTDATA['datetime'] : '';
        if (isset($_POSTDATA['total_weight'])) {
            $item_update['total_weight'] = $_POSTDATA['total_weight'];
        }

        $this->db_model->update($detail['id'], $item_update);
        // get ems-transaction from header to put it in response body
        
        $response['code'] = 'success';
        //$response['message'] = 'OK';
        $response['transaction'] = $ems_transaction;
        
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
    
}