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
	function check($data)
	{			
		$arr=explode("-", $data);
		$count=count($arr)-1;
		$sql = "SELECT * from category WHERE name=?";
		$query = $this->db->query($sql,$data);
		
        if ($query->num_rows() == 0) {
			$sql = "INSERT INTO category(`name`,`show_name`,`level`) VALUES (?,?,?)";
			
			$show_name=$arr[$count];
			
			$this->db->query($sql,array($data,$show_name,$count));
			
			$tmp = $arr[0];
			for ($i=0;$i<$count;$i++){			
				$sql = "SELECT * from category WHERE name=?";
				$q = $this->db->query($sql,$tmp);
				if ($q->num_rows()==0){
					$sql = "INSERT INTO category(`name`,`show_name`) VALUES (?,?)";
					
					$show_name=$arr[$i];
					$this->db->query($sql,array($tmp,$show_name,$i));
				}
				$tmp=$tmp+'-'+$arr[$i];
			}
            return null;
        }
	}	
}