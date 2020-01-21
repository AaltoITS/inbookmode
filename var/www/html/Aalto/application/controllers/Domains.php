<?php



class Domains extends CI_Controller {



	var $commonView;

	public function __construct(){



	    parent::__construct();



        if( ! $this->session->userdata('isUserLoggedIn') )

        {

        	redirect('login');

        }

        if($this->session->userdata('Role') == 'Author')

        {   

            redirect('dashboard');

        }

	    $this->load->model('user_model');

	    $this->load->model('book_model');

        $this->load->model('domain_model');

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





if($this->input->post('adddomainSubmit')){

            // for get user specific books from database

            if($this->input->post('id') == 0) {

            $check_domain = array('domain' =>$this->input->post('domain'));

            $data['fetch_data'] = (array) $this->domain_model->get_details_list($check_domain, NULL);



            if(count($data['fetch_data']) > 0) {

                $this->session->set_flashdata('error_msg', 'Domain Already Exists');

                redirect('domains');

            }

                // for insert data into books table

                $AddData = array(

                    'domain' => strip_tags($this->input->post('domain')),

                    'created' => date('Y-m-d H:i:s'),

                    );

                //var_dump($AddpartData); die();

                $insert = $this->domain_model->insert($AddData);

                if($insert) {



                    //Activity log for profile update

                    $activity = 'Domain Added';

                    

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



                    $this->session->set_flashdata('success', 'Domain Added successfully');

                    redirect('domains'); 

                }

            } else {

            if($this->input->post('domain') != $this->input->post('db_domain')) {

                $check_domain = array('domain' =>$this->input->post('domain'));

                $data['fetch_data'] = (array) $this->domain_model->get_details_list($check_domain, NULL);



                if(count($data['fetch_data']) > 0) {

                $this->session->set_flashdata('error_msg', 'Domain Already Exists');

                redirect('domains');

            }

            } 

                // for update data into books table

                $updateData = array(

                    'domain' => strip_tags($this->input->post('domain')),

                    'modified' => date('Y-m-d H:i:s'),

                );



                $whereData = array(

                'id' => $this->input->post('id'),

                );



                $update =  $this->domain_model->update($updateData, $whereData, NULL);

                 if($update) {



                    //Activity log for profile update

                    $activity = 'Domain Updated';

                    

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



                    $this->session->set_flashdata('success', 'Domain Updated successfully');

                   redirect('domains'); 

                }

            }

        }

	/*

     * fetch user specific data from db and for admin also

     */

        $db_data = (array) $this->domain_model->get_details_list(NULL, NULL);



        //var_dump($db_data); die();

        $data['fetch_data'] = $db_data;

    	$data['common_details'] = $this->commonView;

    	$this->load->view('users/domains', $data);

    }



    public function delete_domains($id=FALSE){



        $whereData = array(

            'id' => $id,

            );



        $delete =  $this->domain_model->delete($whereData, NULL);

        if($delete) {



            //Activity log for profile update

            $activity = 'Domain Deleted';

            

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



            redirect('domains');

        }

    }



}

?>