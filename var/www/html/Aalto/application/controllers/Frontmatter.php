<?php



class Frontmatter extends CI_Controller {



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

	    $this->load->model('addfrontmatter_model');

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



    public function add_frontmatter(){

    	if($this->input->post('addfrontmatterSubmit')){

            $where_condition = array(
                'title' => $this->input->post('title'),
                'book_id' => $this->session->userdata('Book_Id')
            );
            
            $frontM_data = (array) $this->addfrontmatter_model->get_details($where_condition, NULL);
            
            if($frontM_data) {
                $this->session->set_flashdata('error_msg', 'Front Matter already exists');
                redirect('frontmatter/add_frontmatter');
            }

            $new_content = str_replace('..//uploads', base_url().'uploads', $this->input->post('pageBody'));

            // for get user specific books from database

            $u_id = array('user_id' =>$this->session->userdata('userId'));

            $data['fetch_data'] = (array) $this->book_model->get_details_list($u_id, NULL);



            if(count($data['fetch_data']) <= 0) {

                $this->session->set_flashdata('error_msg', 'Create book first to add new front matter');

                redirect('frontmatter/add_frontmatter');

            }

    			// for insert data into books table

  				$AddpartData = array(

		            'title' => strip_tags($this->input->post('title')),

		            //for submit as html in db

		           	'content' => $new_content,

		           	'book_id' => $this->session->userdata('Book_Id'),

                    'user_id' => $this->session->userdata('userId'),

		            'created' => date('Y-m-d H:i:s'),

		            );

				//var_dump($AddpartData); die();

		        $insert = $this->addfrontmatter_model->insert($AddpartData);

                if($insert) {

                    $insert_id = $this->db->insert_id();

                    //Activity log for add new front matter

                    if( ! $this->session->userdata('AdminLoggedIn') )

                    {

                        $activity = 'New Front Matter Added';

                    } else {

                        $activity = 'New Front Matter Added by Admin';

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
                $this->session->set_flashdata('success', 'Front Matter added successfully');
		        redirect('frontmatter/edit_frontmatter/'.$insert_id); 

		}

    	$data['common_details'] = $this->commonView;

    	$this->load->view('users/add_frontmatter', $data);

    }



    public function edit_frontmatter($id=FALSE){

    	// for fetch book specific data from books table

    	$frontmatt_id = array(

    		'id' => $id,

            'user_id' => $this->session->userdata('userId'));

        $frontmatt_data = (array) $this->addfrontmatter_model->get_details($frontmatt_id, NULL);



        $count_data = $this->addfrontmatter_model->get_details($frontmatt_id, NULL);

        if(count($count_data) <= 0){

        redirect('frontmatter/add_frontmatter');

        }

        

        if($this->input->post('addfrontmatterSubmit')){
            if($this->input->post('title') != $this->input->post('db_title')) {
                $where_condition = array(
                    'title' => $this->input->post('title'),
                    'book_id' => $this->session->userdata('Book_Id')
                );
                
                $frontM_data = (array) $this->addfrontmatter_model->get_details($where_condition, NULL);
            
                if($frontM_data) {
                    $this->session->set_flashdata('error_msg', 'Front Matter already exists');
                    redirect('frontmatter/edit_frontmatter/'.$id);
                }
            }
        	// for update data into books table
            $new_content = str_replace('../..//uploads', base_url().'uploads', $this->input->post('pageBody'));
            
        	$updateData = array(

                'title' => strip_tags($this->input->post('title')),

		        'content' => $new_content,

                'modified' => date('Y-m-d H:i:s'),

            );



    		$whereData = array(

            'id' => $id,

            );



			$update =  $this->addfrontmatter_model->update($updateData, $whereData, NULL);

			

			if($update) {



                //Activity log for update front matter

                if( ! $this->session->userdata('AdminLoggedIn') )

                {

                    $activity = 'Front Matter Updated';

                } else {

                    $activity = 'Front Matter Updated by Admin';

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



			$this->session->set_flashdata('success', 'Front Matter updated successfully');

            redirect('frontmatter/edit_frontmatter/'.$id);

        	}



        }

        $data['frontmatt_details'] = $frontmatt_data;

    	$data['common_details'] = $this->commonView;

    	$this->load->view('users/add_frontmatter', $data);

    }



    public function delete_frontmatter($id=FALSE){



        $whereData = array(

            'id' => $id,

            );



        $delete =  $this->addfrontmatter_model->delete($whereData, NULL);

        if($delete) {



            //Activity log for delete front matter

            if( ! $this->session->userdata('AdminLoggedIn') )

            {

                $activity = 'Front Matter Deleted';

            } else {

                $activity = 'Front Matter Deleted by Admin';

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



            redirect('organize');

        }

    }



}

?>