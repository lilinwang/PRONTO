<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Detailed_ajax extends CI_Controller {
	function __construct() {
        parent::__construct();
		$this->load->model('series_ct_model');
		$this->load->model('series_mr_model');
        $this->load->library('csvimport');
		$this->load->library('session');
    }
	
    function index()
    {
        exit('Access denied');
    }
	
	function get_protocol(){	
		$data = json_decode(file_get_contents("php://input"));
		
		$protocol_number = mysql_real_escape_string($data->number);			
		
		$this->load->model('protocol_model');				
		$result= $this->protocol_model->get_by_number($protocol_number);							
		echo json_encode($result);		
	}
	function get_series(){	
		$data = json_decode(file_get_contents("php://input"));
		
		$protocol_number = mysql_real_escape_string($data->number);			
		$category=mysql_real_escape_string($data->category);	
		if ($category[0]=='M'){
			$this->load->model('series_mr_model');				
			$result= $this->series_mr_model->get_list_by_number($protocol_number);	
		}else{
			$this->load->model('series_ct_model');				
			$result= $this->series_ct_model->get_list_by_number($protocol_number);	
		}
								
		echo json_encode($result);		
	}
	function delete(){
		$data = json_decode(file_get_contents("php://input"));
		
		$user_name=$this->session->userdata('user_name');
		$protocol_number = mysql_real_escape_string($data->number);					
		$password = mysql_real_escape_string($data->password);	
		$category=mysql_real_escape_string($data->category);	
		if ($password=="cornellradiology"){
			$this->load->model('protocol_model');				
			$this->protocol_model->delete_by_number($protocol_number,$user_name);							
			if ($category[0]=='M'){
				$this->load->model('series_mr_model');				
				$this->series_mr_model->delete_by_number($protocol_number);	
			}else{
				$this->load->model('series_ct_model');				
				$this->series_ct_model->delete_by_number($protocol_number);	
			}
									
		
			echo 1;
		}else{
			echo 0;
		}
		
	}
	
}