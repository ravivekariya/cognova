<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class itemthickness_model extends Data {

	public $searchCriteria;
	function __construct() 
	{
        parent::__construct();
       // $this->tbl = 'product_master';
	   // $this->tbl = 'product_category';
    }
	
	function getItemThickness()
	{
		$searchCriteria = array();
		$searchCriteria = $this->searchCriteria;
		
		$selectField = "*";
		if(isset($searchCriteria['selectField']) && $searchCriteria['selectField'] != "")
		{
			$selectField = 	$searchCriteria['selectField'];
		}
		
		$whereClaue = "WHERE 1=1 ";

		// By item thickness id
		if(isset($searchCriteria['item_thk_id']) && $searchCriteria['item_thk_id'] != "")
		{
			$whereClaue .= 	" AND item_thk_id = ".$searchCriteria['item_thk_id']." ";
		}
		
		// By item thickness name
		if(isset($searchCriteria['item_thk_name']) && $searchCriteria['item_thk_name'] != "")
		{
			$whereClaue .= 	" AND item_thk_name = '".$searchCriteria['item_thk_name']."' ";
		}
		
		// By item thickness code
		if(isset($searchCriteria['item_thk_code']) && $searchCriteria['item_thk_code'] != "")
		{
			$whereClaue .= 	" AND item_thk_code='".$searchCriteria['item_thk_code']."' ";
		}

		// By item thickness code
		if(isset($searchCriteria['item_thickness']) && $searchCriteria['item_thickness'] != "")
		{
			$whereClaue .= 	" AND item_thickness=".$searchCriteria['item_thickness']." ";
		}
		
		// Not In
		if(isset($searchCriteria['not_id']) && $searchCriteria['not_id'] != "")
		{
			$whereClaue .= 	" AND item_thk_id !=".$searchCriteria['not_id']." ";
		}
		
		$orderField = " item_thk_name";
		$orderDir = " ASC";
		
		// Set Order Field
		if(isset($searchCriteria['orderField']) && $searchCriteria['orderField'] != "")
		{
			$orderField = $searchCriteria['orderField'];
		}
		
		// Set Order Field
		if(isset($searchCriteria['orderDir']) && $searchCriteria['orderDir'] != "")
		{
			$orderDir = $searchCriteria['orderDir'];
		}
		
		$sqlQuery = "SELECT 
						".$selectField."
					FROM 
						item_thickness_master ".$whereClaue." ORDER BY ".$orderField." ".$orderDir."";
		
		$result     = $this->db->query($sqlQuery);
		$rsData     = $result->result_array();
		return $rsData;
		
		
	}
}