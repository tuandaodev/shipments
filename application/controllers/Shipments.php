<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

// require_once APPPATH . "/third_party/PHPExcel/autoload.php";

// use PhpOffice\PhpSpreadsheet\Spreadsheet;
// use PhpOffice\PhpSpreadsheet\IOFactory;
// use PhpOffice\PhpSpreadsheet\Reader;
// use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Shipments extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->isLoggedIn();

        $this->load->model('ems/shipments_model', 'db_model');

        $this->global['pageTitle'] = 'Shipments Management';

        $this->load->model('ems/merchant_token_model');
        $this->global['merchant_token_list'] = $this->merchant_token_model->get_tokens();
    }

    public function index() {
        $data['shipments_list'] = $this->db_model->get_list();

        // Load status list
        $this->load->model('ems/shipments_status_model');
        $data['shipments_status'] = $this->shipments_status_model->gets();

        if (isset($_POST['selected_shipment_status']) && !empty($_POST['selected_shipment_status'])) {
            $data['selected_shipment_status'] = $_POST['selected_shipment_status'];
        } else {
            $data['selected_shipment_status'] = "";
        }

        $this->loadViews("shipments/index", $this->global, $data , NULL);
    }
}