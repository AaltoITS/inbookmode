<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**

 * User Management class

 */

class Users extends CI_Controller {

    

    function __construct() {

        parent::__construct();

        $this->load->model('user_model');

        $this->load->model('activitylog_model');

        $this->load->model('domain_model'); 

    }

    

    /*

     * User login

     */

    public function login(){

        if(!empty($this->session->userdata('error_msg')) || !empty($this->session->userdata('success_msg'))) {
            $this->session->unset_userdata('error_msg');
            $this->session->unset_userdata('success_msg');
            $this->session->sess_destroy();
        }

        $data = array();

        if($this->session->userdata('success_msg')){

            $data['success_msg'] = $this->session->userdata('success_msg');

            $this->session->unset_userdata('success_msg');

        }

        if($this->session->userdata('error_msg')){

            $data['error_msg'] = $this->session->userdata('error_msg');

            $this->session->unset_userdata('error_msg');

        }

        if($this->input->post('loginSubmit')){

            $this->form_validation->set_rules('username', 'Username', 'required');

            $this->form_validation->set_rules('password', 'password', 'required');

            if ($this->form_validation->run() == true) {

                

                $con = array(

                    'username'=> $this->input->post('username'),

                    'password' => md5($this->input->post('password')),

                    //'status' => 'Active',

                );

                $checkLogin = (array) $this->user_model->get_details(NULL, $con);

                //var_dump($checkLogin); die();
                
                    if($checkLogin){

                        if($checkLogin['status'] == 'Active') {

                            if($checkLogin['role'] != 'Reader') {

                                $this->session->set_userdata('isUserLoggedIn',TRUE);

                                $this->session->set_userdata('userId',$checkLogin['id']);

                                $this->session->set_userdata('userName',$checkLogin['username']);

                                $this->session->set_userdata('Role',$checkLogin['role']);

                                //for user specific folder creation

                                $path = "./uploads/".$checkLogin['id'];

                                if(!is_dir($path)) //create the folder if it's not already exists

                                {

                                    mkdir($path,0777,TRUE);

                                }

                                //Activity log for login

                                $ip_address = $this->input->ip_address();

                                $activity_data = array(

                                'user_id' => $this->session->userdata('userId'),

                                'user_name' => $this->session->userdata('userName'),

                                'activity' => 'Log In',

                                'ip_address' => $ip_address,

                                'created' => date('Y-m-d H:i:s'),

                            );

                            //var_dump($activity_data); die();

                            $insert = $this->activitylog_model->insert($activity_data);

                            redirect('dashboard');

                        } else {
                            $data['error_msg'] = 'You do not have a permission to log in.';
                        }
                    } else {
                        $data['error_msg'] = 'Your account is inactive.';
                    }
                } else {
                    $data['error_msg'] = 'Invalid username or password, please try again.';
                }
            }
        }

        //load the view
        $this->load->view('users/login', $data);

    }

    

    /*

     * User registration

     */

    public function registration(){

        if(!empty($this->session->userdata('error_msg')) || !empty($this->session->userdata('success_msg'))) {
            $this->session->unset_userdata('error_msg');
            $this->session->unset_userdata('success_msg');
            $this->session->sess_destroy();
        }

        $data = array();

        $userData = array();

        if($this->input->post('regisSubmit')){

            $this->form_validation->set_rules('username', 'Username', 'required|callback_user_check');

            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_email_check');

            $this->form_validation->set_rules('password', 'password', 'required');

            $this->form_validation->set_rules('conf_password', 'confirm password', 'required|matches[password]');



            $email = strip_tags($this->input->post('email'));

            $explode = explode("@", $email);

            $dom = explode(".", $explode[1]);

            $domain = $dom[0];



            $dom_chk = array('domain'=>$domain);

            $checkdomain =  $this->domain_model->get_details($dom_chk, NULL);

            

                if(count($checkdomain) == 0){

                    $data['error_msg'] = 'Registration fails, Invalid Domain.';

                } else {

                $userData = array(

                    'username' => strip_tags($this->input->post('username')),

                    'email' => strip_tags($this->input->post('email')),

                    'password' => md5($this->input->post('password')),

                    'visible_password' => $this->input->post('password'),

                    'profile_pic' => 'default-pic.png',

                    'created' => date('Y-m-d H:i:s'),

                );



                if($this->form_validation->run() == true){

                    $insert = $this->user_model->insert($userData);

                    if($insert){

                        $from_mail = AALTO_FROM_MAIL;

                        $from_name = AALTO_FROM_NAME;

                        $subject = "Registration";
                        $content = "<p>Your registration was successful. you can login after request has been approved.</p>";
                        //var_dump($content); die();

                        $mail = genericSendEmail($this->input->post('email'), NULL, $from_mail, $from_name, $subject, $content);

                        //var_dump($mail); die();

                        $this->session->set_userdata('success_msg', 'Your registration was successful. you can login after request has been approved.');

                        redirect('login');

                    }else{

                        $data['error_msg'] = 'Some problems occured, please try again.';

                    }

                }

            }

        }

        $data['user'] = $userData;

        //load the view

        $this->load->view('users/registration', $data);

    }

    

