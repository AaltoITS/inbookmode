<?php
class Statistic extends CI_Controller {

	var $commonView;

	public function __construct(){

	    parent::__construct();

        if( ! $this->session->userdata('isUserLoggedIn') )
        {
        	redirect('login');
        }

        if($this->session->userdata('Role') == 'Author')
        {
            redirect('dashboard');
        }

	    $this->load->model('user_model');

        $this->load->model('book_model');

	    $this->load->model('statistic_model');

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

        $order_by = array('title' => 'id', 'order' => 'DESC');

        $db_data = (array) $this->statistic_model->get_details_list(NULL, NULL, '*', $order_by);


        //getting username from its id 
        foreach ($db_data as $key => $value) {
            $user_id = array('id' => $value->user_id);
            $user_data = (array) $this->user_model->get_details($user_id, NULL, 'username');
            if(!empty($user_data['username'])) {
                $db_data[$key]->user_id = $user_data['username'];
            }
        }

        //getting book name from its id
        foreach ($db_data as $key => $value) {
            $book_id = array('id' => $value->book_id);
            $book_data = (array) $this->book_model->get_details($book_id, NULL, 'title');
            if(!empty($book_data['title'])) {
                $db_data[$key]->book_id = $book_data['title'];
            }
        }
        
        //var_dump($user_data['username']); die();

        $data['fetch_data'] = $db_data;

    	$data['common_details'] = $this->commonView;

    	$this->load->view('users/statistic', $data);

    }



}

?>