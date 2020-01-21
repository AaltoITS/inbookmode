<?php



class Backmatter extends CI_Controller {



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

	    $this->load->model('addbackmatter_model');

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



    public function add_backmatter(){

    	if($this->input->post('addbackmatterSubmit')){

            $where_condition = array(
                'title' => $this->input->post('title'),
                'book_id' => $this->session->userdata('Book_Id')
            );
            
            $backM_data = (array) $this->addbackmatter_model->get_details($where_condition, NULL);
            
            if($backM_data) {
                $this->session->set_flashdata('error_msg', 'Back Matter already exists');
                redirect('backmatter/add_backmatter');
            }

            $new_content = str_replace('..//uploads', '../../uploads', $this->input->post('pageBody'));

            // for get user specific books from database

            $u_id = array('user_id' =>$this->session->userdata('userId'));

            $data['fetch_data'] = (array) $this->book_model->get_details_list($u_id, NULL);



            if(count($data['fetch_data']) <= 0) {

                $this->session->set_flashdata('error_msg', 'Create book first to add new back matter');

                redirect('backmatter/add_backmatter');

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

		        $insert = $this->addbackmatter_model->insert($AddpartData);

		        if($insert) {

                    $insert_id = $this->db->insert_id();

                    //Activity log for add new back matter

                    if( ! $this->session->userdata('AdminLoggedIn') )

                    {

                        $activity = 'New Back Matter Added';

                    } else {

                        $activity = 'New Back Matter Added by Admin';

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
                $this->session->set_flashdata('success', 'Back Matter added successfully');
				redirect('backmatter/edit_backmatter/'.$insert_id); 

		}

    	$data['common_details'] = $this->commonView;

    	$this->load->view('users/add_backmatter', $data);

    }



    public function edit_backmatter($id=FALSE){

    	// for fetch book specific data from books table

    	$backmatt_id = array(

    		'id' => $id,

            'user_id' => $this->session->userdata('userId'));

        $backmatt_data = (array) $this->addbackmatter_model->get_details($backmatt_id, NULL);



        $count_data = $this->addbackmatter_model->get_details($backmatt_id, NULL);

        if(count($count_data) <= 0){

        redirect('backmatter/add_backmatter');

        }



        if($this->input->post('addbackmatterSubmit')){
            if($this->input->post('title') != $this->input->post('db_title')) {
                $where_condition = array(
                    'title' => $this->input->post('title'),
                    'book_id' => $this->session->userdata('Book_Id')
                );
                
                $backM_data = (array) $this->addbackmatter_model->get_details($where_condition, NULL);
            
                if($backM_data) {
                    $this->session->set_flashdata('error_msg', 'Back Matter already exists');
                    redirect('backmatter/edit_backmatter/'.$id);
                }
            }
        	// for update data into books table
            $new_content = str_replace('../..//uploads', '../../uploads', $this->input->post('pageBody'));
            
        	$updateData = array(

                'title' => strip_tags($this->input->post('title')),

		        'content' => $new_content,

                'modified' => date('Y-m-d H:i:s'),

            );



    		$whereData = array(

            'id' => $id,

            );



			$update =  $this->addbackmatter_model->update($updateData, $whereData, NULL);

			

			if($update) {



                //Activity log for update back matter

                if( ! $this->session->userdata('AdminLoggedIn') )

                {

                    $activity = 'Back Matter Updated';

                } else {

                    $activity = 'Back Matter Updated by Admin';

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



			$this->session->set_flashdata('success', 'Back Matter updated successfully');

            redirect('backmatter/edit_backmatter/'.$id);

        	}



        }

        $data['backmatt_details'] = $backmatt_data;

    	$data['common_details'] = $this->commonView;

    	$this->load->view('users/add_backmatter', $data);

    }



    public function delete_backmatter($id=FALSE){



        $whereData = array(

            'id' => $id,

            );



        $delete =  $this->addbackmatter_model->delete($whereData, NULL);

        if($delete) {



            //Activity log for delete back matter

            if( ! $this->session->userdata('AdminLoggedIn') )

            {

                $activity = 'Back Matter Deleted';

            } else {

                $activity = 'Back Matter Deleted by Admin';

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