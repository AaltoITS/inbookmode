<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**

 * User Management class created by CodexWorld

 */

class Reset_password extends CI_Controller {

    

    function __construct() {

        parent::__construct();

        $this->load->model('user_model');

        $this->load->model('activitylog_model'); 

    }

    

    /*

     * User registration

     */

    public function index($rs=FALSE){

        if(!empty($this->session->userdata('error_msg')) || !empty($this->session->userdata('success_msg'))) {
            $this->session->unset_userdata('error_msg');
            $this->session->unset_userdata('success_msg');
            $this->session->sess_destroy();
        }

        $data = array();

        //var_dump($rs);

        if($this->input->post('resetSubmit')){

            if(!empty($rs)) {

            $this->form_validation->set_rules('password', 'password', 'required');

            $this->form_validation->set_rules('conf_password', 'confirm password', 'required|matches[password]');

            if($this->form_validation->run() == true){

                //validate token

                $Token = array('reset'=>$rs);

                $countToken =  $this->user_model->get_details($Token, NULL);

                if(count($countToken) > 0){

                    $whereToken = array('reset' => $rs);

                    $check = (array) $this->user_model->get_details(NULL, $whereToken);

                    //Activity log for password changed

                    $ip_address = $this->input->ip_address();

                    $activity_data = array(

                        'user_id' => $check['id'],

                        'user_name' => $check['username'],

                        'activity' => 'Password Changed',

                        'ip_address' => $ip_address,

                        'created' => date('Y-m-d H:i:s'),

                    );

                    //var_dump($activity_data); die();

                    $insert = $this->activitylog_model->insert($activity_data);

                    $resetData = array(

                    'password' => md5($this->input->post('password')),

                    'visible_password' => $this->input->post('password'),

                    'modified' => date('Y-m-d H:i:s'),

                    'reset' =>'',

                    );

                    $update =  $this->user_model->update($resetData, $whereToken, NULL);

                    if($update) {
                    //mail this to user

                    $from_mail = AALTO_FROM_MAIL;

                    $from_name = AALTO_FROM_NAME;

                    $subject = "Password Updated";
                if($check['username'] == 'Author') {
                    $message = "<p>Your password reset successful, click on below link for login to your account.</p>";
                } else {
                    $message = "<p>Your password reset successful.</p>";
                }
                    $message .= "<p>Your Username : ".$check['username']."</p>";

                    $message .= "<p>Your current password is : ".$this->input->post('password')." </p>";
                if($check['username'] == 'Author') {
                    $link = base_url();
                    $message .= "<a href='".$link."' ><b>click here to login</b></a>";
                }
                    //var_dump($message); die();

                    $mail = genericSendEmail($check['email'], NULL, $from_mail, $from_name, $subject, $message);

                    //var_dump($mail); die();

                }
                    $this->session->set_userdata('success_msg', 'password reset successful, login to your account.');

                    redirect('login');

                    } else {

                    $this->session->set_userdata('error_msg', 'Invalid Request.');

                    $data['error_msg'] = 'Invalid Request.';

                }

            }

        } else {
            $this->session->set_userdata('error_msg', 'Invalid Request.');
            $data['error_msg'] = 'Invalid Request.';
        }

        }

        //load the view

        $this->load->view('users/reset_password', $data);

    }

}
?>