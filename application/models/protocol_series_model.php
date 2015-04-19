<?php
class protocol_series_model extends CI_Model{
	
	function __construct(){
		parent::__construct();
	}
	function get_export($category_full,$modality)
	{		
		$sql;
		if ($modality=="MR"){
			$sql = 'SELECT * FROM protocol as p inner join series_mr as s on p.`Protocol ID`=s.`Protocol ID` WHERE (`Protocol Category` LIKE ? ';
		}else{
			$sql = 'SELECT * FROM protocol as p inner join series_ct as s on p.`Protocol ID`=s.`Protocol ID` WHERE (`Protocol Category` LIKE ? ';
		}
		
		$count = count($category_full);	
		
		for ($i = 1; $i < $count; $i++) {   
			$sql=$sql."OR `Protocol Category` LIKE ? ";
		}
		$sql=$sql.")";
		
		$params = array();
		
		foreach ($category_full as $body) {   			
			array_push($params,$body.'%');
		}
		//var_dump( $params);
        $query = $this->db->query($sql,$params);
        if ($query->num_rows() > 0) {
			$result=$query->result_array();
			return $result;            
        }
        else {
            return null;
        }
	}
}
?>