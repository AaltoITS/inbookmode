<?php
class Quiz extends CI_Controller {

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

        if($this->input->post('addQuizSubmit')) {
            // for get user specific books from database
            if($this->input->post('id') == 0) {
            $check_quiz = array(
                'Title' =>$this->input->post('quiz'),
                //'AuthorId' =>$this->session->userdata('userId'),
            );
            $data['fetch_data'] = (array) $this->quiz_model->get_details_list($check_quiz, NULL);

            if(count($data['fetch_data']) > 0) {
                $this->session->set_flashdata('error_msg', 'This quiz name is taken by another author. Try another.');
                redirect('quiz');
            }
            //for unique Quiz Id
            $data['uniqueQuiz'] = (array) $this->quiz_model->last_uniquequiz();
            $splittedstring = explode("#Quiz00", $data['uniqueQuiz']['QuizUniqueId']);
            $uniqueQuiz = (int)$splittedstring[1] + 1;
            $newUniqueQuiz = '#Quiz00'.$uniqueQuiz;
            //check if already exist
            $check_unique = array(
                'QuizUniqueId' => $ewUniqueQuiz,
            );
            $data['fetch_unique'] = (array) $this->quiz_model->get_details_list($check_unique, NULL);
            //if exists generate new one 
            if(count($data['fetch_unique']) > 0) {
                $data['uniqueQuiz'] = (array) $this->quiz_model->last_uniquequiz();
                $splittedstring = explode("#Quiz00", $data['uniqueQuiz']['QuizUniqueId']);
                $uniqueQuiz = (int)$splittedstring[1] + 1;
                $newUniqueQuiz = '#Quiz00'.$uniqueQuiz;
            }

            // for insert data into books table
            $timeData = $this->input->post('hour').':'.$this->input->post('minutes');
            $AddData = array(
                'QuizUniqueId' => $newUniqueQuiz,
                'Title' => strip_tags($this->input->post('quiz')),
                'Description' =>$this->input->post('desc'),
                'Time' =>$timeData,
                'AuthorId' => $this->session->userdata('userId')
            );

            //var_dump($AddpartData); die();
            $insert = $this->quiz_model->insert($AddData);

            if($insert) {
                //Activity log for profile update
                $activity = 'Quiz Added';

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

                $this->session->set_flashdata('success', 'Quiz Added successfully');
                redirect('quiz'); 
            }

            } else {

            if($this->input->post('quiz') != $this->input->post('db_quiz')) {
                $check_quiz = array(
                    'Title' =>$this->input->post('quiz'),
                    //'AuthorId' =>$this->session->userdata('userId'),
                );
                $data['fetch_data'] = (array) $this->quiz_model->get_details_list($check_quiz, NULL);

                if(count($data['fetch_data']) > 0) {
                $this->session->set_flashdata('error_msg', 'This quiz name is taken by another author. Try another.');
                redirect('quiz');
            }

            } 

            // for update data into books table
            $timeData = $this->input->post('hour').':'.$this->input->post('minutes');
            $updateData = array(
                'Title' => strip_tags($this->input->post('quiz')),
                'Description' =>$this->input->post('desc'),
                'Time' =>$timeData,
            );

            $whereData = array(
                'QuizId' => $this->input->post('id'),
            );

            $update =  $this->quiz_model->update($updateData, $whereData, NULL);

            if($update) {
                //Activity log for profile update
                $activity = 'Quiz Updated';

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
                $this->session->set_flashdata('success', 'Quiz Updated successfully');
                redirect('quiz'); 

                }
            }
        }

