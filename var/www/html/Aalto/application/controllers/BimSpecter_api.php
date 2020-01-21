<?php
class BimSpecter_api extends CI_Controller {

	public function __construct() {
        parent::__construct();

        $this->load->model('user_model');
	    $this->load->model('tutor_model');
        $this->load->model('quiz_model');
        $this->load->model('Queans_model');
        $this->load->model('learner_model');
        $this->load->model('record_model');
        $this->load->model('activity_model');
    }

    /*
     * tutor
     */
    public function tutor() {
        
        echo "tutor function goes here";    
    }


    /*
     * quiz
     */
    public function quiz() {
        $data = array();

        $tutor_chk = array('TutorId' => $this->input->post('tutor_id'));
        $checkQuiz = (array) $this->quiz_model->get_details_list(NULL, $tutor_chk);

       if(count($checkQuiz) > 0) {
           $data['Quiz'] = $checkQuiz;
        } else {
           $data['msg'] = 'fail';
        }
        //echo $data['msg'];
        echo json_encode($data);  
    }


    /*
     * q&a
     */
    public function queans() {
        $data = array();

        $quiz_chk = array('QuizId' => $this->input->post('quiz_id'));
        $checkQueAns = (array) $this->Queans_model->get_details_list(NULL, $quiz_chk);

       if(count($checkQueAns) > 0) {
           $data['Q&A'] = $checkQueAns;
        } else {
           $data['msg'] = 'fail';
        }
        //echo $data['msg'];
        echo json_encode($data);     
    }


    /*
     * learner
     */
    public function learner() {
        $data = array();

        $email_chk = array('Email'=>$this->input->post('email'));
        $checkemail =  $this->learner_model->get_details($email_chk, NULL);

        $user_chk = array('email' => $this->input->post('email'));
        $checkUser = (array) $this->user_model->get_details(NULL, $user_chk);

        if((count($checkemail) > 0) || (count($checkUser) > 0)) {
                $data['msg'] = 'email already exists';
        } else {

            $learnerData = array(
                'FirstName' => strip_tags($this->input->post('firstname')),
                'LastName' => strip_tags($this->input->post('lastname')),
                'Password' => md5($this->input->post('password')),
                'Email' => strip_tags($this->input->post('email')),
            );

            $insert = $this->learner_model->insert($learnerData);

            if($insert) {
                $data['msg'] = 'success';
                $data['id'] = "".$insert."";
            } else {
                $data['msg'] = 'fail';
            }
        }
        //echo $data['msg'];
        echo json_encode($data);    
    }


    /*
     * record
     */
    public function record() {
        $data = array();

        $recordData = array(
                'Date' => strip_tags($this->input->post('Date')),
                'Grade' => strip_tags($this->input->post('Grade')),
                'LearnerId' => strip_tags($this->input->post('LearnerId')),
                'QuizId' => strip_tags($this->input->post('QuizId'))
            );

        $insert = $this->record_model->insert($recordData);

        if($insert) {
            $data['msg'] = 'success';
        } else {
            $data['msg'] = 'fail';
        }
        //echo $data['msg'];
        echo json_encode($data);    
    }


    /*
     * activity log
     */
    public function activity() {
        $data = array();

        $activityData = array(
                'LearnerAnswer' => strip_tags($this->input->post('LearnerAnswer')),
                'QuestionTime' => strip_tags($this->input->post('QuestionTime')),
                'AnswerTime' => strip_tags($this->input->post('AnswerTime')),
                'QAId' => strip_tags($this->input->post('QAId'))
            );

        $insert = $this->activity_model->insert($activityData);

        if($insert) {
            $data['msg'] = 'success';
        } else {
            $data['msg'] = 'fail';
        }
        //echo $data['msg'];
        echo json_encode($data);    
    }


    /*
     * User login
     */
    public function login() {
        $data = array();

        $learner_chk = array(
            'Email' => $this->input->post('email'),
            'Password' => md5($this->input->post('password'))
        );
        $checkLearner = (array) $this->learner_model->get_details(NULL, $learner_chk);

        $user_chk = array(
            'email' => $this->input->post('email'),
            'password' => md5($this->input->post('password'))
        );
        $checkUser = (array) $this->user_model->get_details(NULL, $user_chk);

        if((count($checkLearner) > 0) || (count($checkUser) > 0)) {
            if(!empty($checkLearner)) {
                $id = $checkLearner['LearnerId'];
            } else {
                $id = $checkUser['id'];
            }
            $data['msg'] = 'success';
            $data['id'] = $id;
        } else {
            $data['msg'] = 'incorrect email or password';
        }
        //echo $data['msg'];
        echo json_encode($data);
    }

}

?>