<?php



class Profile extends CI_Controller {



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

        $this->load->model('domain_model');



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

        $u_id = array('id' => $this->commonView['user']['id']);

        $db_data = (array) $this->user_model->get_details_list($u_id, NULL);



        if($this->input->post('profileSubmit')){

            $error = '0';

            $update = '0';

            //for upload cover image

            $config['upload_path']   = './uploads/'; 

            $config['allowed_types'] = 'jpg|png'; 

            $config['max_size']      = 1000; 

            $config['max_width']     = 768; 

            $config['max_height']    = 1024;



            $this->load->library('upload', $config);

            if ( ! $this->upload->do_upload('profile_pic')) {

                if (empty($_FILES['profile_pic']['name'])) {
                    $file_name =   strip_tags($this->input->post('db_profile_pic'));
                } else {
                    $this->session->set_flashdata('error_msg', $this->upload->display_errors());
                    redirect('profile');
                } 
                
            } else {

                $upload_data = $this->upload->data(); 
                $file_name =   $upload_data['file_name'];

                if($this->input->post('db_profile_pic') != 'default-pic.png') {
                    $path = '../Aalto/uploads/'.$this->input->post('db_profile_pic');
                    unlink($path);
                }

            }

            //for user validation

            if(($this->input->post('username')) != ($this->input->post('db_username'))) {

                $user_check = array('username'=> strip_tags($this->input->post('username')));

                    $checkUser =  $this->user_model->get_details($user_check, NULL);

                    if(count($checkUser) > 0){

                        $data['username_error'] = 'The given username already exists.';

                        $error = '1';

                    } else {

                        $username = strip_tags($this->input->post('username'));

                    }

            } else {

                $username = $this->input->post('db_username');
            }

            

            //for email validation

            if(($this->input->post('email')) != ($this->input->post('db_email')))

            {

                $email = strip_tags($this->input->post('email'));

                $explode = explode("@", $email);

                $dom = explode(".", $explode[1]);

                $domain = $dom[0];



                $dom_chk = array('domain'=>$domain);

                $checkdomain =  $this->domain_model->get_details($dom_chk, NULL);

            

                    if(count($checkdomain) == 0){

                        $data['email_error'] = 'Invalid Domain.';

                        $error = '1';

                    } else {



                    $email_check = array('email'=> strip_tags($this->input->post('email')));

                    $checkEmail =  $this->user_model->get_details($email_check, NULL);

                    if(count($checkEmail) > 0){

                      $data['email_error'] = 'The given email already exists.';

                      $error = '1';

                    }

                    else

                    {

                        $email = strip_tags($this->input->post('email'));

                    }

                }

            }

            else

            {

                $email = strip_tags($this->input->post('db_email'));

            }

            

            //for password validation

            if(!empty($this->input->post('password')) && !empty($this->input->post('conf_password')))

            {

                if(($this->input->post('password')) != ($this->input->post('conf_password')))

                {

                    $data['password_error'] = 'Password missmatch';

                    $error = '1';

                }

                else

                {

                    $password = md5($this->input->post('password'));

                    $visible_password = $this->input->post('password');

                }

            }

            else

            {

                $password = $this->input->post('db_password');

                $visible_password = $this->input->post('db_visible_password');

            }



//var_dump($visible_password); die();

            if($error == '0') {

                // for update data into books table

                $updateData = array(

                    'username' => $username,

                    'email' => $email,

                    'password' => $password,

                    'visible_password' => $visible_password,

                    'profile_pic' => $file_name,

                    'modified' => date('Y-m-d H:i:s'),

                );



                $whereData = array(

                'id' => $this->commonView['user']['id'],

                );



                $update =  $this->user_model->update($updateData, $whereData, NULL);

            }

            if($update) {

                //Activity log for profile update

                if( ! $this->session->userdata('AdminLoggedIn') )

                {

                    $activity = 'Profile Updated';

                } else {

                    $activity = 'Profile Updated by Admin';

                }

                $ip_address = $this->input->ip_address();

                $activity_data = array(

                    'user_id' => $this->session->userdata('userId'),

                    'user_name' => $username,

                    'activity' => $activity,

                    'ip_address' => $ip_address,

                    'created' => date('Y-m-d H:i:s'),

                );

                //var_dump($activity_data); die();

                $insert = $this->activitylog_model->insert($activity_data);

                if(($this->input->post('username') != $this->input->post('db_username')) || !empty($this->input->post('password'))) {
                    //mail this to user

                    $from_mail = AALTO_FROM_MAIL;

                    $from_name = AALTO_FROM_NAME;

                    $subject = "Profile Updated";

                    $message = "<p>Your Profile updated, click on below link for login to your account.</p>";

                    $message .= "<p>Your current Username : ".$username."</p>";

                    $message .= "<p>Your current Password is : ".$visible_password." </p>";
                    $link = base_url();
                    $message .= "<a href='".$link."' ><b>click here to login</b></a>";

                    //var_dump($message); die();

                    $mail = genericSendEmail($email, NULL, $from_mail, $from_name, $subject, $message);

                    //var_dump($mail); die();
                }

                $this->session->set_flashdata('success', 'Profile updated successfully');

                $this->session->set_userdata('userName',$username);

                redirect('profile');

            }

        }



        //var_dump($db_data); die();

        $data['user_data'] = $db_data;

    	$data['common_details'] = $this->commonView;

    	$this->load->view('users/profile', $data);

    }



}

?>