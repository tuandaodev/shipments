<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Merchant_token extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->isLoggedIn();

        $this->load->model('ems/merchant_token_model');
        $this->global['pageTitle'] = 'Token Management';
    }

    public function index() {

        if($this->isAdmin() == FALSE)
        {
            $this->loadThis();
        } else {
            /* Get all merchant_token */
            $data['token_list'] = $this->merchant_token_model->get_tokens();
            /* Load Template */

            $this->loadViews("merchant_token/index", $this->global, $data , NULL);
        }
    }

    public function create() {
        if($this->isAdmin() == FALSE)
        {
            $this->loadThis();
        } else {
            /* Validate form input */
            
            $this->form_validation->set_rules('value', 'merchant_token_value', 'required');

            if ($this->form_validation->run() == TRUE) {
                
                $insert_data['name'] = $this->input->post('name');
                $insert_data['value'] = $this->input->post('value');
                $insert_data['status'] = $this->input->post('status') ? $this->input->post('status') : 0;
                
                $new_id = $this->merchant_token_model->insert($insert_data);
                if ($new_id) {
                    redirect('merchant_token', 'refresh');
                }
            } else {
                $data['name'] = array(
                    'name' => 'name',
                    'id' => 'name',
                    'type' => 'text',
                    'class' => 'form-control',
                    'value' => $this->form_validation->set_value('name')
                );
                $data['value'] = array(
                    'name' => 'value',
                    'id' => 'value',
                    'type' => 'text',
                    'class' => 'form-control',
                    'value' => $this->form_validation->set_value('value')
                );

                $data['status'] = array(
                    'name' => 'status',
                    'id' => 'status',
                    'type' => 'checkbox',
    //                'class' => 'form-control',
                    'value' => '1',
                    'checked' => TRUE,
                );

                /* Load Template */
                $this->loadViews("merchant_token/create", $this->global, $data , NULL);
            }
        }
    }

    public function delete($id) {
        
        if($this->isAdmin() == FALSE)
        {
            $this->loadThis();
        } else {
           $id = (int) $id;
            $this->merchant_token_model->delete($id);
            
            redirect('merchant_token', 'refresh');
        }
    }

    public function edit($id) {
        if($this->isAdmin() == FALSE)
        {
            $this->loadThis();
        } else {
            $id = (int) $id;

            /* Validate form input */
            $this->form_validation->set_rules('value', 'merchant_token_value', 'required');

            if ($this->form_validation->run() == TRUE)
            {   
                $update_id = $this->input->post('id');
                $update_data['name'] = $this->input->post('name');
                $update_data['value'] = $this->input->post('value');
                $update_data['status'] = $this->input->post('status') ? $this->input->post('status') : 0;
                    $update_id = $this->merchant_token_model->update($update_data, $update_id);
                    if ($update_id)
                    {
                        // Update thanh cong
                        redirect('merchant_token', 'refresh');
                    } else {
                        // Update khong thanh cong //TODO
                        redirect('merchant_token', 'refresh');
                    }
            }
            else
            {
                $token_info = $this->merchant_token_model->get_token($id);
                
                if (!$token_info) {
                    redirect('admin/merchant_token', 'refresh');
                }
                
                $data['id'] = $token_info['id'];
                        
                $data['name'] = array(
                        'name'  => 'name',
                        'id'    => 'name',
                        'type'  => 'text',
                        'class' => 'form-control',
                        'value' => $token_info['name']
                );
                
                $data['value'] = array(
                        'name'  => 'value',
                        'id'    => 'value',
                        'type'  => 'text',
                        'class' => 'form-control',
                        'value' => $token_info['value']
                );

                $data['status'] = array(
                        'name'  => 'status',
                        'id'    => 'status',
                        'type'  => 'checkbox',
    //    'class' => 'form-control',
                        'value' => '1',
                        'checked' => $token_info['status'],
                );

                /* Load Template */
                $this->loadViews("merchant_token/edit", $this->global, $data , NULL);
            }
        }
    }
}
