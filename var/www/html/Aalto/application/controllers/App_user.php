<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * User Management class
 */
class App_user extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('activitylog_model');
        $this->load->model('domain_model');
        $this->load->model('book_model');
        $this->load->model('addfrontmatter_model');
        $this->load->model('addchapter_model');
        $this->load->model('addpart_model');
        $this->load->model('addbackmatter_model');
        $this->load->model('bookmarks_model');
        $this->load->model('statistic_model');
        $this->load->model('objtag_model'); 
        $this->load->model('notes_model');
        $this->load->model('reference_model');   
        $this->load->model('chat_model');
        $this->load->model('url_3DModel_model');
        $this->load->model('queans_model');
        $this->load->model('quiz_model');
        $this->load->model('quizRecord_model');
    }
    
    /*
     * User login
     */
    public function login(){
        $data = array();

        $con = array(
            'username' => $this->input->post('username'),
            'password' => md5($this->input->post('password')),
            //'status' => 'Active',
        );

        $checkLogin = (array) $this->user_model->get_details(NULL, $con);
        
        if(count($checkLogin) > 0) {
            if($checkLogin['status'] == 'Active') {
            //user details
            $checkLogin['profile_pic'] = base_url()."uploads/".$checkLogin['profile_pic'];
            $data['user'] = $checkLogin;
            //book details
            $where = array('privacy_status'=>'Public');
            $books = (array) $this->book_model->get_details_list($where, NULL);
            foreach ($books as $key => $row) {
                if($row->cover_image !=''){
                    $row->cover_image = base_url()."uploads/".$row->cover_image;
                }
            }
            $data['books'] = $books;
            echo json_encode($data);
            } else {
                echo 'inactive';
            }
        } else {
            echo 'false';
            //echo 'false '.'Username = '.$this->input->post('username').' & Password = '.$this->input->post('password');
        }
    }

    /*
     * User registration
     */
    public function registration(){
        $data = array();

        $email_chk = array('email'=>$this->input->post('email'));
        $checkemail =  $this->user_model->get_details($email_chk, NULL);

        if(count($checkemail) > 0){
                $data['msg'] = 'Email already exists';
            } else {

            if(!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^",$this->input->post('email'))){
                $data['msg'] = 'Invalid email';
            } else {  

            $email = strip_tags($this->input->post('email'));
            $explode = explode("@", $email);
            $dom = explode(".", $explode[1]);
            $domain = $dom[0];

            $dom_chk = array('domain'=>$domain);
            $checkdomain =  $this->domain_model->get_details($dom_chk, NULL);

                if(count($checkdomain) == 0){
                    $data['msg'] = 'Invalid domain';
                } else {

            $user_chk = array('username'=>$this->input->post('username'));
            $checkuser =  $this->user_model->get_details($user_chk, NULL);       
                    
                if(count($checkuser) > 0) {
                    $data['msg'] = 'Username already exists';   
                } else {

                    if(($this->input->post('role')) == '1') {
                        $role = 'Author';
                    } else {
                        $role = 'Reader';
                    }
                $userData = array(
                    'username' => strip_tags($this->input->post('username')),
                    'email' => strip_tags($this->input->post('email')),
                    'role' => $role,
                    'password' => md5($this->input->post('password')),
                    'visible_password' => strip_tags($this->input->post('password')),
                    'profile_pic' => 'default-pic.png',
                    'created' => date('Y-m-d H:i:s'),
                );

                $insert = $this->user_model->insert($userData);

                    if($insert){
                        $from_mail = AALTO_FROM_MAIL;
                        $from_name = AALTO_FROM_NAME;
                        $subject = "Registration";
                        $content = "<p>Your registration was successful. you can login after request has been approved.</p>";
                        //var_dump($content); die();
                        $mail = genericSendEmail($this->input->post('email'), NULL, $from_mail, $from_name, $subject, $content);
                        //var_dump($mail); die();
                        $data['msg'] = 'true';
                    }else{
                        $data['msg'] = 'false';
                    }
                }
            }
        }
        }
        echo $data['msg'];
        //echo json_encode($data);    
    }

    /*
     * Forgot password
     */
    public function forgot(){
        $data = array();

        $email_chk = array('email'=>$this->input->post('email'));
        $checkemail =  $this->user_model->get_details($email_chk, NULL);

        if(count($checkemail) > 0){
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
                $data['msg'] = 'true';
            }
        } else {
            $data['msg'] = 'false';
        }
        echo $data['msg'];
        //echo json_encode($data); 
    }


    /*
     * Fetch book specific data
     */
    public function get_book_data($book_id=FALSE){
        $data = array();
        
        $link = base_url();

        $where_book = array('id'=>$book_id);
        $get_book =  $this->book_model->get_details($where_book, NULL);
        if(count($get_book) > 0){

            $where_book_id = array('book_id'=>$book_id);

            $data['cover_image'] = "<img src=".base_url().'uploads/'.$get_book->cover_image." width='360' height='460' style='display: block; margin-left: auto; margin-right: auto;' ><!-- pagebreak -->";

            $get_frontmatter = (array) $this->addfrontmatter_model->get_details_list(NULL, $where_book_id, 'title,content');
            $get_chapter = (array) $this->addchapter_model->get_details_list(NULL, $where_book_id, 'title,sub_title,content');
            $get_part = (array) $this->addpart_model->get_details_list(NULL, $where_book_id, 'title,content');
            $get_backmatter = (array) $this->addbackmatter_model->get_details_list(NULL, $where_book_id, 'title,content');

$sr = 1;
$CheckPage = 0;
$PerPage = 11;
$NoOf_Page = 1;
$total_sub_title = "";

        $json_data =  json_encode($get_chapter);
        $multiple = json_decode($json_data, true);
        foreach($multiple as $key => $single){
        if(!empty($single['sub_title'])) {
            $schools_array = explode("@#@", $single['sub_title']);
            $result = count($schools_array);
            $total_sub_title = $result + $total_sub_title;
        }
    }

$total_title = count($get_frontmatter) + count($get_chapter) + count($get_part) + count($get_backmatter) + $total_sub_title;
//for page no. start including cover image and title page
if($total_title > 11){
    $page_no = ceil($total_title / 11) + 2;
} else {
    $page_no = 3;
}

        $implode = array(); 
           
        if(count($get_frontmatter) > 0){
            
            //frontmatter details
            $json_data =  json_encode($get_frontmatter);

            $multiple = json_decode($json_data, true);
            foreach($multiple as $key => $single) {
                $CheckPage = ($CheckPage + 1);
                
                //$implode[] = implode(', ', $single);
                $hash = 'frontMatter';
                $title = $single['title'];
                
            if($key == 0){
                //$implode[] = "".$sr." . &nbsp<a href='#' id='$page_no' style='text-decoration: none; color: inherit;'
                $implode[] = "<a href='#' id='$page_no' style='text-decoration: none; color: inherit;' onClick='reply_click(this.id)' ><span style='font-size: 16px;' onclick='SendTitle(this.innerHTML)'>$title</span></a><div style='margin-left: 90%; margin-top: -20px;'><span style='font-size: 14px;'>$page_no</span></div>";
            } else {
                if($CheckPage == ($PerPage*$NoOf_Page)) {
                    $NoOf_Page = $NoOf_Page + 1;
                    //$implode[] = "".$sr." . &nbsp<a href='#' id='".$page_no."' style='text-decoration: none; color: inherit;'
                    $implode[] = "<a href='#' id='".$page_no."' style='text-decoration: none; color: inherit;' onClick='reply_click(this.id)' ><span style='font-size: 16px;' onclick='SendTitle(this.innerHTML)'>$title</span></a><div style='margin-left: 90%; margin-top: -20px;'><span style='font-size: 14px;'>$page_no</span></div><input type='hidden' value='' id='Location'></input><input type='hidden' value='' id='CurrentTitle'></input>
<script>function reply_click(clicked_id) {document.getElementById('Location').value = clicked_id;}</script>
<script>function SendTitle(myTitle){document.getElementById('CurrentTitle').value =  myTitle;}</script><!-- pagebreak -->";
                } else {
                    if($total_title == $CheckPage) {
                        //$implode[] = "".$sr." . &nbsp<a href='#' id='".$page_no."' style='text-decoration: none; color:
                        $implode[] = "<a href='#' id='".$page_no."' style='text-decoration: none; color: inherit;' onClick='reply_click(this.id)' ><span style='font-size: 16px;' onclick='SendTitle(this.innerHTML)'>$title</span></a><div style='margin-left: 90%; margin-top: -20px;'><span style='font-size: 14px;'>$page_no</span></div><input type='hidden' value='' id='Location'></input><input type='hidden' value='' id='CurrentTitle'></input>
<script>function reply_click(clicked_id) {document.getElementById('Location').value = clicked_id;}</script>
<script>function SendTitle(myTitle){document.getElementById('CurrentTitle').value =  myTitle;}</script><!-- pagebreak -->";
                    } else {
                        //$implode[] = "".$sr." . &nbsp<a href='#' id='".$page_no."' style='text-decoration: none; color:
                        $implode[] = "<a href='#' id='".$page_no."' style='text-decoration: none; color: inherit;' onClick='reply_click(this.id)' ><span style='font-size: 16px;' onclick='SendTitle(this.innerHTML)'>$title</span></a><div style='margin-left: 90%; margin-top: -20px;'><span style='font-size: 14px;'>$page_no</span></div>";
                    }
                }
                
            }
                $count_page = substr_count($single['content'],"<!-- pagebreak -->");
                $page_no = $count_page + $page_no;

                $sr == $sr++;
            }
            //echo implode(', ', $implode);
        }

//Chapters        
        if(count($get_chapter) > 0){
            
            $json_data =  json_encode($get_chapter);

            $multiple = json_decode($json_data, true);

            foreach($multiple as $key => $single) {
                $CheckPage = ($CheckPage + 1);

                $sub_sr = 1;
                //$implode[] = implode(', ', $single);
                $hash = 'chapters';
                $title = $single['title'];
                $all_sub_title = $single['sub_title'];

                //for subtitle page number
                $exp=explode('<!-- pagebreak -->',$single['content']);
                //end

            if($CheckPage == ($PerPage*$NoOf_Page)) {
                $NoOf_Page = $NoOf_Page + 1;

                //$implode[] = "".$sr." . &nbsp<a href='#' id='".$page_no."' style='text-decoration: none; color: inherit;'
                $implode[] = "<a href='#' id='".$page_no."' style='text-decoration: none; color: inherit;' onClick='reply_click(this.id)' ><span font-size: 16px; onclick='SendTitle(this.innerHTML)'>$title</span></a><div style='margin-left: 90%; margin-top: -20px;'><span style='font-size: 14px;'>$page_no</span></div><input type='hidden' value='' id='Location'></input><input type='hidden' value='' id='CurrentTitle'></input>
<script>function reply_click(clicked_id) {document.getElementById('Location').value = clicked_id;}</script>
<script>function SendTitle(myTitle){document.getElementById('CurrentTitle').value =  myTitle;}</script><!-- pagebreak -->";
            } else {
                if($total_title == $CheckPage) {
                    //$implode[] = "".$sr." . &nbsp<a href='#' id='".$page_no."' style='text-decoration: none; color: inherit;'
                    $implode[] = "<a href='#' id='".$page_no."' style='text-decoration: none; color: inherit;' onClick='reply_click(this.id)' ><span style='font-size: 16px;' onclick='SendTitle(this.innerHTML)'>$title</span></a><div style='margin-left: 90%; margin-top: -20px;'><span style='font-size: 14px;'>$page_no</span></div><input type='hidden' value='' id='Location'></input><input type='hidden' value='' id='CurrentTitle'></input>
<script>function reply_click(clicked_id) {document.getElementById('Location').value = clicked_id;}</script>
<script>function SendTitle(myTitle){document.getElementById('CurrentTitle').value =  myTitle;}</script><!-- pagebreak -->";
                } else {
                    //$implode[] = "".$sr." . &nbsp<a href='#' id='".$page_no."' style='text-decoration: none; color: inherit;'
                    $implode[] = "<a href='#' id='".$page_no."' style='text-decoration: none; color: inherit;' onClick='reply_click(this.id)' ><span style='font-size: 16px;' onclick='SendTitle(this.innerHTML)'>$title</span></a><div style='margin-left: 90%; margin-top: -20px;'><span style='font-size: 14px;'>$page_no</span></div>";
                }
            }
                //for subtitle page number
                $last_page_no = $page_no;
                //end
                $count_page = substr_count($single['content'],"<!-- pagebreak -->");
                $page_no = $count_page + $page_no;

                if(!empty($all_sub_title)) {
                foreach (explode('@#@', $all_sub_title) as $key => $sub_title) {
                    $CheckPage = ($CheckPage + 1);
//for subtitle page number
$NEWPAGE = 1;
$page_sub_title = "";
foreach ($exp as $key => $value) {
    if (strpos($value, $sub_title) !== false) {
    $NEWPAGE = $NEWPAGE;
    $page_sub_title = ($NEWPAGE+$last_page_no) - 1;
    //end
    if($CheckPage == ($PerPage*$NoOf_Page)) {
                $NoOf_Page = $NoOf_Page + 1;

                //$implode[] = "SubTitle_&nbsp&nbsp&nbsp".$sr.'.&nbsp'.$sub_sr." &nbsp<a href='#' id='".$page_sub_title."' style='text-decoration: none; color: inherit;' onClick='reply_click(this.id)' ><span onclick='SendTitle(this.innerHTML)'>$sub_title</span></a><div style='margin-left: 90%; margin-top: -20px;'><span style='font-size: 14px;'>$page_sub_title</span></div><input type='hidden' value='' id='Location'></input><input type='hidden' value='' id='CurrentTitle'></input>
                $implode[] = "&nbsp&nbsp&nbsp&nbsp<a href='#' id='".$page_sub_title."' style='text-decoration: none; color: inherit;' onClick='reply_click(this.id)' ><span style='font-size: 14px;' onclick='SendTitle(this.innerHTML)'>$sub_title</span></a><div style='margin-left: 90%; margin-top: -20px;'><span style='font-size: 14px;'>$page_sub_title</span></div><input type='hidden' value='' id='Location'></input><input type='hidden' value='' id='CurrentTitle'></input>
<script>function reply_click(clicked_id) {document.getElementById('Location').value = clicked_id;}</script>
<script>function SendTitle(myTitle){document.getElementById('CurrentTitle').value =  myTitle;}</script><!-- pagebreak -->";
            } else {
                if($total_title == $CheckPage) {
                    //$implode[] = "SubTitle_&nbsp&nbsp&nbsp".$sr.'.&nbsp'.$sub_sr." &nbsp<a href='#' id='".$page_sub_title."' style='text-decoration: none; color: inherit;' onClick='reply_click(this.id)' ><span onclick='SendTitle(this.innerHTML)'>$sub_title</span></a><div style='margin-left: 90%; margin-top: -20px;'><span style='font-size: 14px;'>$page_sub_title</span></div><input type='hidden' value='' id='Location'></input><input type='hidden' value='' id='CurrentTitle'></input>
                    $implode[] = "&nbsp&nbsp&nbsp&nbsp<a href='#' id='".$page_sub_title."' style='text-decoration: none; color: inherit;' onClick='reply_click(this.id)' ><span style='font-size: 14px;' onclick='SendTitle(this.innerHTML)'>$sub_title</span></a><div style='margin-left: 90%; margin-top: -20px;'><span style='font-size: 14px;'>$page_sub_title</span></div><input type='hidden' value='' id='Location'></input><input type='hidden' value='' id='CurrentTitle'></input>
<script>function reply_click(clicked_id) {document.getElementById('Location').value = clicked_id;}</script>
<script>function SendTitle(myTitle){document.getElementById('CurrentTitle').value =  myTitle;}</script><!-- pagebreak -->";
                } else {
                    //$implode[] = "SubTitle_&nbsp&nbsp&nbsp".$sr.'.&nbsp'.$sub_sr." &nbsp<a href='#' id='".$page_sub_title."' style='text-decoration: none; color: inherit;' onClick='reply_click(this.id)' ><span onclick='SendTitle(this.innerHTML)'>$sub_title</span></a><div style='margin-left: 90%; margin-top: -20px;'><span style='font-size: 14px;'>$page_sub_title</span></div>";
                    $implode[] = "&nbsp&nbsp&nbsp&nbsp<a href='#' id='".$page_sub_title."' style='text-decoration: none; color: inherit;' onClick='reply_click(this.id)' ><span style='font-size: 14px;' onclick='SendTitle(this.innerHTML)'>$sub_title</span></a><div style='margin-left: 90%; margin-top: -20px;'><span style='font-size: 14px;'>$page_sub_title</span></div>";
                }
            }
//for subtitle page number
            } else {
    $NEWPAGE = $NEWPAGE + 1;
    }
}
//end
                    $sub_sr == $sub_sr++;
                } }
                $sr == $sr++;
            }
            //echo implode(', ', $implode);
        }

//Parts
        if(count($get_part) > 0){
            
            $json_data =  json_encode($get_part);

            $multiple = json_decode($json_data, true);
            foreach($multiple as $key => $single) {
                $CheckPage = ($CheckPage + 1);

                //$implode[] = implode(', ', $single);
                $hash = 'parts';
                $title = $single['title'];

            if($CheckPage == ($PerPage*$NoOf_Page)) {
                $NoOf_Page = $NoOf_Page + 1;

                //$implode[] = "".$sr." . &nbsp<a href='#' id='".$page_no."' style='text-decoration: none; color: inherit;'
                $implode[] = "<a href='#' id='".$page_no."' style='text-decoration: none; color: inherit;' onClick='reply_click(this.id)' ><span style='font-size: 16px;' onclick='SendTitle(this.innerHTML)'>$title</span></a><div style='margin-left: 90%; margin-top: -20px;'><span style='font-size: 14px;'>$page_no</span></div><input type='hidden' value='' id='Location'></input><input type='hidden' value='' id='CurrentTitle'></input>
<script>function reply_click(clicked_id) {document.getElementById('Location').value = clicked_id;}</script>
<script>function SendTitle(myTitle){document.getElementById('CurrentTitle').value =  myTitle;}</script><!-- pagebreak -->";
            } else {
                if($total_title == $CheckPage) {
                    //$implode[] = "".$sr." . &nbsp<a href='#' id='".$page_no."' style='text-decoration: none; color: inherit;'
                    $implode[] = "<a href='#' id='".$page_no."' style='text-decoration: none; color: inherit;' onClick='reply_click(this.id)' ><span style='font-size: 16px;' onclick='SendTitle(this.innerHTML)'>$title</span></a><div style='margin-left: 90%; margin-top: -20px;'><span style='font-size: 14px;'>$page_no</span></div><input type='hidden' value='' id='Location'></input><input type='hidden' value='' id='CurrentTitle'></input>
<script>function reply_click(clicked_id) {document.getElementById('Location').value = clicked_id;}</script>
<script>function SendTitle(myTitle){document.getElementById('CurrentTitle').value =  myTitle;}</script><!-- pagebreak -->";
                } else {
                    //$implode[] = "".$sr." . &nbsp<a href='#' id='".$page_no."' style='text-decoration: none; color: inherit;'
                    $implode[] = "<a href='#' id='".$page_no."' style='text-decoration: none; color: inherit;' onClick='reply_click(this.id)' ><span style='font-size: 16px;' onclick='SendTitle(this.innerHTML)'>$title</span></a><div style='margin-left: 90%; margin-top: -20px;'><span style='font-size: 14px;'>$page_no</span></div>";
                }
            }
                $count_page = substr_count($single['content'],"<!-- pagebreak -->");
                $page_no = $count_page + $page_no;

                $sr == $sr++;
            }
            //echo implode(', ', $implode);
        }

//Back matter        
        if(count($get_backmatter) > 0){
            
            $json_data =  json_encode($get_backmatter);

            $multiple = json_decode($json_data, true);
            foreach($multiple as $key => $single) {
                $CheckPage = ($CheckPage + 1);

                //$implode[] = implode(', ', $single);
                $hash = 'backMatters';
                $title = $single['title'];

            if($CheckPage == ($PerPage*$NoOf_Page)) {
                $NoOf_Page = $NoOf_Page + 1;

                //$implode[] = "".$sr." . &nbsp<a href='#' id='".$page_no."' style='text-decoration: none; color: inherit;'
                $implode[] = "<a href='#' id='".$page_no."' style='text-decoration: none; color: inherit;' onClick='reply_click(this.id)' ><span style='font-size: 16px;' onclick='SendTitle(this.innerHTML)'>$title</span></a><div style='margin-left: 90%; margin-top: -20px;'><span style='font-size: 14px;'>$page_no</span></div><input type='hidden' value='' id='Location'></input><input type='hidden' value='' id='CurrentTitle'></input>
<script>function reply_click(clicked_id) {document.getElementById('Location').value = clicked_id;}</script>
<script>function SendTitle(myTitle){document.getElementById('CurrentTitle').value =  myTitle;}</script><!-- pagebreak -->";
            } else {
                if(count($get_backmatter) == ($key + 1)){
                    //$implode[] = "".$sr." . &nbsp<a href='#' id='".$page_no."' style='text-decoration: none; color: inherit;'
                    $implode[] = "<a href='#' id='".$page_no."' style='text-decoration: none; color: inherit;' onClick='reply_click(this.id)' ><span style='font-size: 16px;' onclick='SendTitle(this.innerHTML)'>$title</span></a><div style='margin-left: 90%; margin-top: -20px;'><span style='font-size: 14px;'>$page_no</span></div><input type='hidden' value='' id='Location'></input><input type='hidden' value='' id='CurrentTitle'></input>
<script>function reply_click(clicked_id) {document.getElementById('Location').value = clicked_id;}</script>
<script>function SendTitle(myTitle){document.getElementById('CurrentTitle').value =  myTitle;}</script><!-- pagebreak -->";
                } else {
                    //$implode[] = "".$sr." . &nbsp<a href='#' id='".$page_no."' style='text-decoration: none; color: inherit;'
                    $implode[] = "<a href='#' id='".$page_no."' style='text-decoration: none; color: inherit;' onClick='reply_click(this.id)' ><span style='font-size: 16px;' onclick='SendTitle(this.innerHTML)'>$title</span></a><div style='margin-left: 90%; margin-top: -20px;'><span style='font-size: 14px;'>$page_no</span></div>";
                }
            }
                $count_page = substr_count($single['content'],"<!-- pagebreak -->");
                $page_no = $count_page + $page_no;

                $sr == $sr++;
            }
            //echo implode(', ', $implode);
        }

            //print_r($implode); die();
            $data['content_array'] = $implode;
            //var_dump($data['content_array']); die();
            $data['book_frontmatters'] = (array) $this->addfrontmatter_model->get_details_list($where_book_id, NULL);
            $data['book_chapters'] = (array) $this->addchapter_model->get_details_list($where_book_id, NULL);
            $data['book_parts'] = (array) $this->addpart_model->get_details_list($where_book_id, NULL);
            $data['book_backmatters'] = (array) $this->addbackmatter_model->get_details_list($where_book_id, NULL);

            //replace site url with ../../ in contents
            if(count($data['book_frontmatters']) > 0){
                for ($i=0; $i < count($data['book_frontmatters']); $i++) { 
                    $data['book_frontmatters'][$i]->content = str_replace('../../', $link, $data['book_frontmatters'][$i]->content);
                    $data['book_frontmatters'][$i]->page_count = substr_count($data['book_frontmatters'][$i]->content,"<!-- pagebreak -->");
            }
        }   
            if(count($data['book_chapters']) > 0){
                for ($i=0; $i < count($data['book_chapters']); $i++) {
                    $data['book_chapters'][$i]->content = str_replace('../../', $link, $data['book_chapters'][$i]->content);
                    //$data['book_chapters'][$i]->content = str_replace('onclick="3dmodel"', 'onclick="confirmClicked(this.href, this.id); return false;" onmouseover="OnMouseHover(this.href, this.id)" onmouseout="OnMouseExit()"', $data['book_chapters'][$i]->content);
                    //$data['book_chapters'][$i]->content = str_replace('onclick="3dmodelelement"', 'onclick="confirmClicked(this.href, this.id); return false;"', $data['book_chapters'][$i]->content);
                    $data['book_chapters'][$i]->page_count = substr_count($data['book_chapters'][$i]->content,"<!-- pagebreak -->");

                    preg_match_all('~<a(.*?)id="([^"]+)"(.*?)>~', $data['book_chapters'][$i]->content, $matches);
                    //var_dump($matches[2]);
                    $count = count($matches[2]);
                    for ($row = 0; $row < $count ; $row++) {
                        $poid = $matches[2]["$row"];
                        $where_poid = array('poid'=>$poid);
                        $get_dimension = (array) $this->url_3DModel_model->get_details_list(NULL, $where_poid, 'dimension');
                        //var_dump($get_dimension[0]->dimension);
                        if($get_dimension) {
                            $dimension = $get_dimension[0]->dimension;
                            //$data['book_chapters'][$i]->content = str_replace($poid.'"', $poid.'" data-dimension="'.$dimension.'"', $data['book_chapters'][$i]->content);
                            $data['book_chapters'][$i]->content = str_replace($poid.'"', $poid.'" data-dimension="'.$dimension.'" onclick="confirmClicked(this.href, this.id); return false;" onmouseover="OnMouseHover(this.href, this.id)" onmouseout="OnMouseExit()"', $data['book_chapters'][$i]->content);
                        }
                    }
            }
        }   
            if(count($data['book_parts']) > 0){
                for ($i=0; $i < count($data['book_parts']); $i++) {
                    $data['book_parts'][$i]->content = str_replace('../../', $link, $data['book_parts'][$i]->content);
                    $data['book_parts'][$i]->page_count = substr_count($data['book_parts'][$i]->content,"<!-- pagebreak -->");
            }
        }   
            if(count($data['book_backmatters']) > 0){
                for ($i=0; $i < count($data['book_backmatters']); $i++) {
                    $data['book_backmatters'][$i]->content = str_replace('../../', $link, $data['book_backmatters'][$i]->content);
                    $data['book_backmatters'][$i]->page_count = substr_count($data['book_backmatters'][$i]->content,"<!-- pagebreak -->");
            }
        }
            echo json_encode($data);
        
            /*$JSON = json_encode($data);
            print_r(json_decode($JSON, true));*/
        } else {
            echo "false";
        }
    }


    /*
     * bookmarks
     */
    public function bookmark(){
        $data = array();   
        $bookmark_data = array(
            'book_id' => strip_tags($this->input->post('bookid')),
            'user_id' => strip_tags($this->input->post('userid')),
            'page_no' => strip_tags($this->input->post('pageno')),
        );

        $check_bookmark = (array) $this->bookmarks_model->get_details(NULL, $bookmark_data);
        
        if(count($check_bookmark) > 0){

            $delete =  $this->bookmarks_model->delete($bookmark_data, NULL);

            if($delete){
                $data['msg'] = 'true';
            }else{
                $data['msg'] = 'false';
            }

        }else{

            $insert = $this->bookmarks_model->insert($bookmark_data);

            if($insert){
                $data['msg'] = 'true';
            }else{
                $data['msg'] = 'false';
            }
    
        }
        echo json_encode($data);
    }


    /*
     * get bookmarks
     */
    /*public function get_bookmark() {
        $data = array();

        $post_bookmark = array(
            'book_id' => strip_tags($this->input->post('bookid')),
            'user_id' => strip_tags($this->input->post('userid')),
        );

        $check_bookmark = (array) $this->bookmarks_model->get_details_list(NULL, $post_bookmark, 'page_no,created_at');
        
        if(count($check_bookmark) > 0) {
            
            //bookmark details
            foreach($check_bookmark as $var => $value) {
                $value->created_at = date('l, j F Y', strtotime($value->created_at));
            }

            $data['bookmark'] = $check_bookmark;
            
        } else {
            $data['bookmark'] = 'false';
        }
        echo json_encode($data);
    }*/

    public function get_bookmark() {
        $data = array();
        $bookmarkArray = array();
        $post_bookmark = array(
            'book_id' => strip_tags($this->input->post('bookid')),
            'user_id' => strip_tags($this->input->post('userid')),
        );

        $check_bookmark = (array) $this->bookmarks_model->get_details_list(NULL, $post_bookmark, 'page_no');
        
        if(count($check_bookmark) > 0) {
            foreach($check_bookmark as $var => $value) {
                $bookmarkArray[] = (int)$value->page_no;
            }
            //sort($bookmarkArray);
            $str = implode(',', $bookmarkArray);
            $data['bookmark'] = $str;
        } else {
            $data['bookmark'] = 'false';
        }
        echo json_encode($data);
    }


    /*
     * statistic
     */
    public function statistic($user=FALSE, $book=FALSE, $chapter=FALSE, $start=FALSE, $end=FALSE){
        $data = array();
        
        $chapter = str_replace('%20', ' ', $chapter);

        $start_date = (int)$start;
        $start = date("Y-m-d H:i:s", $start_date);

        $end_date = (int)$end;
        $end = date("Y-m-d H:i:s", $end_date);

        $datetime1 = new DateTime($start);//start time
        $datetime2 = new DateTime($end);//end time

        $interval = $datetime1->diff($datetime2);
        $date_diff = $interval->format('%H hours %I minutes %S seconds');

        $post_data = array(
            'user_id' => $user,
            'book_id' => $book,
            'chapter' => $chapter,
            'start_time' => $start,
            'end_time' => $end,
            'time_spent' => $date_diff,
        );

        $insert = $this->statistic_model->insert($post_data);

            if($insert){
                $data['msg'] = 'true';
            }else{
                $data['msg'] = 'false';
            }

            echo json_encode($data);
    }


    /*
     * print pdf
     */
    public function get_book_data_pdf($book_id=FALSE){
        $pdf_data = "";
        $link = base_url();

        $where_book = array('id'=>$book_id);
        $get_book =  $this->book_model->get_details($where_book, NULL);
        if(count($get_book) > 0){

            $where_book_id = array('book_id'=>$book_id);

            $pdf_data .= "<img src=".base_url().'uploads/'.$get_book->cover_image." width='400' height='680' style='display: block; margin-left: auto; margin-right: auto;' ><br /><br />";

            $pdf_data .= "<div class='pagebreak'></div>";

            $get_frontmatter = (array) $this->addfrontmatter_model->get_details_list(NULL, $where_book_id, 'title');
$sr = 1;
        $implode = array(); 
           
        if(count($get_frontmatter) > 0){
            
            //frontmatter details
            $json_data =  json_encode($get_frontmatter);

            $multiple = json_decode($json_data, true);
            foreach($multiple as $key => $single) {
                //$implode[] = implode(', ', $single);
                $hash = 'frontMatter';
                $title = $single['title'];
                //$pdf_data .= "".$sr." . &nbsp<a href='#' id='".$hash.$key."' style='text-decoration: none; color: inherit;' onClick='reply_click(this.id)' ><span onclick='SendTitle(this.innerHTML)'>$title</span></a><br/>";
                $pdf_data .= "<a href='#' id='".$hash.$key."' style='text-decoration: none; color: inherit;' onClick='reply_click(this.id)' ><span style='font-size: 16px;' onclick='SendTitle(this.innerHTML)'>$title</span></a><br/>";
                $sr == $sr++;
            }
            //echo implode(', ', $implode);
        }

        $get_chapter = (array) $this->addchapter_model->get_details_list(NULL, $where_book_id, 'title,sub_title');
        
        if(count($get_chapter) > 0){
            
            $json_data =  json_encode($get_chapter);

            $multiple = json_decode($json_data, true);

            foreach($multiple as $key => $single) {
                $sub_sr = 1;
                //$implode[] = implode(', ', $single);
                $hash = 'chapters';
                $title = $single['title'];
                $all_sub_title = $single['sub_title'];

                //$pdf_data .= "".$sr." . &nbsp<a href='#' id='".$hash.$key."' style='text-decoration: none; color: inherit;' onClick='reply_click(this.id)' ><span onclick='SendTitle(this.innerHTML)'>$title</span></a><br/>";
                $pdf_data .= "<a href='#' id='".$hash.$key."' style='text-decoration: none; color: inherit;' onClick='reply_click(this.id)' ><span style='font-size: 16px;' onclick='SendTitle(this.innerHTML)'>$title</span></a><br/>";
                if(!empty($all_sub_title)) {
                foreach (explode('@#@', $all_sub_title) as $key => $sub_title) {
                    $pdf_data .= "&nbsp<a href='#' id='".$hash.$key."' style='text-decoration: none; color: inherit;' onClick='reply_click(this.id)' ><span style='font-size: 14px;' onclick='SendTitle(this.innerHTML)'>$sub_title</span></a><br/>";
                    $sub_sr == $sub_sr++;
                } }
                $sr == $sr++;
            }
            //echo implode(', ', $implode);
        }


        $get_part = (array) $this->addpart_model->get_details_list(NULL, $where_book_id, 'title');

        if(count($get_part) > 0){
            
            $json_data =  json_encode($get_part);

            $multiple = json_decode($json_data, true);
            foreach($multiple as $key => $single) {
                //$implode[] = implode(', ', $single);
                $hash = 'parts';
                $title = $single['title'];
                //$pdf_data .= "".$sr." . &nbsp<a href='#' id='".$hash.$key."' style='text-decoration: none; color: inherit;' onClick='reply_click(this.id)' ><span onclick='SendTitle(this.innerHTML)'>$title</span></a><br/>";
                $pdf_data .= "<a href='#' id='".$hash.$key."' style='text-decoration: none; color: inherit;' onClick='reply_click(this.id)' ><span style='font-size: 16px;' onclick='SendTitle(this.innerHTML)'>$title</span></a><br/>";
                $sr == $sr++;
            }
            //echo implode(', ', $implode);
        }


        $get_backmatter = (array) $this->addbackmatter_model->get_details_list(NULL, $where_book_id, 'title');
        
        if(count($get_backmatter) > 0){
            
            $json_data =  json_encode($get_backmatter);

            $multiple = json_decode($json_data, true);
            foreach($multiple as $key => $single) {
                //$implode[] = implode(', ', $single);
                $hash = 'backMatters';
                $title = $single['title'];
                //$pdf_data .= "".$sr." . &nbsp<a href='#' id='".$hash.$key."' style='text-decoration: none; color: inherit;' onClick='reply_click(this.id)' ><span onclick='SendTitle(this.innerHTML)'>$title</span></a><br/>";
                $pdf_data .= "<a href='#' id='".$hash.$key."' style='text-decoration: none; color: inherit;' onClick='reply_click(this.id)' ><span style='font-size: 16px;' onclick='SendTitle(this.innerHTML)'>$title</span></a><br/>";
                $sr == $sr++;
            }
            //echo implode(', ', $implode);
        }

        $pdf_data .= "<div class='pagebreak'></div>";
            //print_r($implode);
            //$data['content_array'] = $implode;
            //var_dump($data['content_array']); die();
            $data['book_frontmatters'] = (array) $this->addfrontmatter_model->get_details_list($where_book_id, NULL);
            $data['book_chapters'] = (array) $this->addchapter_model->get_details_list($where_book_id, NULL);
            $data['book_parts'] = (array) $this->addpart_model->get_details_list($where_book_id, NULL);
            $data['book_backmatters'] = (array) $this->addbackmatter_model->get_details_list($where_book_id, NULL);

            //replace site url with ../../ in contents
            if(count($data['book_frontmatters']) > 0){
                for ($i=0; $i < count($data['book_frontmatters']); $i++) { 
                    $data['book_frontmatters'][$i]->content = str_replace('../../', $link, $data['book_frontmatters'][$i]->content);
                    $pdf_data .= $data['book_frontmatters'][$i]->content;
                    $pdf_data .= "<div class='pagebreak'></div>";
                }
        }
           
            if(count($data['book_chapters']) > 0){
                for ($i=0; $i < count($data['book_chapters']); $i++) {
                    $data['book_chapters'][$i]->content = str_replace('../../', $link, $data['book_chapters'][$i]->content);
                    $pdf_data .= $data['book_chapters'][$i]->content;
                   $pdf_data .= "<div class='pagebreak'></div>";
            }
        } 
         
            if(count($data['book_parts']) > 0){
                for ($i=0; $i < count($data['book_parts']); $i++) {
                    $data['book_parts'][$i]->content = str_replace('../../', $link, $data['book_parts'][$i]->content);
                    $pdf_data .= $data['book_parts'][$i]->content;
                    $pdf_data .= "<div class='pagebreak'></div>";
            }
        }  
            if(count($data['book_backmatters']) > 0){
                for ($i=0; $i < count($data['book_backmatters']); $i++) {
                    $data['book_backmatters'][$i]->content = str_replace('../../', $link, $data['book_backmatters'][$i]->content);
                    $pdf_data .= $data['book_backmatters'][$i]->content;
                    if($i < count($data['book_backmatters']) - 1) {
                    $pdf_data .= "<div class='pagebreak'></div>";
                }
            }
        }
            echo $pdf_data;
        
            /*$JSON = json_encode($data);
            print_r(json_decode($JSON, true));*/
        } else {
            echo "false";
        }
    }


     /*
     * delete book
     */
    public function delete_book($user_id=FALSE, $book_id=FALSE) {

        $whereData = array(
            'id' => $book_id,
            'user_id' => $user_id
            );

        $check_book = (array) $this->book_model->get_details(NULL, $whereData);

        if(count($check_book) > 0){

            $delete =  $this->book_model->delete($whereData, NULL);

            if($delete){
            
            if($check_book['cover_image'] != 'default-book.jpg') {
                $path = '../Aalto/uploads/'.$check_book['cover_image'];
                unlink($path);
            }

            $whereBook = array(
                'book_id' => $book_id,
            );

            $delete_FrontMatter =  $this->addfrontmatter_model->delete($whereBook, NULL);
            $delete_Chapter =  $this->addchapter_model->delete($whereBook, NULL);
            $delete_Part =  $this->addpart_model->delete($whereBook, NULL);
            $delete_BackMatter =  $this->addbackmatter_model->delete($whereBook, NULL);
            $delete_reference =  $this->reference_model->delete($whereBook, NULL);

                $data['msg'] = 'true';
            }else{
                $data['msg'] = 'false';
            }
            
        } else {
            $data['msg'] = 'false';
        }

        echo json_encode($data);
    }


    /*
     * Object tag 
     */
    public function tag(){
        $data = array(); 
                    
        //$tag_date = (int)$this->input->post('dts');
        //$tag_date = time();

        $posted_string = $this->input->post('data');

        $total_array = explode("#@@#", $posted_string);
        $count = count($total_array) - 1;

        foreach(explode('#@@#', $posted_string) as $key => $row_value) {

            if($key == $count) {
            break;
            }

            $single_val = explode("@#@", $row_value);

            $tagData = array(
            'book_id' => $single_val[0],
            'user_id' => $single_val[1],
            'model_id' => $single_val[2],
            'element_id' => $single_val[3],
            'tag_notes' => $single_val[4],
            'dts' => $single_val[5],
        );

        if(!empty($single_val[4])) {

            $check_objtag =  $this->objtag_model->get_details($tagData, NULL);

            if(count($check_objtag) > 0) {
                $data['msg'] = 'repeat';
            } else {
                $insert = $this->objtag_model->insert($tagData);
                if($insert) {
                    $data['msg'] = 'true';
                } else {
                    $data['msg'] = 'false';
                }
            }  
        }

        }

        echo $data['msg'];
        //echo json_encode($data);    
    }

    /*
     * get Object tag 
     */
    public function getTag(){
        $data = array(); 
                    
        $tagData = array(
            'book_id' => strip_tags($this->input->post('id')),
            'user_id' => strip_tags($this->input->post('user_id')),
            'model_id' => strip_tags($this->input->post('model_id')),
        );

        $data['tags'] = (array) $this->objtag_model->get_details_list(NULL, $tagData, 'element_id,tag_notes,dts');

            if(count($data['tags']) > 0){
                $data['tags'] = $data['tags'];
            }else{
                $data['tags'] = 'false';
            }
        echo json_encode($data);    
    }

    /*
     * sync Object tag 
     */
    public function sync_tag(){
        $data = array(); 

        $tag_date = (int)$this->input->post('dts');

        $tagData = array(
            'model_id' => strip_tags($this->input->post('model_id')),
        );

        $data['tags'] = (array) $this->objtag_model->get_details_list(NULL, $tagData, 'user_id,element_id,tag_notes,dts');
       
        $user_id = $this->input->post('id');
       
        /*foreach($data['tags'] as $key=>$value){
            if ($value->user_id == $user_id) {
            unset($data['tags'][$key]);
            }
        }*/
        
        foreach($data['tags'] as $key=>$value){
            if ($value->dts < $tag_date) {
            unset($data['tags'][$key]);
            }
        }

        if(count($data['tags']) > 0){
            $data['tags'] = $data['tags'];
        }else{
            $data['tags'] = 'false';
        }
        echo json_encode($data);    
    }


    /*
     * Notes 
     */
    public function notes(){
        $data = array(); 
                    
        $note_date = (int)$this->input->post('dts');
        //$note_date = date("Y-m-d H:i:s", $tag_date);

        $noteData = array(
            'book_id' => strip_tags($this->input->post('id')),
            'user_id' => strip_tags($this->input->post('user_id')),
            //'model_id' => strip_tags($this->input->post('model_id')),
            //'element_id' => strip_tags($this->input->post('element_id')),
            'page_no' => strip_tags($this->input->post('page_no')),
            'notes' => strip_tags($this->input->post('notes')),
            'dts' => $note_date,
        );

        if(!empty($this->input->post('notes'))) {
            $insert = $this->notes_model->insert($noteData);
        }

        if($insert){
            $data['msg'] = 'true';
        }else{
            $data['msg'] = 'false';
        }
        echo $data['msg'];
        //echo json_encode($data);    
    }


    /*
     * get Notes
     */
    public function sync_notes(){
        $data = array(); 

        //$note_date = (int)$this->input->post('dts');
        //$note_date = date("Y-m-d H:i:s", $note_date);

        $noteData = array(
            //'model_id' => strip_tags($this->input->post('model_id')),
            'book_id' => strip_tags($this->input->post('bookid')),
            //'user_id' => strip_tags($this->input->post('userid')),
        );

        //$data['notes'] = (array) $this->notes_model->get_details_list(NULL, $noteData, 'element_id,notes,dts');
        $data['notes'] = (array) $this->notes_model->get_details_list(NULL, $noteData, 'id,page_no,notes');

        /*foreach($data['notes'] as $key=>$value){
            
            if ($value->dts <= $note_date) {
            unset($data['notes'][$key]);
            }
        }*/

        if(count($data['notes']) > 0){
            $data['notes'] = $data['notes'];
        }else{
            $data['notes'] = 'false';
        }
        echo json_encode($data);    
    }


    /*
     * delete Notes
     */
    public function delete_notes($note_id) {
        $data = array(); 

        $whereId = array(
            'id' => $note_id,
        );

        $delete =  $this->notes_model->delete($whereId, NULL);

            if($delete){
                $data['msg'] = 'true';
            }else{
                $data['msg'] = 'false';
            }
        echo json_encode($data);    
    }
    

    /*
     * Chat 
     */
    public function chat(){
        $data = array();

        $chatData = array(
            'user_id' => strip_tags($this->input->post('user_id')),
            'user_name' => strip_tags($this->input->post('user_name')),
            'model_id' => strip_tags($this->input->post('model_id')),
            'message' => strip_tags($this->input->post('message')),
        );
        
        $insert = $this->chat_model->insert($chatData);

        if($insert){
            $data['msg'] = 'true';
        }else{
            $data['msg'] = 'false';
        }
        echo $data['msg'];
        //echo json_encode($data);    
    }


    public function upload_objtag_image(){
        $data = array();

        $tag_date = (int)$this->input->post('dts');
        if($this->input->post('action') === 'imageupload') {
        // for upload cover image
        $config['upload_path']   = './ObjTagImg/';
        $config['allowed_types'] = 'jpg|png';
        /*$config['max_size']      = 5000;
        $config['max_width']     = 4096;
        $config['max_height']    = 4096;*/

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('file')) {
            $data['msg'] = 'false';
        } else {

            $data = $this->upload->data();  
            $config['image_library'] = 'gd2';  
            $config['source_image'] = './ObjTagImg/'.$data["file_name"];  
            $config['create_thumb'] = FALSE;  
            $config['maintain_ratio'] = FALSE;  
            $config['quality'] = '90%';  
            $config['width'] = 200;  
            $config['height'] = 200;  
            $config['new_image'] = './ObjTagImg/'.$data["file_name"];  
            $this->load->library('image_lib', $config);  
            $this->image_lib->resize(); 

            $file_name =  $data['file_name'];;
            $finel_file_name =  '__image__'.$file_name.'__image'; 

            $tagData = array(
                'user_id' => strip_tags($this->input->post('id')),
                'book_id' => strip_tags($this->input->post('book_id')),
                'model_id' => strip_tags($this->input->post('model_id')),
                'element_id' => strip_tags($this->input->post('element_id')),
                'tag_notes' => $finel_file_name,
                'dts' => $tag_date,
            );

            $insert = $this->objtag_model->insert($tagData);

            if($insert) {
                $data['msg'] = $finel_file_name;
            } else {
                $data['msg'] = 'false';
            }
        }
    } else {
        $data['msg'] = 'false'; 
    }
        echo $data['msg'];
    }


    /*
     * quizapp login
     */
    public function quizlogin() {
        $data = array();

        $con = array(
            'username' => $this->input->post('username'),
            'password' => md5($this->input->post('password')),
            //'status' => 'Active',
        );

        $checkLogin = (array) $this->user_model->get_details(NULL, $con, 'id,status');
        
        if(count($checkLogin) > 0) {
            if($checkLogin['status'] == 'Active') {
            //user details
            $data['user'] = $checkLogin;
            echo json_encode($data);
            } else {
                echo 'inactive';
            }
        } else {
            echo 'false';
        }
    }

    /*
     * get Question 
    */
    public function getQuestion() {
        $data = array(); 

        $quiz_id = array(
            'QuizId' => $this->input->post('SelectedQuizID'),
        );
        $dbQue = (array) $this->quiz_model->get_details_list(NULL, $quiz_id, 'questions');
        if($dbQue) {
            $queArray = explode(',', $dbQue[0]->questions);

            $queNo = $this->input->post('Question_no');
            
            if($queNo > count($queArray)-1) {
                $data['que'] = 'false';
            } else {
                $data['que'] = (array) $this->queans_model->get_que_data($queArray[$queNo]);

                if(count($data['que']) > 0){
                    $single_val = explode("____", $data['que']['Question']);
                    $data['que']['blanks'] = count($single_val) -1;
                    $data['que']['sequence'] = $queNo +1;
                    
                    $data['que'] = $data['que'];
                }else{
                    $data['que'] = 'false';
                }
            }
        } else {
            $data['que'] = 'false';
        }
        
        echo json_encode($data);    
    }

    /*
     * get Quizzes 
    */
    public function getQuizzes() {
        if(!empty($this->input->post('id'))) {
            $finelData = array(); 
            $user = $this->input->post('id');
            //$user = 9;

            $db_data = (array) $this->quiz_model->get_details_list(NULL, NULL);
            
            $db_quiz_data = array();
            $db_attempted_data = array();

            foreach ($db_data as $key => $value) {
                $Attempted = 0;
                $checkQueCount = 0;

                $MinArray = explode(':', $value->Time);
                $Totalsec = (($MinArray[0] * 60) * 60) + ($MinArray[1] * 60);
                $value->Time = $Totalsec;

                $userArray = explode(',', $value->assigned_users);
                if(in_array($user, $userArray)) {

                    $queArray = explode(',', $value->questions);
                    $checkQueCount = count($queArray);

                    //$checkQueCount = count((array) $this->queans_model->get_details_list(array('QuizId' =>$value->QuizId), NULL));

                    if($checkQueCount == 5 || $checkQueCount > 5) {
                        $db_quiz_data[] = $checkQueCount;

                        $chkAttempted = array(
                            'quiz_id' => $value->QuizId,
                            'user_id' => $user,
                        );

                        $Attempted = (array) $this->quizRecord_model->get_details_list($chkAttempted, NULL);

                        if(count($Attempted) > 0) {
                            $db_attempted_data[] = "true";
                        } else {
                            $db_attempted_data[] = "false";
                        }

                        $arrange = array_values($db_data);
                    } else {
                        //unset array and arrange key sequestion
                        unset($db_data[$key]);
                        $arrange = array_values($db_data);
                    }
                    
                } else {
                    //unset array and arrange key sequestion
                    unset($db_data[$key]);
                    $arrange = array_values($db_data);
                }
            }
            $myArray = json_decode(json_encode($arrange), true); 
        
            for ($i=0; $i < count($db_quiz_data) ; $i++) { 
                $myArray[$i]['QuestionCount'] = $db_quiz_data[$i];
                $myArray[$i]['Attempted'] = $db_attempted_data[$i];
            }

            $data['count_quizzes'] = count($db_data);
            $data['quizzes'] = $myArray;

            $finelData['QuizData'] = $data;
        } else {
            $finelData['QuizData'] = 'false';
        }

        echo json_encode($finelData);    
    }


    /*
     * get Quiz Answers
    */
    public function SubmitQData() {
        $data = array();
        $posted_string = $this->input->post('BULK');

        $total_array = explode("#@@#", $posted_string);
        $count = count($total_array) - 1;

        foreach(explode('#@@#', $posted_string) as $key => $row_value) {

            if($key == $count) {
                break;
            }

            $single_val = explode("@#@", $row_value);

            $answerData = array(
                'user_id' => $single_val[0],
                'quiz_id' => $single_val[1],
                'question_id' => $single_val[2],
                'answer' => $single_val[3]
            );
            //var_dump($answerData);
            $insert = $this->quizRecord_model->insert($answerData);
            if($insert) {
                $data['msg'] = 'true';
            } else {
                $data['msg'] = 'false';
            }
        }
        echo $data['msg'];
    }


    /*
     * get Report 
    */
    public function getReport() {
        $data = array(); 

        $quiz_id = array(
            'QuizId' => $this->input->post('SelectedQuizID'),
        );
        $dbQue = (array) $this->quiz_model->get_details_list(NULL, $quiz_id, 'questions');

        if($dbQue) {
            //sum of marks per quiz
            $queArray = explode(',', $dbQue[0]->questions);
            $db_marksSum = (array) $this->queans_model->get_marks_sum($queArray);
        
            //sum of obtained marks per quiz
            $whereArr = array(
                'quiz_id' => $this->input->post('SelectedQuizID'),
                'user_id' => $this->input->post('id'),
            );
            
            $db_obtmarksSum = (array) $this->quizRecord_model->get_obtainedmarks_sum($whereArr);
            
            if($db_obtmarksSum['obtained_marks'] == NULL) {
                $obtmarksSum = 'null';
            } else {
                $obtmarksSum = $db_obtmarksSum['obtained_marks'];
            }
            $data['report']['marks'] = $db_marksSum[0]->Marks;
            $data['report']['obtmarks'] = $obtmarksSum;

        } else {
            $data['report'] = 'false';
        }
        
        echo json_encode($data);    
    }

}
?>