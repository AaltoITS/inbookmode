<?php



class Reference extends CI_Controller {



	var $commonView;

	public function __construct(){



	    parent::__construct();



        if( ! $this->session->userdata('isUserLoggedIn') )

        {

        	redirect('login');

        }

        if($this->session->userdata('Role') == 'Admin')

        {   

            redirect('dashboard');

        }

	    $this->load->model('user_model');

	    $this->load->model('book_model');

        $this->load->model('reference_model');

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





if($this->input->post('addrefSubmit')){

            // for get user specific books from database

            if($this->input->post('id') == 0) {

            $check_ref = array(
                'book_id' => $this->session->userdata('Book_Id'),
                'title' => $this->input->post('title'),
            );

            $data['fetch_data'] = (array) $this->reference_model->get_details_list($check_ref, NULL);

            if(count($data['fetch_data']) > 0) {

                $this->session->set_flashdata('error_msg', 'Reference Already Exists');

                redirect('reference');

            }

            $config['upload_path']   = './reference_images/'; 

            $config['allowed_types'] = 'gif|jpg|png'; 

            $config['max_size']      = 1000; 

            $config['max_width']     = 768; 

            $config['max_height']    = 1024;


            $this->load->library('upload', $config);

                         
            if ( ! $this->upload->do_upload('thumbnail')) {

                if (empty($_FILES['thumbnail']['name'])) {
                    $file_name = 'default-thumbnail.png';
                } else {
                    $this->session->set_flashdata('error_msg', $this->upload->display_errors());
                    redirect('reference');
                }

            } else {

                $upload_data = $this->upload->data(); 
                $file_name =   $upload_data['file_name'];

            }

                // for insert data into table

                $AddData = array(
                    'book_id' => $this->session->userdata('Book_Id'),
                    'title' => $this->input->post('title'),
                    'link' => $this->input->post('link'),
                    'thumbnail' => $file_name,
                    'created' => date('Y-m-d H:i:s'),
                );

                //var_dump($AddpartData); die();

                $insert = $this->reference_model->insert($AddData);

                if($insert) {

                    //Activity log for profile update

                    $activity = 'Reference Added';

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

                    $this->session->set_flashdata('success', 'Reference Added successfully');

                    redirect('reference'); 

                }

            } else {
                if($this->input->post('title') != $this->input->post('db_title')) {
                    $check_ref = array(
                        'book_id' => $this->session->userdata('Book_Id'),
                        'title' => $this->input->post('title'),
                    );

                    $data['fetch_data'] = (array) $this->reference_model->get_details_list($check_ref, NULL);

                    if(count($data['fetch_data']) > 0) {
                        $this->session->set_flashdata('error_msg', 'Reference Already Exists');
                        redirect('reference');
                    }
                }
                

                // for update data into books table
                $config['upload_path']   = './reference_images/'; 

                $config['allowed_types'] = 'gif|jpg|png'; 

                $config['max_size']      = 1000; 

                $config['max_width']     = 768; 

                $config['max_height']    = 1024;

                $this->load->library('upload', $config);

                if ( ! $this->upload->do_upload('thumbnail')) {

                    if (empty($_FILES['thumbnail']['name'])) {
                        $file_name =   strip_tags($this->input->post('db_thumbnail'));
                    } else {
                        $this->session->set_flashdata('error_msg', $this->upload->display_errors());
                        redirect('reference');
                    }

                } else {

                    $upload_data = $this->upload->data(); 
                    $file_name =   $upload_data['file_name'];

                    if($this->input->post('db_thumbnail') != 'default-thumbnail.png') {
                        $path = '../Aalto/reference_images/'.$this->input->post('db_thumbnail');
                        unlink($path);
                    }

                }

                $updateData = array(
                    'title' => $this->input->post('title'),
                    'link' => $this->input->post('link'),
                    'thumbnail' => $file_name,
                    'modified' => date('Y-m-d H:i:s'),
                );

                $whereData = array(

                'id' => $this->input->post('id'),

                );

                $update =  $this->reference_model->update($updateData, $whereData, NULL);

                 if($update) {

                    //Activity log for profile update

                    $activity = 'Reference Updated';

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

                    $this->session->set_flashdata('success', 'Reference Updated successfully');

                   redirect('reference'); 

                }

            }

        }

	/*

     * fetch user specific data from db and for admin also

     */
        $book_id = array(
                'book_id' => $this->session->userdata('Book_Id'),
            );

        $db_data = (array) $this->reference_model->get_details_list($book_id, NULL);

        //var_dump($db_data); die();

        $data['fetch_data'] = $db_data;

    	$data['common_details'] = $this->commonView;

    	$this->load->view('users/reference', $data);

    }



    public function delete_reference($id=FALSE){



        $whereData = array(

            'id' => $id,

            );

        $check_ref = (array) $this->reference_model->get_details($whereData, NULL);

        $delete =  $this->reference_model->delete($whereData, NULL);

        if($delete) {
            
        if($check_ref['thumbnail'] != 'default-thumbnail.png') {
            $path = '../Aalto/reference_images/'.$check_ref['thumbnail'];
            unlink($path);
        }
            //Activity log for profile update

            $activity = 'Reference Deleted';

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

            redirect('reference');

        }

    }

}

?>