    /*

     * User logout

     */

    public function logout(){



    if( ! $this->session->userdata('AdminLoggedIn') ) {

        if(!empty($this->session->userdata('userId'))) {

        //Activity log for logout

        $ip_address = $this->input->ip_address();

        $activity_data = array(

            'user_id' => $this->session->userdata('userId'),

            'user_name' => $this->session->userdata('userName'),

            'activity' => 'Log Out',

            'ip_address' => $ip_address,

            'created' => date('Y-m-d H:i:s'),

        );

        //var_dump($activity_data); die();

        $insert = $this->activitylog_model->insert($activity_data);

    }    

            $this->session->unset_userdata('isUserLoggedIn');

            $this->session->unset_userdata('userId');

            $this->session->unset_userdata('userName');

            $this->session->unset_userdata('Role');

            $this->session->sess_destroy();

            redirect('login');

        } else {

        if(!empty($this->session->userdata('userId'))) {

        //Activity log for logout

        $ip_address = $this->input->ip_address();

        $activity_data = array(

            'user_id' => $this->session->userdata('userId'),

            'user_name' => $this->session->userdata('userName'),

            'activity' => 'Admin logout from your account',

            'ip_address' => $ip_address,

            'created' => date('Y-m-d H:i:s'),

        );

        //var_dump($activity_data); die();

        $insert = $this->activitylog_model->insert($activity_data);

        }    

            $this->session->set_userdata('userId', $this->session->userdata('AdminId'));

            $this->session->set_userdata('userName', $this->session->userdata('AdminName'));

            $this->session->set_userdata('Role', $this->session->userdata('AdminRole'));





            $this->session->unset_userdata('AdminLoggedIn');

            redirect('dashboard');

        }

    }

    

    /*

     * Existing email check during validation

     */

    public function email_check($str){

        $con = array('email'=>$str);

        $checkEmail =  $this->user_model->get_details($con, NULL);

        if(count($checkEmail) > 0){

            $this->form_validation->set_message('email_check', 'The given email already exists.');

            return FALSE;

        } else {

            return TRUE;

        }

    }



    /*

     * Forgot password

     */

    public function forgot(){

        if(!empty($this->session->userdata('error_msg')) || !empty($this->session->userdata('success_msg'))) {
            $this->session->unset_userdata('error_msg');
            $this->session->unset_userdata('success_msg');
            $this->session->sess_destroy();
        }

        if($this->input->post('resetSubmit')){

            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_email_check_forgot');

        }



         if($this->form_validation->run() == true){



    /*

     * random string update in users table

     */

            $this->load->helper('string', 6);

            $rs= random_string('alnum', 12);



            $updateReset = array(

                'reset' => $rs,

                'modified' => date('Y-m-d H:i:s'),

                );



            $whereEmail = array(

            'email' => $this->input->post('email'),

            );

            $update =  $this->user_model->update($updateReset, $whereEmail, NULL);



                //mail this to user

                if($update){



                $whereEmail = array(

                    'email' => $this->input->post('email'),

                    );

                $check = (array) $this->user_model->get_details(NULL, $whereEmail);



                    //Activity log for password reset request

                    $ip_address = $this->input->ip_address();

                    $activity_data = array(

                        'user_id' => $check['id'],

                        'user_name' => $check['username'],

                        'activity' => 'Password Reset Request',

                        'ip_address' => $ip_address,

                        'created' => date('Y-m-d H:i:s'),

                    );

                    //var_dump($activity_data); die();

                    $insert = $this->activitylog_model->insert($activity_data);



                    $from_mail = AALTO_FROM_MAIL;

                    $from_name = AALTO_FROM_NAME;

                    $subject = "Forgot Password";

                    $message = "<p>Please click on below link for reset password.</p>";

                    $message .= "<p>Your Username : ".$check['username']."</p>";
                    $link = base_url();
                    $message .= "<a href='".$link."reset_password/index/".$rs."' ><b>click here to reset password</b></a>";

                    //var_dump($message); die();

                    $mail = genericSendEmail($this->input->post('email'), NULL, $from_mail, $from_name, $subject, $message);

                    //var_dump($mail); die();

                }

            $this->session->set_userdata('success_msg', 'You will receive a link to create a new password via email.');

                    redirect('login');

            }else {

            

            }

        //load the view

        $this->load->view('users/forgot');

    }



     /*

     * Existing email check during validation

     */

    public function email_check_forgot($str){

        $con2 = array('email'=>$str);

        $checkEmail2 =  $this->user_model->get_details($con2, NULL);

        if(count($checkEmail2) > 0){

           return TRUE;

        } else {

            $this->form_validation->set_message('email_check_forgot', 'The given email is invalid, please enter valid email.');

            return FALSE;

        }

    }


    /*

     * Existing username check during validation

     */

    public function user_check($str){

        $user = array('username'=>$str);

        $checkUser =  $this->user_model->get_details($user, NULL);

        if(count($checkUser) > 0){

            $this->form_validation->set_message('user_check', 'The given username already exists.');

            return FALSE;

        } else {

            return TRUE;

        }

    }


}

?>