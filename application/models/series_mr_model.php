<?php
class series_mr_model extends CI_Model{
	
	function __construct(){
		parent::__construct();
	}	
	
	function insert_new($data,$id,$protocol){
		$sql = 'SELECT * FROM series_mr WHERE `Series`=? and `Protocol ID`=?';
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
				$this->db->insert('series_mr_backup',$query->result_array()[0]);
				$this->db->where('Series', $id);
				$this->db->where('Protocol ID', $protocol);
				$this->db->update('series_mr', $data);        
			}
        }
        else {            
			//$this->db->insert('series_mr', $data);
			/*foreach($data as $key=>$val)
			{
				$this->db->set($key, $val);
			}
			$this->db->insert('series_mr');*/
			$sql = 'INSERT INTO `series_mr` VALUES(?';			
		
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
		
		return $status;
	}	
	
	function get_list_by_number($protocol_number){
		$sql = 'SELECT * FROM series_mr WHERE `Protocol ID`=?';
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
		$sql = 'DELETE FROM series_mr WHERE `Protocol ID`=?';
		$params = array($protocol_number);
		
        $query = $this->db->query($sql, $params);
	}
}