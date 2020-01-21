<?php



class Chapter extends CI_Controller {



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

	    $this->load->model('addchapter_model');

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



    public function add_chapter(){
//for auto insert title and subtitle
$book_id = array('book_id' =>$this->session->userdata('Book_Id'));
$feth_data['fetch_chapt'] = (array) $this->addchapter_model->get_details_list($book_id, NULL);
$data['title_sr'] = count($feth_data['fetch_chapt']) + 1;
$data['sub_title_sr'] = 1;
//var_dump($data['title_sr']); die();

    	if($this->input->post('addchapterSubmit')){

            $where_condition = array(
                'title' => $this->input->post('title'),
                'book_id' => $this->session->userdata('Book_Id')
            );
            
            $chapter_data = (array) $this->addchapter_model->get_details($where_condition, NULL);
            
            if($chapter_data) {
                $this->session->set_flashdata('error_msg', 'Chapter already exists');
                redirect('chapter/add_chapter');
            }
            
            $new_content = str_replace('..//uploads', '../../uploads', $this->input->post('pageBody'));

            // for get user specific books from database

            $u_id = array('user_id' =>$this->session->userdata('userId'));

            $data['fetch_data'] = (array) $this->book_model->get_details_list($u_id, NULL);



            if(count($data['fetch_data']) <= 0) {

                $this->session->set_flashdata('error_msg', 'Create book first to add new chapter');

                redirect('chapter/add_chapter');

            }

            //var_dump($this->input->post()); die();

    			// for insert data into books table

  				$AddchapterData = array(

		            'title' => strip_tags($this->input->post('title')),

                    'sub_title' => implode("@#@", $this->input->post('sub_title')),
		            //for submit as html in db

		           	'content' => $new_content,

		           	'book_id' => $this->session->userdata('Book_Id'),

                    'user_id' => $this->session->userdata('userId'),

		            'created' => date('Y-m-d H:i:s'),

		            );

				//var_dump($AddchapterData); die();

		        $insert = $this->addchapter_model->insert($AddchapterData);

		        if($insert) {

                    $insert_id = $this->db->insert_id();

                    if( ! $this->session->userdata('AdminLoggedIn') )

                    {

                        $activity = 'New Chapter Added';

                    } else {

                        $activity = 'New Chapter Added by Admin';

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
                $this->session->set_flashdata('success', 'Chapter added successfully');
				redirect('chapter/edit_chapter/'.$insert_id); 

		}

    	$data['common_details'] = $this->commonView;

    	$this->load->view('users/add_chapter', $data);

    }



    public function edit_chapter($id=FALSE){
//for auto insert title and subtitle
$book_id = array('book_id' =>$this->session->userdata('Book_Id'));
$feth_data['fetch_chapt'] = (array) $this->addchapter_model->get_details_list($book_id, NULL, 'id');

foreach ($feth_data['fetch_chapt'] as $key => $value) {
    if($value->id == $id) {
        $data['title_sr'] = $key + 1;
    }
}
//end
    	// for fetch book specific data from books table

    	$chapter_id = array(

    		'id' => $id,

            'user_id' => $this->session->userdata('userId')
        );

        $chapter_data = (array) $this->addchapter_model->get_details($chapter_id, NULL);
//for auto insert title and subtitle
        $schools_array = explode("@#@", $chapter_data['sub_title']);
        $result = count($schools_array);
        if(!empty($chapter_data['sub_title'])) {
        $data['sub_title_sr'] = $result + 1;
    } else {
        $data['sub_title_sr'] = 1;
    }

        //var_dump($data['sub_title_sr']); die();
//end

        $count_data = $this->addchapter_model->get_details($chapter_id, NULL);

        if(count($count_data) <= 0){

        redirect('chapter/add_chapter');

        }



        if($this->input->post('addchapterSubmit')){
            if($this->input->post('title') != $this->input->post('db_title')) {
                $where_condition = array(
                    'title' => $this->input->post('title'),
                    'book_id' => $this->session->userdata('Book_Id')
                );
                
                $chapter_data = (array) $this->addchapter_model->get_details($where_condition, NULL);
                
                if($chapter_data) {
                    $this->session->set_flashdata('error_msg', 'Chapter already exists');
                    redirect('chapter/edit_chapter/'.$id);
                }
            }
        	// for update data into books table
            $new_content = str_replace('../..//uploads', '../../uploads', $this->input->post('pageBody'));
            
        	$updateData = array(

                'title' => strip_tags($this->input->post('title')),

                'sub_title' => implode("@#@", $this->input->post('sub_title')),

		        'content' => $new_content,

                'modified' => date('Y-m-d H:i:s'),

            );



    		$whereData = array(

            'id' => $id,

            );



			$update =  $this->addchapter_model->update($updateData, $whereData, NULL);

			

			if($update) {



                //Activity log for update chapter

                if( ! $this->session->userdata('AdminLoggedIn') )

                {

                    $activity = 'Chapter Updated';

                } else {

                    $activity = 'Chapter Updated by Admin';

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



			$this->session->set_flashdata('success', 'Chapter updated successfully');
            redirect('chapter/edit_chapter/'.$id);

        	}



        }

        $data['chapter_details'] = $chapter_data;

    	$data['common_details'] = $this->commonView;

    	$this->load->view('users/add_chapter', $data);

    }



    public function delete_chapter($id=FALSE){



        $whereData = array(

            'id' => $id,

            );



        $delete =  $this->addchapter_model->delete($whereData, NULL);

        if($delete) {



            //Activity log for delete chapter

            if( ! $this->session->userdata('AdminLoggedIn') )

            {

                $activity = 'Chapter Deleted';

            } else {

                $activity = 'Chapter Deleted by Admin';

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