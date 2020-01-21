<?php

class Activity_log extends CI_Controller {

	var $commonView;
	public function __construct(){

	    parent::__construct();

        if( ! $this->session->userdata('isUserLoggedIn') )
        {
        	redirect('login');
        }

	    $this->load->model('user_model');
        $this->load->model('book_model');
	    $this->load->model('activitylog_model');

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
		$db_data = array();

	/*
     * fetch user specific data from db and for admin also
     */
    if($this->commonView['user']['role'] == 'Admin')
    {
        $order_by = array('title' => 'id', 'order' => 'DESC');
        $db_data = (array) $this->activitylog_model->get_details_list(NULL, NULL, '*', $order_by);
    }
    else
    {
        $u_id = array('user_id' => $this->commonView['user']['id']);
        $order_by = array('title' => 'id', 'order' => 'DESC');
        $db_data = (array) $this->activitylog_model->get_details_list($u_id, NULL, '*', $order_by);
    }

        //var_dump($db_data); die();
        $data['fetch_data'] = $db_data;
    	$data['common_details'] = $this->commonView;
    	$this->load->view('users/activity_log', $data);
    }

}
?>