<?php



class All_users extends CI_Controller {



	var $commonView;

	public function __construct(){



	    parent::__construct();



        if( ! $this->session->userdata('isUserLoggedIn') )

        {

        	redirect('login');

        }

        //redirect to user dashboard if role is user

        if($this->session->userdata('Role') == 'Author')

        {   

            redirect('dashboard');

        }

	    $this->load->model('user_model');

	    $this->load->model('book_model');

        $this->load->model('activitylog_model');

        $this->load->model('quiz_model');

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

     * fetch users data from db 

     */

        //$users_role = array('role' =>'Author');

        $db_data = (array) $this->user_model->get_details_list(NULL, NULL);

        //var_dump($db_data); die();

        $data['fetch_data'] = $db_data;

        foreach ($data['fetch_data'] as $key => $value) {
            if($value->role != 'Admin') {
            $db_book_data[] = (array) $this->book_model->get_details_list(array('user_id' =>$value->id), NULL);
        }
    }
        
        $data['fetch_book_data'] = $db_book_data;

        //var_dump(count($data['fetch_book_data']['2'])); die();
    	$data['common_details'] = $this->commonView;

    	$this->load->view('users/all_users', $data);

    }



    public function reject($id=FALSE) {



        // for update data into books table

            $updateData = array(

                'status' => 'Inactive',

                'modified' => date('Y-m-d H:i:s'),

            );



            $whereData = array(

            'id' => $id,

            );



            $update =  $this->user_model->update($updateData, $whereData, NULL);



            $data['fetch_user'] = (array) $this->user_model->get_details_list($whereData, NULL);



        if($update) {

            $fetch_user = (array) $this->quiz_model->remove_rejectedUser($id);

            foreach ($fetch_user as $key => $value) {

                $array1 = Array($id);
                $array2 = explode(',', $value->assigned_users);
                $array3 = array_diff($array2, $array1);

                $output = implode(',', $array3);
                
                $updateQ = array(
                    'assigned_users' => $output,
                );

                $whereQ = array(
                    'QuizId' => $value->QuizId,
                );

                $update =  $this->quiz_model->update($updateQ, $whereQ, NULL);
            }
            //Activity log for reject registreation

            $ip_address = $this->input->ip_address();

            $activity_data = array(

                'user_id' => $data['fetch_user']['0']->id,

                'user_name' => $data['fetch_user']['0']->username,

                'activity' => 'Registration Rejected by Admin',

                'ip_address' => $ip_address,

                'created' => date('Y-m-d H:i:s'),

            );

            //var_dump($activity_data); die();

            $insert = $this->activitylog_model->insert($activity_data);

            $from_mail = AALTO_FROM_MAIL;

            $from_name = AALTO_FROM_NAME;

            $subject = "Registration Rejected";
            $content = "<p>Your registration request is rejected due to some failure.</p>";
            //var_dump($content); die();

            $mail = genericSendEmail($data['fetch_user']['0']->email, NULL, $from_mail, $from_name, $subject, $content);

            //var_dump($mail); die();

            redirect('all_users');

        }

    }



    public function approve($id=FALSE){



        // for update data into books table

        $updateData = array(

            'status' => 'Active',

            'modified' => date('Y-m-d H:i:s'),

        );



        $whereData = array(

            'id' => $id,

        );



        $update =  $this->user_model->update($updateData, $whereData, NULL);



        $data['fetch_user'] = (array) $this->user_model->get_details_list($whereData, NULL);

        

        if($update) {



            //Activity log for approve registreation

            $ip_address = $this->input->ip_address();

            $activity_data = array(

                'user_id' => $data['fetch_user']['0']->id,

                'user_name' => $data['fetch_user']['0']->username,

                'activity' => 'Registration Approved by Admin',

                'ip_address' => $ip_address,

                'created' => date('Y-m-d H:i:s'),

            );

            //var_dump($activity_data); die();

            $insert = $this->activitylog_model->insert($activity_data);



            $from_mail = AALTO_FROM_MAIL;

            $from_name = AALTO_FROM_NAME;

            $subject = "Registration Approved";

            if($data['fetch_user']['0']->role == 'Author') {
                $content = "<p>Your registration request is approved, click on below link for login to your account.</p>";
            } else {
                $content = "<p>Your registration request is approved.</p>";
            }

                $content .= "<p>Your Username : ".$data['fetch_user']['0']->username."</p>";
                $content .= "<p>Your Password : ".$data['fetch_user']['0']->visible_password."</p>";

            if($data['fetch_user']['0']->role == 'Author') {
                $link = base_url();
                $content .= "<a href='".$link."' ><b>click here to login</b></a>";
            }
            //var_dump($content); die();

            $mail = genericSendEmail($data['fetch_user']['0']->email, NULL, $from_mail, $from_name, $subject, $content);

            //var_dump($mail); die();

            redirect('all_users');

        }

    }



    public function login($id=FALSE) {

    //var_dump($id); die();

        //for Admin session

        $this->session->set_userdata('AdminLoggedIn',TRUE);

        $this->session->set_userdata('AdminId',$this->session->userdata('userId'));

        $this->session->set_userdata('AdminName',$this->session->userdata('userName'));

        $this->session->set_userdata('AdminRole',$this->session->userdata('Role'));



        $this->session->unset_userdata('userId');

        $this->session->unset_userdata('userName');

        $this->session->unset_userdata('Role');





        $this->session->set_userdata('userId',$id);

        $whereData = array(

            'id' => $id,

        );

        $data['fetch_user'] = (array) $this->user_model->get_details_list($whereData, NULL);



        $this->session->set_userdata('userName',$data['fetch_user']['0']->username);

        $this->session->set_userdata('Role',$data['fetch_user']['0']->role);

        //for book session
        $u_id = array('user_id' =>$this->session->userdata('userId'));

        $data['fetch_data_book'] = (array) $this->book_model->get_details_list($u_id, NULL);

        $this->session->set_userdata('Book_Id',$data['fetch_data_book']['0']->id);

        $this->session->set_userdata('Book_Title',$data['fetch_data_book']['0']->title);

            //for user specific folder creation

            $path = "./uploads/".$id;

            if(!is_dir($path)) //create the folder if it's not already exists
            {
                mkdir($path,0777,TRUE);
            }

            //Activity log for admin login into users account

            $ip_address = $this->input->ip_address();

            $activity_data = array(

                'user_id' => $this->session->userdata('userId'),

                'user_name' => $this->session->userdata('userName'),

                'activity' => 'Admin Login to your account',

                'ip_address' => $ip_address,

                'created' => date('Y-m-d H:i:s'),

            );

            //var_dump($activity_data); die();

            $insert = $this->activitylog_model->insert($activity_data);



        redirect('dashboard');

    }

}

?>