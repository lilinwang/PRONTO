<?php
class protocol_model extends CI_Model{
	
	function __construct(){
		parent::__construct();
	}
	private function record($protocol_number,$protocol_name,$user_name,$status){
		$sql='INSERT INTO record (`protocol_number`, `protocol_name`, `created_by`, `status`) VALUES (?,?,?,?);';
		//0: new protocol; 1: modified; 2:no change
		$new_status=["New protocol","Modified","No change","Deleted"];
		$params = array($protocol_number,$protocol_name,$user_name,$new_status[$status]);
		$query = $this->db->query($sql, $params);
	}
	
	function get_list_by_category($category)
	{
		$sql = "SELECT * FROM protocol WHERE protocol_category LIKE ? ORDER BY protocol_name";
		$params = array('%'.$category.'%');
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
			$result=$query->result_array();
			return $result;            
        }
        else {
            return null;
        }
	}
	function get_all_protocols()
	{
		$sql = "SELECT * FROM protocol ORDER BY protocol_name";
		
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
			$result=$query->result_array();
			return $result;            
        }
        else {
            return null;
        }
	}
	function get_list_by_keywords($content)
	{	$content= explode(" ", $content);
		//echo $content;
		$ids = implode(" +",$content); 
		
		//echo $ids;
		$sql="SELECT * FROM `protocol` WHERE MATCH (`protocol_name`, `indication`,`protocol_category`) AGAINST('+".$ids."' IN BOOLEAN MODE) ORDER BY protocol_name;";
		//echo $sql;
		//$sql = "SELECT * FROM protocol WHERE bodypart_full IN ('".$ids."')";
		//$params = array($ids);
		//$params = array($content);
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
			$result=$query->result_array();
			return $result;            
        }
        else {
            return null;
        }
	}	
	
	function get_by_number($protocol_number){
		$sql = 'SELECT * FROM protocol WHERE protocol_number like ?';
		$params = array($protocol_number);
		
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
			$result=$query->result_array();
			return $result;            
        }
        else {
            return null;
        }
	}
	function get_report_description_by_name($name){
		$sql = 'SELECT report,description FROM protocol WHERE protocol_name like ?';
		$params = array($name);
		
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
			$result=$query->result_array();
			return $result;            
        }
        else {
            return null;
        }
	}
	function get_report_description_by_number($number){
		$sql = 'SELECT report,description FROM protocol WHERE protocol_number like ?';
		$params = array($number);
		
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
			$result=$query->result_array();
			return $result;            
        }
        else {
            return null;
        }
	}
	function insert_new($data,$id,$user_name){
		$sql = 'SELECT * FROM protocol WHERE protocol_number=?';
		$params = array($id);
		$status = 0;//0: new protocol; 1: modified; 2:no change; 3:delete
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
			$status=2;
			$arrayobject = new ArrayObject($data);

			for($iterator = $arrayobject->getIterator();
				$iterator->valid();
				$iterator->next()) {
				if ($iterator->current()!=$query->result_array()[0][$iterator->key()]){
					$status=1;
					break;
				}
			}
			
			if ($status==1){				
				$this->db->insert('protocol_backup',$query->result_array()[0]);
				$this->db->where('protocol_number', $id);
				$this->db->update('protocol', $data);  
			}					
        }
        else {            
			$this->db->insert('protocol', $data);	
			
        }		
		
		$this->record($id,$data['protocol_name'],$user_name,$status);
		return $status;
	}	
	
	function delete_by_number($protocol_number,$user_name){
		$sql = 'SELECT * FROM protocol WHERE protocol_number=?';
		$params = array($protocol_number);
		$query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
			$this->db->insert('protocol_backup',$query->result_array()[0]);//backup
			$this->record($protocol_number,$query->result_array()[0]['protocol_name'],$user_name,3);//record
		}
		
		$sql = 'DELETE FROM protocol WHERE protocol_number=?';
		$params = array($protocol_number);
		
        $query = $this->db->query($sql, $params);
	}	
	
}