<?php
class Questions extends CI_Controller {
	var $commonView;

	public function __construct(){
	    parent::__construct();

        if( ! $this->session->userdata('isUserLoggedIn') ) {
            redirect('login');
        }
        
	    $this->load->model('user_model');
        $this->load->model('quiz_model');
        $this->load->model('queans_model');
        $this->load->model('activitylog_model');

        $u_id = array('user_id' =>$this->session->userdata('userId'));
	    $data['user'] = (array) $this->user_model->get_details(array('id'=>$this->session->userdata('userId')), NULL);

		$this->commonView  = Array(
			'user' 	  => $data['user'],
			'topbar'  => $this->load->view('topbar', $data, TRUE),
	        'sidebar' => $this->load->view('sidebar', $data, TRUE),
	        'footer'  => $this->load->view('footer', '', TRUE),
        );

    }



    public function index() {
    	$data = array();
		$db_data = array();

        if($this->input->post('QueSubmit')) {

            if($this->input->post('id') == 0) {

               if ($this->input->post('question_type') == 1) {
                    $answer = implode("@#@",$this->input->post('answer'));
                    $option = strip_tags($this->input->post('option_1')).'@#@'.strip_tags($this->input->post('option_2')).'@#@'.strip_tags($this->input->post('option_3')).'@#@'.strip_tags($this->input->post('option_4'));
                    $AddData = array(
                        'qType' => strip_tags($this->input->post('question_type')),
                        'Question' => strip_tags($this->input->post('question')),
                        'Answer' => $answer,
                        'options' => $option,
                        'QuizId' => strip_tags($this->input->post('quiz'))
                    );
                } else {
                    $AddData = array(
                        'qType' => strip_tags($this->input->post('question_type')),
                        'Question' => strip_tags($this->input->post('question')),
                        'Answer' => strip_tags($this->input->post('answer')),
                        'QuizId' => strip_tags($this->input->post('quiz'))
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
                    redirect('questions');
                }

            } else {

                if ($this->input->post('db_question_type') == 1) {
                    $answer = implode("@#@",$this->input->post('answer'));
                    $option = strip_tags($this->input->post('option_1')).'@#@'.strip_tags($this->input->post('option_2')).'@#@'.strip_tags($this->input->post('option_3')).'@#@'.strip_tags($this->input->post('option_4'));
                    $updateData = array(
                        'Question' => strip_tags($this->input->post('question')),
                        'Answer' => $answer,
                        'options' => $option,
                        'QuizId' => strip_tags($this->input->post('quiz'))
                    );
                } else {
                    $updateData = array(
                        'Question' => strip_tags($this->input->post('question')),
                        'Answer' => strip_tags($this->input->post('answer')),
                        'QuizId' => strip_tags($this->input->post('quiz'))
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
                    redirect('questions'); 
                }
            }
        }

	/*

     * fetch user specific data from db and for admin also

     */

        $db_data = (array) $this->queans_model->get_details_list(NULL, NULL);
        $db_quiz = (array) $this->quiz_model->get_details_list(NULL, NULL);
        //var_dump($db_data); die();

        $data['fetch_data'] = $db_data;
        $data['quiz_data'] = $db_quiz;
    	$data['common_details'] = $this->commonView;
    	$this->load->view('users/questions', $data);
    }


    public function delete_question($id=FALSE, $quizid=FALSE) {

        $whereData = array(
            'QAId' => $id,
        );

        $updateData = array(
            'status' => 'Deleted'
        );

        $check_que = (array) $this->queans_model->get_details($whereData, NULL);

        $update =  $this->queans_model->update($updateData, $whereData, NULL);

        if($update) {
            
            /*$path = '../Aalto/QueImg/'.$check_que['img'];
            unlink($path);*/
            //Activity log for profile update
            $activity = 'Question Deleted';
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
            $this->session->set_flashdata('success', 'Question Deleted successfully');
            redirect('questionpool'.$quizid);
        }
    }

    function get_view_ajax() {
        $where_id = array(
            'QAId' => $_POST['questionId'],
        );
        $que_data = (array) $this->queans_model->get_details($where_id, NULL);
        //var_dump($que_data);
        $data['question_data'] = $que_data;
        $data['question_type'] = $_POST['type'];
        if ($_POST['type'] == '') {
            echo "";
        } else if ($_POST['type'] == 1) {
            $this->load->view('quiz/multiple_choice', $data);
        } else if ($_POST['type'] == 2) {
            $this->load->view('quiz/true_false', $data);
        } else {
            $this->load->view('quiz/all_question', $data);
        }
    }

}
?>