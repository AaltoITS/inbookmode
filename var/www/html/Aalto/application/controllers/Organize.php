<?php

class Organize extends CI_Controller {

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
	    $this->load->model('book_model');
	    $this->load->model('addpart_model');
	    $this->load->model('addchapter_model');
	    $this->load->model('addfrontmatter_model');
	    $this->load->model('addbackmatter_model');

		// for get user specific books from database
	    $u_id = array('user_id' =>$this->session->userdata('userId'));
        $data['fetch_data'] = (array) $this->book_model->get_details_list($u_id, NULL);

        $author_fetch = array(
        	'id' => $this->session->userdata('Book_Id'),
        	'user_id' =>$this->session->userdata('userId'));
        $data['fetch_author'] = (array) $this->book_model->get_details_list($author_fetch, NULL);

	    $data['user'] = (array) $this->user_model->get_details(array('id'=>$this->session->userdata('userId')), NULL);
	    
		$this->commonView  = Array(
			'user' 	  => $data['user'],
			'topbar'  => $this->load->view('topbar', $data, TRUE),
	        'sidebar' => $this->load->view('sidebar', $data, TRUE),
	        'footer'  => $this->load->view('footer', '', TRUE),
        );

    }

    public function index(){
    	// for get user specific books from database
	    $where_id = array(
	    	'user_id' =>$this->session->userdata('userId'),
	    	'book_id' =>$this->session->userdata('Book_Id')
	    	);
	    
        $data['fetch_part'] = (array) $this->addpart_model->get_details_list($where_id, NULL);
        $data['fetch_chapter'] = (array) $this->addchapter_model->get_details_list($where_id, NULL);
        $data['fetch_frontmatter'] = (array) $this->addfrontmatter_model->get_details_list($where_id, NULL);
        $data['fetch_backmatter'] = (array) $this->addbackmatter_model->get_details_list($where_id, NULL);
		//var_dump($data['fetch_chapter']); die();
    	$data['common_details'] = $this->commonView;
    	$this->load->view('users/organize', $data);
    }
}
?>