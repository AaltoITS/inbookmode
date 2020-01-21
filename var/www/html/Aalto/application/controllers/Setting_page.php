<?php

class Setting_page extends CI_Controller {

	var $commonView;

	public function __construct(){

	    parent::__construct();

        if( ! $this->session->userdata('isUserLoggedIn') )

        {

        	redirect('login');

        }

        //redirect to admin dashboard if role is admin

        if($this->session->userdata('Role') == 'Admin')

        {

            redirect('dashboard');

        }

	    $this->load->model('user_model');

	    $this->load->model('setting_model');

        $this->load->model('book_model');

        $this->load->model('activitylog_model');



    /*

     * common data (user, topbar, sidebar, footer)

     */

        // for get user specific books from database

        $u_id = array('user_id' =>$this->session->userdata('userId'));

        $data['fetch_data'] = (array) $this->book_model->get_details_list($u_id, NULL);

	    $data['user'] = (array) $this->user_model->get_details(array('id'=>$this->session->userdata('userId')), NULL);

        $this->commonView  = Array(

			'user' 	  => $data['user'],

			'topbar'  => $this->load->view('topbar', $data, TRUE),

	        'sidebar' => $this->load->view('sidebar', $data, TRUE),

	        'footer'  => $this->load->view('footer', '', TRUE),

        );

    }



    public function index(){

    	$data = array();

    	$SettingData = array();

    	$SettingError = array();

        $db_data = array();

    /*

     * fetch user specific data from db 

     */

        $b_id = array('book_id' => $this->session->userdata('Book_Id'));

        $db_data = (array) $this->setting_model->get_details($b_id, NULL);

    /*

     * on submit click

     */    

    	if($this->input->post('settingSubmit')){

if(empty($this->session->userdata('Book_Id'))) {
    $this->session->set_flashdata('error_msg', "You don't have any book, create book first");
    redirect('create_newbook');
}    

        $count_book = $this->setting_model->get_details($b_id, NULL);

        if(count($count_book) > 0){

        	$updateData = array(

                'comments' => strip_tags($this->input->post('comments')),

                'modified' => date('Y-m-d H:i:s'),

            );

    		$whereData = array(

            'book_id' => $this->session->userdata('Book_Id'),

            );

			$update =  $this->setting_model->update($updateData, $whereData, NULL);

            if($update) {

                //Activity log for settings update

                if( ! $this->session->userdata('AdminLoggedIn') )

                {

                    $activity = 'Settings Updated';

                } else {

                    $activity = 'Settings Updated by Admin';

                }

                $ip_address = $this->input->ip_address();

                $activity_data = array(

                    'user_id' => $this->session->userdata('userId'),

                    'user_name' => $this->session->userdata('userName'),

                    'activity' => $activity,

                    'ip_address' => $ip_address,

                    'created' => date('Y-m-d H:i:s'),

                );

                //var_dump($activity_data); die();

                $insert = $this->activitylog_model->insert($activity_data);

                $this->session->set_flashdata('success', 'Settings updated successfully');

                redirect('setting_page');

            }

        } else {

        	$SettingData = array(

                'comments' => strip_tags($this->input->post('comments')),

                'book_id' => $this->session->userdata('Book_Id'),

                'created' => date('Y-m-d H:i:s'),

            );

            $insert = $this->setting_model->insert($SettingData);

            if($insert) {

                //Activity log for settings update

                if( ! $this->session->userdata('AdminLoggedIn') )

                {

                    $activity = 'Settings Saved';

                } else {

                    $activity = 'Settings Saved by Admin';

                }

                $ip_address = $this->input->ip_address();

                $activity_data = array(

                    'user_id' => $this->session->userdata('userId'),

                    'user_name' => $this->session->userdata('userName'),

                    'activity' => $activity,

                    'ip_address' => $ip_address,

                    'created' => date('Y-m-d H:i:s'),

                );

                //var_dump($activity_data); die();

                $insert = $this->activitylog_model->insert($activity_data);

                $this->session->set_flashdata('success', 'Settings inserted successfully');

                redirect('setting_page');

            }

        }

        }

        $data['db_settings'] = $db_data;

        $data['settings'] = $SettingData;

        $data['SettingError'] = $SettingError;

    	$data['common_details'] = $this->commonView;

    	

    	$this->load->view('users/settings', $data);

    }

}

?>