	/*
     * fetch user specific data from db and for admin also
     */
        $Aid = array('AuthorId' => $this->session->userdata('userId'));
        $db_data = (array) $this->quiz_model->get_details_list($Aid, NULL);
        //var_dump($db_data); die();
        $data['fetch_data'] = $db_data;
        //$db_quiz_data = '';
        foreach ($data['fetch_data'] as $key => $value) {
            //$db_quiz_data[] = (array) $this->queans_model->get_details_list(array('QuizId' =>$value->QuizId), NULL);
            $db_attempt_data[] = (array) $this->quizRecord_model->get_attempted_data($value->QuizId);

            //sum of marks per quiz
            $allQue = explode(",", $value->questions);
            $db_marksSum_data[] = (array) $this->queans_model->get_marks_sum($allQue);
        }
        //$data['count_quiz'] = $db_quiz_data;
        $data['count_attempt'] = $db_attempt_data;
        $data['marks_sum'] = $db_marksSum_data;
    	$data['common_details'] = $this->commonView;
    	$this->load->view('users/quiz', $data);
    }

    public function all_question($quizid=FALSE) {

        if($this->input->post('QueSubmit')) {

            if($this->input->post('id') == 0) {

                $check_attempt = (array) $this->quizRecord_model->get_attempted_data($quizid);
                if(count($check_attempt) > 0) {
                    $this->session->set_flashdata('error_msg', 'You can not add questions after one or more user attempt it.');
                    redirect('quiz/all_question/'.$this->input->post('db_quiz'));
                }

                $check_que = array(
                    'Question' =>$this->input->post('question'),
                    'AuthorId' =>$this->session->userdata('userId'),
                    'status' =>'Active',
                );
                $db_que = (array) $this->queans_model->get_details_list($check_que, NULL);

                if(count($db_que) > 0) {
                    $this->session->set_flashdata('error_msg', 'Question Already Exists');
                    redirect('quiz/all_question/'.$this->input->post('db_quiz'));
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
                        //'QuizId' => strip_tags($this->input->post('db_quiz')),
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
                                redirect('quiz/all_question/'.$this->input->post('db_quiz'));
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
                            //'QuizId' => strip_tags($this->input->post('db_quiz')),
                            'AuthorId' => $this->session->userdata('userId')
                        );

                    } else {

                        if($this->input->post('question_type') == 5) {
                            $mystring = $this->input->post('question');
                            $findme   = '____';
                            $pos = strpos($mystring, $findme);
                            if($pos === false) {
                                $this->session->set_flashdata('error_msg', 'At least one fill in the blank needed.');
                                redirect('quiz/all_question/'.$this->input->post('db_quiz'));
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

                    $dbQue = (array) $this->quiz_model->get_details_list(NULL, array('QuizId'=>$this->input->post('db_quiz')), 'questions');
                    
                    if(!empty($dbQue[0]->questions)) {
                        $parts = explode(',', $dbQue[0]->questions);
                        array_push($parts, $insert);
                        $finelQues = implode(',', $parts);
                    } else {
                        $finelQues = $insert;
                    }

                    $updateQue = array(
                        'questions' => $finelQues,
                    );
                    $whereQuiz = array(
                        'QuizId' => $this->input->post('db_quiz'),
                    );

                    $update =  $this->quiz_model->update($updateQue, $whereQuiz, NULL);

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
                    redirect('quiz/all_question/'.$this->input->post('db_quiz'));
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
                        redirect('quiz/all_question/'.$this->input->post('db_quiz'));
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
                        //'QuizId' => strip_tags($this->input->post('quiz'))
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
                            redirect('quiz/all_question/'.$this->input->post('db_quiz'));
                        }
                    } else {
                        $upload_data = $this->upload->data();
                        $file_name =   $upload_data['file_name'];
                    }
                    $updateData = array(
                        'Question' => strip_tags($this->input->post('question')),
                        'Marks' => strip_tags($this->input->post('marks')),
                        'Answer' => strip_tags($this->input->post('answer')),
                        'img' => $file_name,
                        //'QuizId' => strip_tags($this->input->post('quiz'))
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
                    redirect('quiz/all_question/'.$this->input->post('db_quiz'));
                }
            }
        }

        $whereData = array(
            'QuizId' => $quizid,
        );
        $Aid = array('AuthorId' => $this->session->userdata('userId'));
        //to show in table
        //$db_data = (array) $this->queans_model->get_details_list($whereData, NULL);

        $dbQue = (array) $this->quiz_model->get_details_list(NULL, array('QuizId'=>$quizid), 'questions');
        $queArray = explode(',', $dbQue[0]->questions);

        $quizSpecifiQue = (array) $this->queans_model->get_quiz_questions($queArray);
        
        //for dropdown in form
        $db_quiz = (array) $this->quiz_model->get_details_list($Aid, NULL);
        //to show in title
        $sel_quiz = (array) $this->quiz_model->get_details_list($whereData, NULL);
        //var_dump($sel_quiz); die();

        $db_joinque = (array) $this->queans_model->get_que_list(NULL, NULL);

        $db_attempt_data = (array) $this->quizRecord_model->get_attempted_data($quizid);

        $data['count_attempt'] = count($db_attempt_data);
        $data['fetch_allQue'] = $db_joinque;
        $data['fetch_data'] = $quizSpecifiQue;
        $data['quiz_data'] = $db_quiz;
        $data['sel_quiz'] = $sel_quiz;
        $data['common_details'] = $this->commonView;
        $this->load->view('users/questions', $data);
    }


    public function delete_quiz($id=FALSE) {

        $whereData = array(
            'QuizId' => $id,
        );

        $delete =  $this->quiz_model->delete($whereData, NULL);
        if($delete) {

            $whereQ = array(
                'quiz_id' => $id,
            );
            $delete =  $this->quizRecord_model->delete($whereQ, NULL);
            
            //Activity log for profile update
            $activity = 'Quiz Deleted';
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
            $this->session->set_flashdata('success', 'Quiz Deleted successfully');
            redirect('quiz');
        }
    }

    public function assign_quiz($quizid=FALSE) {

        $whereData = array(
            'QuizId' => $quizid,
        );
        $sel_quiz = (array) $this->quiz_model->get_details_list($whereData, NULL);
        //var_dump($sel_quiz); die();
        //to show in table
        $whereStatus = array(
            'status' => 'Active',
        );
        $db_data = (array) $this->user_model->get_details_list($whereStatus, NULL);
        
        $data['fetch_data'] = $db_data;
        $data['sel_quiz'] = $sel_quiz;
        $data['common_details'] = $this->commonView;
        $this->load->view('users/assign_quiz', $data);
    }

    function get_assign_user() {

        if(isset($_POST['userMapping']) && !empty($_POST['userMapping'])) {
            $forEmailCheck = $_POST['userMapping'];
            $users = implode(',', $_POST['userMapping']);
            
            $assign_data = array(
                'assigned_users' => $users
            );

            $whereData = array(
                'QuizId' => $_POST['quiz_id'],
            );

            $dbUsers = (array) $this->quiz_model->get_details_list(NULL, $whereData, 'QuizUniqueId,Title,assigned_users');
            $usersArray = explode(',', $dbUsers[0]->assigned_users);
            
            foreach ($forEmailCheck as $key => $value) {
                if(in_array($value, $usersArray)) {
                    unset($forEmailCheck[$key]);
                }
            }
            
            if(isset($forEmailCheck) && !empty($forEmailCheck)) {
                $UsersEmails = $this->user_model->get_UserEmail($forEmailCheck);
                foreach ($UsersEmails as $key => $value) {
                    $result[] = $value->email;
                }
                $usersFinel = implode(',', $result);

                $from_mail = AALTO_FROM_MAIL;
                $from_name = AALTO_FROM_NAME;
                $subject = "Quiz Assigned";
                $content = "<p>The following quiz has been assigned to you by ".$this->session->userdata('userName').", please check the Quiz App to attempt it.</p>";
                $content .= "<p>".$dbUsers[0]->QuizUniqueId.' - '.$dbUsers[0]->Title."</p>";
                $mail = genericSendEmail($usersFinel, NULL, $from_mail, $from_name, $subject, $content);
            }
            
            $update =  $this->quiz_model->update($assign_data, $whereData, NULL);
            
            if($update) {
                echo "true";
            } else {
                echo "false";
            }
        } else {
            echo "not_selected";
        }

        //$this->session->set_flashdata('success', 'Quiz Assigned successfully');

        //redirect('quiz/assign_quiz/'.$_POST['quiz_id']);
        
    }

    function add_questionsToQuiz() {

        if(isset($_POST['queMapping']) && !empty($_POST['queMapping'])) {

            $check_attempt = (array) $this->quizRecord_model->get_attempted_data($_POST['quiz_id']);
            if(count($check_attempt) > 0) {
                echo "attempted";
            } else {
                $inactiveQue = '';
                if(isset($_POST['inactiveQue']) && !empty($_POST['inactiveQue'])) {
                    $inactiveQue = $_POST['inactiveQue'].',';
                } else {
                    $inactiveQue = '';
                }

                $que = implode(',', $_POST['queMapping']);
                
                $postQue = $inactiveQue.$que;
                $que_data = array(
                    'questions' => $postQue
                );
                $whereData = array(
                    'QuizId' => $_POST['quiz_id'],
                );

                $update =  $this->quiz_model->update($que_data, $whereData, NULL);
                
                if($update) {
                    echo "true";
                } else {
                    echo "false";
                }
            }
        } else {
            echo "not_selected";
        }
        
        //$this->session->set_flashdata('success', 'Questions Added successfully');

        //redirect('quiz/all_question/'.$_POST['quiz_id']);
        
    }

}
?>