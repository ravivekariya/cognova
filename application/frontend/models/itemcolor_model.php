<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class itemcolor_model extends Data {

	public $searchCriteria;
	function __construct() 
	{
        parent::__construct();
       // $this->tbl = 'product_master';
	   // $this->tbl = 'product_category';
    }
	
	function getItemColor()
	{
		$searchCriteria = array();
		$searchCriteria = $this->searchCriteria;
		
		$selectField = "*";
		if(isset($searchCriteria['selectField']) && $searchCriteria['selectField'] != "")
		{
			$selectField = 	$searchCriteria['selectField'];
		}
		
		$whereClaue = "WHERE 1=1 ";

		// By item color id
		if(isset($searchCriteria['item_color_id']) && $searchCriteria['item_color_id'] != "")
		{
			$whereClaue .= 	" AND item_color_id = ".$searchCriteria['item_color_id']." ";
		}
		
		// By item color name
		if(isset($searchCriteria['item_color_name']) && $searchCriteria['item_color_name'] != "")
		{
			$whereClaue .= 	" AND item_color_name = '".$searchCriteria['item_color_name']."' ";
		}
		
		// By item color code
		if(isset($searchCriteria['item_color_code']) && $searchCriteria['item_color_code'] != "")
		{
			$whereClaue .= 	" AND item_color_code='".$searchCriteria['item_color_code']."' ";
		}
		
		// Not In
		if(isset($searchCriteria['not_id']) && $searchCriteria['not_id'] != "")
		{
			$whereClaue .= 	" AND item_color_id !=".$searchCriteria['not_id']." ";
		}
		
		$orderField = " item_color_name";
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
						item_color_master ".$whereClaue." ORDER BY ".$orderField." ".$orderDir."";
		
		$result     = $this->db->query($sqlQuery);
		$rsData     = $result->result_array();
		return $rsData;
		
		
	}
}