<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Process_model extends Data {

    public $searchCriteria;
    function __construct()
    {
        parent::__construct();
        $this->tbl = 'process';
    }

    function getDetails()
    {
        $searchCriteria = array();
        $searchCriteria = $this->searchCriteria;

        $selectField = "pm.*";
        if(isset($searchCriteria['selectField']) && $searchCriteria['selectField'] != "")
        {
            $selectField = 	$searchCriteria['selectField'];
        }

        $whereClaue = "WHERE 1=1 ";

        // By Product id
        if(isset($searchCriteria['id']) && $searchCriteria['id'] != "")
        {
            $whereClaue .= 	" AND pm.id IN (".$searchCriteria['id'].") ";
        }

        // By Status
        if(isset($searchCriteria['status']) && $searchCriteria['status'] != "")
        {
            $whereClaue .= 	" AND pm.status='".$searchCriteria['status']."' ";
        }

        // By Product name
        if(isset($searchCriteria['name']) && $searchCriteria['name'] != "")
        {
            $whereClaue .= 	" AND pm.name='".$searchCriteria['name']."' ";
        }

        // Not In
        if(isset($searchCriteria['not_id']) && $searchCriteria['not_id'] != "")
        {
            $whereClaue .= 	" AND pm.id !=".$searchCriteria['not_id']." ";
        }

        $orderField = " pm.name";
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
						".$this->tbl." AS pm ".$whereClaue." ORDER BY ".$orderField." ".$orderDir."";

        //echo $sqlQuery; exit;

        $result     = $this->db->query($sqlQuery);
        $rsData     = $result->result_array();
        return $rsData;
    }
}