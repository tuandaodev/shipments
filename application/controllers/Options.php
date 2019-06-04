<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Options extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->isLoggedIn();

        $this->global['pageTitle'] = 'Shipments Management';
    }

    public function index()
    {

        /* Validate form input */
        $this->form_validation->set_rules('cron_token', "Required", 'required');

        /* Data */
        $data['message_public']        = (validation_errors()) ? validation_errors() : NULL;
        $data['options_text'] = $this->option->get_options_text();
        
        if ($this->form_validation->run() == TRUE)
        {
            $data = array(
                'cron_token' => $this->input->post('cron_token'),
                'webhook_token' => $this->input->post('webhook_token'),
            );

            foreach ($data as $key => $value) {
                $this->option->update_option($key, $value);
            }
            
            redirect('options', 'refresh');
        }
        else
        {
            /* Load Template */
            $this->loadViews("options/index", $this->global, $data , NULL);
        }
    }
}
