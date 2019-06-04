<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    function __construct() {
         parent::__construct();
    }

    public function index() {
        $this->load->view('public/home');
    }
    
    public function test() {
        
        $arr = array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5);    

        //add the header here
         header('Content-Type: application/json');
         echo json_encode( $arr );
         exit();
        
    }

   
}