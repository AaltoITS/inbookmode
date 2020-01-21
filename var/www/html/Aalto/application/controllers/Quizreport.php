<?php
class Quizreport extends CI_Controller {

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
        $this->load->model('quizRecord_model');

        // for get user specific books from database

        $u_id = array('user_id' => $this->session->userdata('userId'));

        $data['fetch_data'] = (array) $this->book_model->get_details_list($u_id, NULL);

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
        $db_marksSum_data = array();

	/*
     * fetch user specific data from db and for admin also
     */
        $Aid = array('AuthorId' => $this->session->userdata('userId'));
        $db_data = (array) $this->quiz_model->get_details_list($Aid, NULL);
        //var_dump($db_data); die();
        $data['fetch_data'] = $db_data;
        $db_attempt_data = '';
        foreach ($data['fetch_data'] as $key => $value) {
            $db_attempt_data[] = (array) $this->quizRecord_model->get_attempted_data($value->QuizId);

            //sum of marks per quiz
            $FileName = explode(",", $value->questions);
            $db_marksSum_data[] = (array) $this->queans_model->get_marks_sum($FileName);
        }
        //var_dump($db_attempt_data); die();
        $data['count_attempt'] = $db_attempt_data;
        $data['marks_sum'] = $db_marksSum_data;
    	$data['common_details'] = $this->commonView;
    	$this->load->view('users/quizreport', $data);
    }

    public function atmpt_user($quizid=FALSE) {
        $data = array();
        $db_obtmarksSum_data = array();

        $whereData = array(
            'QuizId' => $quizid,
        );

        $db_userdata = (array) $this->quizRecord_model->get_attempted_data($quizid);
        $db_attempt_user = array();

        foreach ($db_userdata as $key => $value) {
            $db_attempt_user[] = (array) $this->user_model->get_details_list(NULL, array('id' => $value->user_id), 'id,username,email,role,status');

            //sum of obtained marks per quiz
            $whereArr = array(
                'quiz_id' => $quizid,
                'user_id' => $value->user_id,
            );
        
            $db_obtmarksSum_data[] = (array) $this->quizRecord_model->get_obtainedmarks_sum($whereArr);
        }

        //to show in title
        $sel_quiz = (array) $this->quiz_model->get_details_list($whereData, NULL);

        $data['fetch_data'] = $db_attempt_user;
        $data['obtmarks_sum'] = $db_obtmarksSum_data;
        $data['sel_quiz'] = $sel_quiz;
        $data['common_details'] = $this->commonView;
        $this->load->view('users/attemptedUser', $data);
    }

    public function allQueAndAns($quizId=FALSE,$userId=FALSE) {

        $whereData = array(
            'quiz_id' => $quizId,
            'user_id' => $userId,
        );
        
        //attempted user
        $attempt_user = (array) $this->user_model->get_details_list(NULL, array('id' => $userId), 'username,email');

        //to show in table
        $db_data = (array) $this->quizRecord_model->getRecordData($whereData);

        //to show in title
        $sel_quiz = (array) $this->quiz_model->get_details_list(array('QuizId' => $quizId), NULL);
        //var_dump($sel_quiz); die();

        $data['sel_user'] = $attempt_user;
        $data['fetch_data'] = $db_data;
        $data['sel_quiz'] = $sel_quiz;
        $data['common_details'] = $this->commonView;
        $this->load->view('users/report_queans', $data);
    }

    public function save_marks() {

        $where_id = array(
            'Id' => $_POST['id'],
        );
        
        $updateData = array(
            'obtained_marks' => $_POST['obt_mark'],
        );

        $update =  $this->quizRecord_model->update($updateData, $where_id, NULL);

        /*if($update) {*/
            echo "marks given successfully";
        /*} else {
            echo "something went wrong, please try after sometime";
        }*/
    }


    function get_sentReport_user() {

        if(isset($_POST['userMapping']) && !empty($_POST['userMapping'])) {
            $forEmailCheck = $_POST['userMapping'];
            $users = implode(',', $_POST['userMapping']);
            
            $insert_data = array(
                'reportSent_users' => $users
            );

            $whereData = array(
                'QuizId' => $_POST['quiz_id'],
            );

            $dbUsers = (array) $this->quiz_model->get_details_list(NULL, $whereData, 'QuizUniqueId,Title,questions,reportSent_users');
            $usersArray = explode(',', $dbUsers[0]->reportSent_users);
            
            foreach ($forEmailCheck as $key => $value) {
                if(in_array($value, $usersArray)) {
                    unset($forEmailCheck[$key]);
                }
            }
            
            if(isset($forEmailCheck) && !empty($forEmailCheck)) {

                $ques = explode(",", $dbUsers[0]->questions);
                $db_marksSum_data = (array) $this->queans_model->get_marks_sum($ques);
                //var_dump($db_marksSum_data[0]->Marks); die();

                foreach ($forEmailCheck as $key => $value) {
                    $whereQuizUser = array(
                        'quiz_id' => $_POST['quiz_id'],
                        'user_id' => $value,
                    );

                    //sum of obtained marks per quiz
                    $db_obtmarksSum_data = (array) $this->quizRecord_model->get_obtainedmarks_sum($whereQuizUser);
                    //var_dump($db_obtmarksSum_data['obtained_marks']);

                    $UsersEmails = $this->user_model->get_UserEmail($value);
                    //var_dump($UsersEmails[0]->email);

                    $from_mail = AALTO_FROM_MAIL;
                    $from_name = AALTO_FROM_NAME;
                    $subject = "Quiz Report";
                    $content = "<p>Your score for the following quiz given by ".$this->session->userdata('userName').", you can also check it into the Quiz App.</p>";
                    $content .= "<p>Quiz :- ".$dbUsers[0]->QuizUniqueId.' - '.$dbUsers[0]->Title."</p>";
                    $content .= "<p>Score :- ".$db_obtmarksSum_data['obtained_marks'].' / '.$db_marksSum_data[0]->Marks."</p>";
                    $mail = genericSendEmail($UsersEmails[0]->email, NULL, $from_mail, $from_name, $subject, $content);
                }
            }
            
            $update =  $this->quiz_model->update($insert_data, $whereData, NULL);
            
            if($update) {
                echo "true";
            } else {
                echo "false";
            }
        } else {
            echo "not_selected";
        }
    }

}
?>