<?php
class series_ct_model extends CI_Model{
	
	function __construct(){
		parent::__construct();
	}	
	
	function insert_new($data,$id,$protocol){
		$sql = 'SELECT * FROM series_ct WHERE `Series`=? and `Protocol ID`=?';
		$params = array($id,$protocol);
		$status = 0;//0: new protocol; 1: modified; 2:no change
		
        $query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
			$status=2;
			$arrayobject = new ArrayObject($data);

			for($iterator = $arrayobject->getIterator();
				$iterator->valid();
				$iterator->next()) {
				if ($iterator->current()!=$query->result_array()[0][$iterator->key()]){
					$status=1;//$iterator->current()."***".$query->result_array()[0][$iterator->key()];
					break;
				}
			}
			if ($status==1){	
				$this->backup($query->result_array()[0]);
				$this->delete_one($id,$protocol);
				$this->insert_protocol($data);
				
				/*$this->db->insert('series_ct_backup',$query->result_array()[0]);
				$this->db->where('Series', $id);
				$this->db->where('Protocol ID', $protocol);
				$this->db->update('series_ct', $data);        */
			}
        }
        else {            			
			$this->insert_protocol($data); 
        }		
		
		return $status;
	}	
	
	private function insert_protocol($data){		
		$sql = 'INSERT INTO `series_ct` VALUES(?';					
		$count = count($data);			
		for ($i = 1; $i < $count; $i++) {   
			$sql=$sql.", ? ";
		}
		$sql=$sql.")";
		
		$params = array();
		
		foreach($data as $key=>$val){ 			
			array_push($params,$val);
		}
		
		$query = $this->db->query($sql,$params);
	}
	function get_list_by_number($protocol_number){
		$sql = 'SELECT * FROM series_ct WHERE `Protocol ID`=? ORDER BY `Series`';
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
	private function backup($data){
		$sql = 'INSERT INTO `series_ct_backup` VALUES(?';					
		$count = count($data);	
		
		for ($i = 1; $i < $count; $i++) {   
			$sql=$sql.", ? ";
		}
		$sql=$sql.")";
		
		$params = array();
		
		foreach($data as $key=>$val){ 			
			array_push($params,$val);
		}
		
		$query = $this->db->query($sql,$params);
	}
	function delete_all(){
		$sql = 'SELECT * FROM series_ct';		
		$query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
			foreach($query->result_array() as $data){
				$this->backup($data);					
			}
		}			
		
		$sql = 'DELETE FROM series_ct WHERE 1';
		$query = $this->db->query($sql);		
	}	
	
	function delete_by_number($protocol_number){
		$sql = 'SELECT * FROM series_ct WHERE `Protocol ID`=?';
		$params = array($protocol_number);
		$query = $this->db->query($sql, $params);
        if ($query->num_rows() > 0) {
			foreach($query->result_array() as $data){
				$this->backup($data);					
			}
		}
		
		$this->delete_only($protocol_number);
	}	
	private function delete_only($protocol_number){
		$sql = 'DELETE FROM series_ct WHERE `Protocol ID`=?';
		$params = array($protocol_number);		
        $query = $this->db->query($sql, $params);
	}
	
	function delete_one($id,$protocol_number){
		$sql = 'DELETE FROM series_ct WHERE `Series`=? and `Protocol ID`=?';
		$params = array($id,$protocol_number);		
        $query = $this->db->query($sql, $params);
	}		
}