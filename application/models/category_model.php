<?php
class category_model extends CI_Model{
	
	function __construct(){
		parent::__construct();
	}
	
	function get_all_list()
	{				
		//$sql = "SELECT GROUP_CONCAT(parent.name ORDER BY parent.lft ASC SEPARATOR '|') as name FROM category AS node CROSS JOIN category AS parent WHERE node.lft BETWEEN parent.lft AND parent.rgt GROUP by node.id ORDER BY node.lft;";
        $sql="SELECT * FROM category ORDER BY name";
		$query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
			$result=$query->result_array();
			return $result;            
        }
        else {
            return null;
        }
	}		
	
}