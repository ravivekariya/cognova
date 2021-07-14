<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class product_model extends Data {

	public $searchCriteria;
	function __construct() 
	{
        parent::__construct();
       // $this->tbl = 'product_master';
	   // $this->tbl = 'product_category';
    }
	
	function getProduct()
	{
		$searchCriteria = array();
		$searchCriteria = $this->searchCriteria;
		
		$selectField = "pm.*,pc.cat_name,vm.vendor_name";
		if(isset($searchCriteria['selectField']) && $searchCriteria['selectField'] != "")
		{
			$selectField = 	$searchCriteria['selectField'];
		}
		
		$whereClaue = "WHERE 1=1 ";
		
		// By Product id
		if(isset($searchCriteria['prod_id']) && $searchCriteria['prod_id'] != "")
		{
			$whereClaue .= 	" AND pm.prod_id IN (".$searchCriteria['prod_id'].") ";
		}

		// By Product Type (E.g product and product component)
		if(isset($searchCriteria['prod_type']) && $searchCriteria['prod_type'] != "")
		{
			$whereClaue .= 	" AND pm.prod_type = '".$searchCriteria['prod_type']."' ";
		}
		
		// By Category
		if(isset($searchCriteria['cat_id']) && $searchCriteria['cat_id'] != "")
		{
			$whereClaue .= 	" AND pm.prod_categoty=".$searchCriteria['cat_id']." ";
		}

        // By Vendor
        if(isset($searchCriteria['vendor_id']) && $searchCriteria['vendor_id'] != "")
        {
            $whereClaue .= 	" AND pm.vendor_id=".$searchCriteria['vendor_id']." ";
        }
		
		// By Status
		if(isset($searchCriteria['status']) && $searchCriteria['status'] != "")
		{
			$whereClaue .= 	" AND pm.status='".$searchCriteria['status']."' ";
		}
		
		// By Product name
		if(isset($searchCriteria['prod_name']) && $searchCriteria['prod_name'] != "")
		{
			$whereClaue .= 	" AND pm.prod_name='".$searchCriteria['prod_name']."' ";
		}
		
		// Not In
		if(isset($searchCriteria['not_id']) && $searchCriteria['not_id'] != "")
		{
			$whereClaue .= 	" AND pm.prod_id !=".$searchCriteria['not_id']." ";
		}
		
		$orderField = " pm.prod_name";
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
						product_master AS pm 
                    LEFT JOIN product_category AS pc ON pm.prod_categoty=pc.cat_id
                    LEFT JOIN vendor_master AS vm ON pm.vendor_id=vm.vendor_id 
                    ".$whereClaue." ORDER BY ".$orderField." ".$orderDir."";
						
		//echo $sqlQuery; exit;
		
		$result     = $this->db->query($sqlQuery);
		$rsData     = $result->result_array();
		return $rsData;
		
		
	}
	
	function getCategory()
	{
		$searchCriteria = array();
		$searchCriteria = $this->searchCriteria;
		
		$selectField = "*";
		if(isset($searchCriteria['selectField']) && $searchCriteria['selectField'] != "")
		{
			$selectField = 	$searchCriteria['selectField'];
		}
		
		$whereClaue = "WHERE 1=1 ";
		
		// By Category id
		if(isset($searchCriteria['category_id']) && $searchCriteria['category_id'] != "")
		{
			$whereClaue .= 	" AND cat_id=".$searchCriteria['category_id']." ";
		}
		
		// By Category name
		if(isset($searchCriteria['category_name']) && $searchCriteria['category_name'] != "")
		{
			$whereClaue .= 	" AND cat_name='".$searchCriteria['category_name']."' ";
		}
		
		// Not In
		if(isset($searchCriteria['not_id']) && $searchCriteria['not_id'] != "")
		{
			$whereClaue .= 	" AND cat_id !=".$searchCriteria['not_id']." ";
		}
		
		$orderField = " cat_name";
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
						product_category ".$whereClaue." ORDER BY ".$orderField." ".$orderDir."";
		
		$result     = $this->db->query($sqlQuery);
		$rsData     = $result->result_array();
		return $rsData;	
	}
}