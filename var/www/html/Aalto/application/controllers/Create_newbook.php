<?php



class Create_newbook extends CI_Controller {



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

        $this->load->model('activitylog_model');

        $this->load->model('addfrontmatter_model');
        $this->load->model('addchapter_model');
        $this->load->model('addpart_model');
        $this->load->model('addbackmatter_model');
        $this->load->model('reference_model');

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

    	if($this->input->post('newbookSubmit')){

    		$where_condition = array(
	    		'title' => $this->input->post('title'),
	    		'user_id' => $this->session->userdata('userId')
	    	);
        	
        	$book_data = (array) $this->book_model->get_details($where_condition, NULL);
        	
        	if($book_data) {
        		$this->session->set_flashdata('error_msg', 'Book already exists');
        		$this->session->set_flashdata('myData',$this->input->post());
                redirect('create_newbook');
        	}

    		//for upload cover image

    		$config['upload_path']   = './uploads/'; 

			$config['allowed_types'] = 'gif|jpg|png'; 

			$config['max_size']      = 1000; 

			$config['max_width']     = 768; 

			$config['max_height']    = 1024;



			$this->load->library('upload', $config);

			if ( ! $this->upload->do_upload('cover_image')) {

				if (empty($_FILES['cover_image']['name'])) {
                    $file_name = 'default-book.jpg';
                } else {
                    $this->session->set_flashdata('error_msg', $this->upload->display_errors());
                    $this->session->set_flashdata('myData',$this->input->post());
                    redirect('create_newbook');
                }

			} else {

				$upload_data = $this->upload->data(); 
  				$file_name =   $upload_data['file_name'];

			}

				// for insert data into books table

  				$NewbookData = array(

		            'title' => strip_tags($this->input->post('title')),

		            'sub_title' => strip_tags($this->input->post('sub_title')),

		            'author' => strip_tags($this->input->post('author')),

		            'c_author' => implode(",", $this->input->post('c_author')),

		            'tags' => strip_tags($this->input->post('tags')),

		            'cover_image' => $file_name,

		            'privacy_status' => strip_tags($this->input->post('privacy_status')),

		            'user_id' => $this->commonView['user']['id'],

		            'created' => date('Y-m-d H:i:s'),

		            );



		        $insert = $this->book_model->insert($NewbookData);



		        if($insert) {

		        	$insert_id = $this->db->insert_id();

		        	//Activity log for create new book

	                if( ! $this->session->userdata('AdminLoggedIn') )

	                {

	                    $activity = 'New Book Created';

	                } else {

	                    $activity = 'New Book Created by Admin';

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

				}
				$this->session->set_flashdata('success', 'Book created successfully');
		        redirect('dashboard/index/'.$insert_id); 

		}

    	$data['common_details'] = $this->commonView;

    	$this->load->view('users/create_newbook', $data);

    }



    public function edit_book($id=FALSE){

    	// for fetch book specific data from books table
    	if(empty($id)) {
    		redirect('create_newbook'); 
    	}
    	$book_id = array(

    		'id' => $id,

    		'user_id' =>$this->session->userdata('userId'));

        $book_data = (array) $this->book_model->get_details($book_id, NULL);



        if($this->input->post('editbookSubmit')){

        	if($this->input->post('title') != $this->input->post('db_title')) {
        		$where_condition = array(
		    		'title' => $this->input->post('title'),
		    		'user_id' => $this->session->userdata('userId')
		    	);
	        	
	        	$book_data = (array) $this->book_model->get_details($where_condition, NULL);
	        	if($book_data) {
	        		$this->session->set_flashdata('error_msg', 'Book already exists');
	        		$this->session->set_flashdata('myData',$this->input->post());
	                redirect('create_newbook/edit_book/'.$id);
	        	}
        	}

        	// for upload cover image

        	$config['upload_path']   = './uploads/'; 

			$config['allowed_types'] = 'gif|jpg|png'; 

			$config['max_size']      = 1000; 

			$config['max_width']     = 768; 

			$config['max_height']    = 1024;



			$this->load->library('upload', $config);



			if ( ! $this->upload->do_upload('cover_image')) {

				if (empty($_FILES['cover_image']['name'])) {
                    $file_name =   strip_tags($this->input->post('db_cover_image'));
                } else {
                    $this->session->set_flashdata('error_msg', $this->upload->display_errors());
                    $this->session->set_flashdata('myData',$this->input->post());
                    redirect('create_newbook/edit_book/'.$id);
                }

			} else {

				$upload_data = $this->upload->data();
  				$file_name =   $upload_data['file_name'];

  				if($this->input->post('db_cover_image') != 'default-book.jpg') {
	            	$path = '../Aalto/uploads/'.$this->input->post('db_cover_image');
	                unlink($path);
	            }

			}

			// for update data into books table

        	$updateData = array(

                'title' => strip_tags($this->input->post('title')),

		        'sub_title' => strip_tags($this->input->post('sub_title')),

		        'author' => strip_tags($this->input->post('author')),

		        'c_author' => implode(",", $this->input->post('c_author')),

		        'tags' => strip_tags($this->input->post('tags')),

		        'cover_image' => $file_name,

		        'privacy_status' => strip_tags($this->input->post('privacy_status')),

                'modified' => date('Y-m-d H:i:s'),

            );



    		$whereData = array(

            'id' => $id,

            );



			$update =  $this->book_model->update($updateData, $whereData, NULL);

			



			if($update) {



				//Activity log for update book

	            if( ! $this->session->userdata('AdminLoggedIn') )

	            {

	                $activity = 'Book Updated';

	            } else {

	                $activity = 'Book Updated by Admin';

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



			$this->session->set_flashdata('success', 'Book updated successfully');

			$this->session->set_userdata('Book_Title',$this->input->post('title'));
			
            redirect('create_newbook/edit_book/'.$id);

        }



        }

        $data['book_details'] = $book_data;

    	$data['common_details'] = $this->commonView;

    	$this->load->view('users/edit_book', $data);

    }



    public function delete_book($id=FALSE){

    	$whereData = array(

            'id' => $id,

            );

    	$check_book = (array) $this->book_model->get_details($whereData, NULL);

  		$delete =  $this->book_model->delete($whereData, NULL);

		if($delete) {
			
			if($check_book['cover_image'] != 'default-book.jpg') {
				$path = '../Aalto/uploads/'.$check_book['cover_image'];
				unlink($path);
			}
			$whereBook = array(
            	'book_id' => $id,
            );

			$delete_FrontMatter =  $this->addfrontmatter_model->delete($whereBook, NULL);
			$delete_Chapter =  $this->addchapter_model->delete($whereBook, NULL);
			$delete_Part =  $this->addpart_model->delete($whereBook, NULL);
			$delete_BackMatter =  $this->addbackmatter_model->delete($whereBook, NULL);
			$delete_reference =  $this->reference_model->delete($whereBook, NULL);

			//Activity log for delete book

	        if( ! $this->session->userdata('AdminLoggedIn') )

	        {

	            $activity = 'Book Deleted';

	        } else {

	            $activity = 'Book Deleted by Admin';

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
	        
	    if($id == $this->session->userdata('Book_Id')) {
	        $this->session->unset_userdata('Book_Id');
	        $this->session->unset_userdata('Book_Title');
	    }
            redirect('catalog');

        }

    }



}

?>