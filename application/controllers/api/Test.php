<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once('Chatfluel.php');

class Test extends CI_Controller {

    function __construct() {
         parent::__construct();
         $this->load->helper('url');
         $this->load->model('common/client_model');
    }
    
    public function index() {
        
        $test = $this->option->get_option("gift_message");
        
        echo "<pre>";
        print_r($test);
        echo "</pre>";
        exit;
//        
//        $updated = $this->option->update_option('user_access_token', 'testdone');
//        
//        echo "<pre>";
//        print_r($page_id);
//        print_r($updated);
//        echo "</pre>";
//        exit;
    }
    
}