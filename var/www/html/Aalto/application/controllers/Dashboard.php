<?php



class Dashboard extends CI_Controller {



	var $commonView;

	public function __construct(){



	    parent::__construct();

	    

        if( ! $this->session->userdata('isUserLoggedIn') )

        {

        	redirect('login');



        }



	    $this->load->model('user_model');

	    $this->load->model('book_model');

	    $this->load->model('addpart_model');

	    $this->load->model('addchapter_model');

	    $this->load->model('addfrontmatter_model');

	    $this->load->model('addbackmatter_model');

	    $this->load->model('reference_model');

	}



    public function index($id=FALSE){

    	// start of two condition check (user and its books)

    	$u_id = array('user_id' =>$this->session->userdata('userId'));



    	$data['fetch_data'] = (array) $this->book_model->get_details_list($u_id, NULL);

    	if((count($data['fetch_data'])) > 0) {

    		if(empty($id)){

    			$data['fetch_data'] = (array) $this->book_model->get_details_list($u_id, NULL);

		    	$id = $data['fetch_data']['0']->id;

			}



	    	$two_id = array(

	    		'user_id' =>$this->session->userdata('userId'),

	    		'id' => $id,

	    		);

			$data['fetch_data'] = (array) $this->book_model->get_details(NULL, $two_id);

    	}



    	

    	// end of two condition check (user and its books)

	    	// for count books

	    	if((count($data['fetch_data'])) > 0) {

		    	if(empty($id)) {

		    		$data['fetch_data_session'] = (array) $this->book_model->get_details_list($u_id, NULL);

		        	$data['fetch_data'] = (array) $this->book_model->get_details_list($u_id, NULL);

		    	} else {

		    		$book_id = array('id' => $id);

		    		$data['fetch_data_session'] = (array) $this->book_model->get_details_list($book_id, NULL);

		    		$data['fetch_data'] = (array) $this->book_model->get_details_list($u_id, NULL);

		    	}

				// if count is more than 0 set session of books

		    	if((count($data['fetch_data_session'])) > 0) {

				$this->session->set_userdata('Book_Id',$data['fetch_data_session']['0']->id);

				$this->session->set_userdata('Book_Title',$data['fetch_data_session']['0']->title);

			}

		}

		

	    //for count total active users

	    $where_act_user = array(

	    	'role' => 'Author',

	    	'status' => 'Active',

	    	);

		$data['fetch_active_users'] = (array) $this->user_model->get_details_list($where_act_user, NULL);

		$where_act_reader = array(

	    	'role' => 'Reader',

	    	'status' => 'Active',

	    	);

		$data['fetch_active_rader'] = (array) $this->user_model->get_details_list($where_act_reader, NULL);
		//for count total deactive users

	    $where_deact_user = array(

	    	'role' => 'Author',

	    	'status' => 'Inactive',

	    	);

		$data['fetch_deactive_users'] = (array) $this->user_model->get_details_list($where_deact_user, NULL);

	   	$where_deact_reader = array(

	    	'role' => 'Reader',

	    	'status' => 'Inactive',

	    	);

		$data['fetch_deactive_reader'] = (array) $this->user_model->get_details_list($where_deact_reader, NULL);

	   	//for count total books

	   	$data['fetch_books'] = (array) $this->book_model->get_details_list(NULL, NULL);



	   	$data['user'] = (array) $this->user_model->get_details(array('id'=>$this->session->userdata('userId')), NULL);

	    //for fetch refrences
	   	$where_book = array(
	    	'book_id' => $this->session->userdata('Book_Id'),
	    	);

		$data['fetch_ref'] = (array) $this->reference_model->get_details_list($where_book, NULL);

		$this->commonView  = Array(

			'user' 	  => $data['user'],

			'topbar'  => $this->load->view('topbar', $data, TRUE),

	        'sidebar' => $this->load->view('sidebar', $data, TRUE),

	        'footer'  => $this->load->view('footer', '', TRUE),

        );



        $where_id = array(

	    	'user_id' =>$this->session->userdata('userId'),

	    	'book_id' =>$this->session->userdata('Book_Id')

	    	);

	    

        $data['fetch_part'] = (array) $this->addpart_model->get_details_list($where_id, NULL);

        $data['fetch_chapter'] = (array) $this->addchapter_model->get_details_list($where_id, NULL);

        $data['fetch_frontmatter'] = (array) $this->addfrontmatter_model->get_details_list($where_id, NULL);

        $data['fetch_backmatter'] = (array) $this->addbackmatter_model->get_details_list($where_id, NULL);



        //var_dump($data['fetch_part']); die();



		$data['common_details'] = $this->commonView;

    	$this->load->view('users/dashboard', $data);

    }



}

?>