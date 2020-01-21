<?php
class Url_3DModel extends CI_Controller {

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

	    $this->load->model('book_model');

        $this->load->model('url_3DModel_model');

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

if($this->input->post('addrefSubmit')){

            $config['upload_path']   = './3DModel/'; 

            $config['allowed_types'] = '*'; 


            $this->load->library('upload', $config);

            if ( ! $this->upload->do_upload('3dmodel')) {
                    
                $file_name = '';

            } else {

                $upload_data = $this->upload->data(); 

                $file_name =   $upload_data['file_name'];

            }

            $asdf = explode(".",$file_name);
            $last_arrayvalue = end($asdf);
            if($last_arrayvalue != 'ifc') {
                $fileDelete = '../Aalto/3DModel/'.$file_name;
                unlink($fileDelete);
                $this->session->set_flashdata('error_msg', 'File type is not supported');
                redirect('url_3DModel');
            }

            $check_projectName = array(
                'description' => $this->input->post('desc'),
            );

            $data['fetch_project'] = (array) $this->url_3DModel_model->get_details_list($check_projectName, NULL);

            if(count($data['fetch_project']) > 0) {
                $fileDelete = '../Aalto/3DModel/'.$file_name;
                unlink($fileDelete);
                $this->session->set_flashdata('error_msg', 'Project Name exists on bimserver');
                redirect('url_3DModel');
            }

            $check_url = array(
                'user_id' => $this->session->userdata('userId'),
                'file' => $file_name,
            );

            $data['fetch_data'] = (array) $this->url_3DModel_model->get_details_list($check_url, NULL);

            if(count($data['fetch_data']) > 0) {
                $this->session->set_flashdata('error_msg', '3D Model Already Exists');
                redirect('url_3DModel');
            }

            $file_url =  base_url().'3DModel/'.$file_name; 
            //$file_url =  'http://inbookmode.aalto.fi/build/2222.ifc';
            $data_curl = array("projectName" => $this->input->post('desc'), "url" => $file_url);
            $data_string = json_encode($data_curl);                                                                                   
                                                                                                                                 
            $ch = curl_init('http://inbookmode.aalto.fi:3000/uploadifc');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                         
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                        
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                     
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                 
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
            );                                                                                                             
            $result_curl = curl_exec($ch);

            // also get the error and response code
            $errors = curl_error($ch);
            $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if($response == 200 && $errors =='')
            {
                $respResult = json_decode($result_curl);
                if($respResult->message == 'Project name must be unique') {
                    $this->session->set_flashdata('error_msg', 'Project Name exists on bimserver');
                    redirect('url_3DModel');
                }
                $poid = $respResult->projectId;
                // for insert data into table
                
                $AddData = array(
                    'user_id' => $this->session->userdata('userId'),
                    'description' => $this->input->post('desc'),
                    'file' => $file_name,
                    'poid' => $poid,
                    'dimension' => $this->input->post('dimension'),
                );
                //var_dump($AddData); die();
                $insert = $this->url_3DModel_model->insert($AddData);
                
                if($insert) {
                    //Activity log for profile update
                    $activity = '3D Model Added';
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
                    $this->session->set_flashdata('success', '3D Model Uploaded successfully');
                    redirect('url_3DModel');
                }
            } else {
                $this->session->set_flashdata('error_msg', 'Something went wrong please try again aftr some time');
                redirect('url_3DModel');
            }
            curl_close($ch);
        }

	/** fetch user specific data from db and for admin also
        **************************************/
        $user_id = array(
                'user_id' => $this->session->userdata('userId'),
            );

        $db_data = (array) $this->url_3DModel_model->get_details_list($user_id, NULL);

        //var_dump($db_data); die();

        $data['fetch_data'] = $db_data;

    	$data['common_details'] = $this->commonView;

    	$this->load->view('users/url_3DModel', $data);

    }


    public function delete_3DModel($id=FALSE){

        $whereData = array(

            'id' => $id,

            );

        $check_ref = (array) $this->url_3DModel_model->get_details($whereData, NULL);

        $delete =  $this->url_3DModel_model->delete($whereData, NULL);

        if($delete) {
            
            $path = '../Aalto/3DModel/'.$check_ref['file'];
            unlink($path);
            /*$fbxDelete = '../Aalto/3DModel/'.str_replace(".ifc",".fbx",$check_ref['file']);
            unlink($fbxDelete);*/

            //Activity log for profile update

            $activity = '3D Model Deleted';

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

            redirect('url_3DModel');

        }
    }

    /*public function convert_3DModel($id=FALSE) {
        $whereData = array(

            'id' => $id,

            );

        $check_ref = (array) $this->url_3DModel_model->get_details($whereData, NULL);
        
        $file_name = $check_ref['file'];
        
        //exec('C:/xampp/htdocs/IOT/trunk/test-ifc-obj/IfcConvert.exe  "C:/xampp/htdocs/IOT/trunk/test-ifc-obj/2222.ifc"'); die();
        $forConvertObj = $_SERVER['DOCUMENT_ROOT'].'/Aalto/3DModel/'.$file_name;
        $exepathObj = $_SERVER['DOCUMENT_ROOT'].'/Aalto/3DModel/IfcConvert.exe';
        $objconvert = exec($exepathObj.'  '.$forConvertObj.' "--use-element-guids"');
        $objfilename = str_replace(".ifc",".obj",$file_name);   
        if($objconvert) {
            //exec('C:/xampp/htdocs/Aalto/3DModel/FbxConverter.exe  "C:/xampp/htdocs/Aalto/3DModel/2222.obj" "2222.fbx"');
            $forConvertFbx = $_SERVER['DOCUMENT_ROOT'].'/Aalto/3DModel/'.$objfilename;
            $exepathFbx = $_SERVER['DOCUMENT_ROOT'].'/Aalto/3DModel/FbxConverter.exe';
            $fbxfilename = str_replace(".ifc",".fbx",$file_name);
            $fbxconvert = exec($exepathFbx.'  '.$forConvertFbx.' '.$_SERVER['DOCUMENT_ROOT'].'/Aalto/3DModel/'.$fbxfilename);
            var_dump($forConvertFbx); var_dump($exepathFbx); var_dump($fbxconvert); die();
            $updateData = array(
                'fbx_file' => $fbxfilename,
            );

            $update =  $this->url_3DModel_model->update($updateData, $whereData, NULL);

            if($update) {
                $ifcfileDelete = '../Aalto/3DModel/'.$file_name;
                unlink($ifcfileDelete);
                $objfileDelete = '../Aalto/3DModel/'.$objfilename;
                unlink($objfileDelete);
                $mtlfileDelete = '../Aalto/3DModel/'.str_replace(".ifc",".mtl",$file_name);
                unlink($mtlfileDelete);
            }
            
        }   
            $this->session->set_flashdata('success', '3D Model Converted successfully');

            redirect('url_3DModel');
    }*/
}

?>