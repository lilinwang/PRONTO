<?php
class series_ct_model extends CI_Model{
	
	function __construct(){
		parent::__construct();
	}	
	
	function insert_new($data,$id){
		$sql = 'SELECT * FROM series_ct WHERE `Series`=?';
		$params = array($id);
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
				$this->db->insert('series_ct_backup',$query->result_array()[0]);
				$this->db->where('Series', $id);
				$this->db->update('series_ct', $data);        
			}
        }
        else {            
			$this->db->insert('series_ct', $data);
        }		
		
		return $status;
	}	
	
	function get_list_by_number($protocol_number){
		$sql = 'SELECT * FROM series_ct WHERE `Protocol ID`=?';
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
	function delete_by_number($protocol_number){
		$sql = 'DELETE FROM series_ct WHERE `Protocol ID`=?';
		$params = array($protocol_number);
		
        $query = $this->db->query($sql, $params);
	}
}