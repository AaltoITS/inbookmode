<?php
class Questionpool extends CI_Controller {

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
        $this->load->model('quiz_model');
        $this->load->model('queans_model');
        $this->load->model('activitylog_model');
        $this->load->model('book_model');

        // for get user specific books from database

        $u_id = array('user_id' => $this->session->userdata('userId'));

        $data['fetch_data'] = (array) $this->book_model->get_details_list($u_id, NULL);

        $data['user'] = (array) $this->user_model->get_details(array('id'=>$this->session->userdata('userId')), NULL);

        $this->commonView  = Array(
            'user'    => $data['user'],
            'topbar'  => $this->load->view('topbar', $data, TRUE),
            'sidebar' => $this->load->view('sidebar', $data, TRUE),
            'footer'  => $this->load->view('footer', '', TRUE),
        );
    }


    public function index() {
        if($this->input->post('QueSubmit')) {

            if($this->input->post('id') == 0) {

                $check_que = array(
                    'Question' =>$this->input->post('question'),
                    'AuthorId' =>$this->session->userdata('userId'),
                    'status' =>'Active',
                );
                $db_que = (array) $this->queans_model->get_details_list($check_que, NULL);

                if(count($db_que) > 0) {
                    $this->session->set_flashdata('error_msg', 'Question Already Exists');
                    redirect('questionpool');
                }

               if ($this->input->post('question_type') == 1) {
                    $answer = implode("@#@",$this->input->post('answer'));
                    $option = strip_tags($this->input->post('option_1')).'@#@'.strip_tags($this->input->post('option_2')).'@#@'.strip_tags($this->input->post('option_3')).'@#@'.strip_tags($this->input->post('option_4'));
                    $AddData = array(
                        'qType' => strip_tags($this->input->post('question_type')),
                        'Question' => strip_tags($this->input->post('question')),
                        'Marks' => strip_tags($this->input->post('marks')),
                        'Answer' => $answer,
                        'options' => $option,
                        'AuthorId' => $this->session->userdata('userId')
                    );
                } else if(($this->input->post('question_type') == 3) || ($this->input->post('question_type') == 6)) {
                        $config['upload_path']   = './QueImg/';
                        $config['allowed_types'] = 'gif|jpg|png';
                        $config['max_size']      = 1000;
                        $config['max_width']     = 768;
                        $config['max_height']    = 1024;

                        $this->load->library('upload', $config);

                        if ( ! $this->upload->do_upload('que_image')) {
                            if (empty($_FILES['que_image']['name'])) {
                                $file_name = '';
                            } else {
                                $this->session->set_flashdata('error_msg', $this->upload->display_errors());
                                redirect('questionpool');
                            }
                        } else {
                            $upload_data = $this->upload->data(); 
                            $file_name =   $upload_data['file_name'];
                        }

                        $AddData = array(
                            'qType' => strip_tags($this->input->post('question_type')),
                            'Question' => strip_tags($this->input->post('question')),
                            'Marks' => strip_tags($this->input->post('marks')),
                            'Answer' => strip_tags($this->input->post('answer')),
                            'img' => $file_name,
                            'AuthorId' => $this->session->userdata('userId')
                        );

                    } else {

                        if($this->input->post('question_type') == 5) {
                            $mystring = $this->input->post('question');
                            $findme   = '____';
                            $pos = strpos($mystring, $findme);
                            if($pos === false) {
                                $this->session->set_flashdata('error_msg', 'At least one fill in the blank needed.');
                                redirect('questionpool');
                            } 
                        }

                        $AddData = array(
                            'qType' => strip_tags($this->input->post('question_type')),
                            'Question' => strip_tags($this->input->post('question')),
                            'Marks' => strip_tags($this->input->post('marks')),
                            'Answer' => strip_tags($this->input->post('answer')),
                            //'QuizId' => strip_tags($this->input->post('db_quiz')),
                            'AuthorId' => $this->session->userdata('userId')
                        );
                    }

                $insert = $this->queans_model->insert($AddData);

                if($insert) {

                    //Activity log for profile update
                    $activity = 'Question Added';
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

                    $this->session->set_flashdata('success', 'Question Added successfully');
                    redirect('questionpool');
                }

            } else {

                if($this->input->post('question') != $this->input->post('db_question')) {
                    $check_que = array(
                        'Question' =>$this->input->post('question'),
                        'AuthorId' =>$this->session->userdata('userId'),
                        'status' =>'Active',
                    );
                    $db_que = (array) $this->queans_model->get_details_list($check_que, NULL);

                    if(count($db_que) > 0) {
                        $this->session->set_flashdata('error_msg', 'Question Already Exists');
                        redirect('questionpool');
                    }
                }

                if ($this->input->post('db_question_type') == 1) {
                    $answer = implode("@#@",$this->input->post('answer'));
                    $option = strip_tags($this->input->post('option_1')).'@#@'.strip_tags($this->input->post('option_2')).'@#@'.strip_tags($this->input->post('option_3')).'@#@'.strip_tags($this->input->post('option_4'));
                    $updateData = array(
                        'Question' => strip_tags($this->input->post('question')),
                        'Marks' => strip_tags($this->input->post('marks')),
                        'Answer' => $answer,
                        'options' => $option,
                    );
                } else {

                    $config['upload_path']   = './QueImg/'; 
                    $config['allowed_types'] = 'gif|jpg|png';
                    $config['max_size']      = 1000;
                    $config['max_width']     = 768;
                    $config['max_height']    = 1024;

                    $this->load->library('upload', $config);

                    if ( ! $this->upload->do_upload('que_image')) {
                        if (empty($_FILES['que_image']['name'])) {
                            $file_name =   strip_tags($this->input->post('db_que_image'));
                        } else {
                            $this->session->set_flashdata('error_msg', $this->upload->display_errors());
                            redirect('questionpool');
                        }
                    } else {
                        $upload_data = $this->upload->data();
                        $file_name =   $upload_data['file_name'];

                        $path = '../Aalto/QueImg/'.$this->input->post('db_que_image');
                        unlink($path);
                    }
                    
                    $updateData = array(
                        'Question' => strip_tags($this->input->post('question')),
                        'Marks' => strip_tags($this->input->post('marks')),
                        'Answer' => strip_tags($this->input->post('answer')),
                        'img' => $file_name,
                    );
                }

                // for update data into table
                $whereData = array(
                    'QAId' => $this->input->post('id'),
                );

                $update =  $this->queans_model->update($updateData, $whereData, NULL);

                 if($update) {
                    //Activity log for profile update

                    $activity = 'Question Updated';
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

                    $this->session->set_flashdata('success', 'Question Updated successfully');
                    redirect('questionpool');
                }
            }
        }
        //to show in table

        $db_joinque = (array) $this->queans_model->get_pool_list(NULL, NULL);

        $data['fetch_allQue'] = $db_joinque;
        $data['common_details'] = $this->commonView;
        $this->load->view('users/questionpool', $data);
    }


}
?>