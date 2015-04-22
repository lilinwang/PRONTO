<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mylist extends CI_Controller {

	function __construct(){		
		parent::__construct();
        header('Content-Type: text/html;charset=utf-8');
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('session');
		
	}
	
	public function index() {				
		$this->load->view('onepage');
	}	
}