<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class order_model extends Data {

	public $searchCriteria;
	function __construct() 
	{
        parent::__construct();
        $this->tbl = 'order_master';
    }

	//Author : Nikunj Bambhroliya
	//Description : generate uniq order number
	public function generateOrderNo()
	{
		$query = "SELECT (id+1) AS new_order_no FROM order_track ORDER BY id DESC LIMIT 1";
		$new_order_no = $this->db->query($query)->row()->new_order_no;
		$new_order_no = ($new_order_no) ? sprintf("%04d", $new_order_no) : "0001";
		return $new_order_no;
	}



	//Author : Nikunj Bambhroliya
	//Description : return clienct created order list
	public function getOrderList()
	{
		$searchCriteria = array();
		$searchCriteria = $this->searchCriteria;
		
		$selectField = "*";
		if(isset($searchCriteria['selectField']) && $searchCriteria['selectField'] != "")
		{
			$selectField = 	$searchCriteria['selectField'];
		}
		
		$whereClaue = "WHERE is_complete=0 ";

		// By Order		
		if(isset($searchCriteria['search_order']) && $searchCriteria['search_order'] != "")
		{
			$whereClaue .= 	" AND order_no='".$searchCriteria['search_order']."' ";
		}
		
		// By From Date		
		if(isset($searchCriteria['from_date']) && $searchCriteria['from_date'] != "")
		{
			$whereClaue .= 	" AND order_date>='".$searchCriteria['from_date']."' ";
		}
		
		// By To date		
		if(isset($searchCriteria['to_date']) && $searchCriteria['to_date'] != "")
		{
			$whereClaue .= 	" AND order_date<='".$searchCriteria['to_date']."' ";
		}

        // By Order Type
        if(isset($searchCriteria['type']) && $searchCriteria['type'] != "")
        {
            $whereClaue .= 	" AND type ='".$searchCriteria['type']."' ";
        }
		
		// Not In
		if(isset($searchCriteria['not_id']) && $searchCriteria['not_id'] != "")
		{
			$whereClaue .= 	" AND order_id !=".$searchCriteria['not_id']." ";
		}
		
		$orderField = " order_id ";
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
					  FROM order_master ".$whereClaue." ORDER BY ".$orderField." ".$orderDir."";
		
		//echo $sqlQuery; exit;
		$result     = $this->db->query($sqlQuery);
		$rsData     = $result->result_array();
		return $rsData;
		
	}

	//Author : Nikunj Bambhroliya
	//Description : return client order detail
	public function getOrderDetails()
	{
		$searchCriteria = array();
		$searchCriteria = $this->searchCriteria;
		
		$selectField = "om.*";
		if(isset($searchCriteria['selectField']) && $searchCriteria['selectField'] != "")
		{
			$selectField = 	$searchCriteria['selectField'];
		}
		
		$whereClaue = "WHERE 1=1 ";
		
		// By Order id
		if(isset($searchCriteria['order_id']) && $searchCriteria['order_id'] != "")
		{
			$whereClaue .= 	" AND om.order_id IN (".$searchCriteria['order_id'].") ";
		}

        // By Order Number
        if(isset($searchCriteria['order_no']) && $searchCriteria['order_no'] != "")
        {
            $whereClaue .= 	" AND om.order_no = '".$searchCriteria['order_no']."' ";
        }

		// By Customer Id
		if(isset($searchCriteria['cust_id']) && $searchCriteria['cust_id'] != "")
		{
			$whereClaue .= 	" AND om.customer_id = '".$searchCriteria['cust_id']."' ";
		}
		
		// By status
		if(isset($searchCriteria['status']) && $searchCriteria['status'] != "")
		{
			$whereClaue .= 	" AND om.order_status = '".$searchCriteria['status']."' ";
		}
		
		// By complete status
		if(isset($searchCriteria['is_complete']) && $searchCriteria['is_complete'] != "")
		{
			$whereClaue .= 	" AND om.is_complete = ".$searchCriteria['is_complete']." ";
		}
		
		// Not In
		if(isset($searchCriteria['not_id']) && $searchCriteria['not_id'] != "")
		{
			$whereClaue .= 	" AND om.order_id !=".$searchCriteria['not_id']." ";
		}
		
		$orderField = " om.order_id";
		$orderDir = " DESC";
		
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

		$sqlQuery = "SELECT ".$selectField." FROM order_master AS om ".$whereClaue."  ORDER BY ".$orderField." ".$orderDir."";
		//echo $sqlQuery; exit;
		
		$result     = $this->db->query($sqlQuery);
		$rsData     = $result->result_array();
		$orderDetailArr = array();
		if(count($rsData) > 0)
		{
			foreach($rsData AS $key=>$row)
			{
				// Get Order Product Details
				$orderProductDetailsArr = array();
				if(isset($searchCriteria["fetchProductDetail"]) && $searchCriteria["fetchProductDetail"] == 1)
				{
					$prodSearchCriteria = array();
					$prodSearchCriteria['selectField'] = "opd.*,pm.prod_name AS prod_name";
					$prodSearchCriteria['order_id'] = $row['order_id'];
					$this->searchCriteria = $prodSearchCriteria;
					$orderProductDetailsArr = $this->getOrderProductDetails();
					$row['orderProductDetailsArr'] = $orderProductDetailsArr;
				}

				// Get Order Status
				$orderStatusArr = array();
				if(isset($searchCriteria["fetchOrderStatus"]) && $searchCriteria["fetchOrderStatus"] == 1)
				{
					$orderSearchCriteria = array();
					$orderSearchCriteria['order_id'] = $row['order_id'];
					$this->searchCriteria = $orderSearchCriteria;
					$orderStatusArr = $this->getOrderStatus();
					$row['orderStatusArr'] = $orderStatusArr[1];
				}
				$orderDetailArr[] = $row;
			}
		}
		//$this->Page->pr($orderDetailArr);
		return $orderDetailArr;
	}

	
	//Author : Nikunj Bambhroliya
	//Description : return client order product details
	public function getOrderProductDetails()
	{
		$searchCriteria = array();
		$searchCriteria = $this->searchCriteria;
		
		$selectField = "opd.*,pm.prod_name AS prod_name";
		if(isset($searchCriteria['selectField']) && $searchCriteria['selectField'] != "")
		{
			$selectField = 	$searchCriteria['selectField'];
		}
		
		$whereClaue = "WHERE 1=1 ";
		
		// By Order id
		if(isset($searchCriteria['order_id']) && $searchCriteria['order_id'] != "")
		{
			$whereClaue .= 	" AND opd.order_id IN (".$searchCriteria['order_id'].") ";
		}

		// By Product id
		if(isset($searchCriteria['prod_id']) && $searchCriteria['prod_id'] != "")
		{
			$whereClaue .= 	" AND opd.prod_id IN (".$searchCriteria['prod_id'].") ";
		}
		
		// By status
		if(isset($searchCriteria['status']) && $searchCriteria['status'] != "")
		{
			$whereClaue .= 	" AND opd.status = '".$searchCriteria['status']."' ";
		}
		
		// Not In
		if(isset($searchCriteria['not_id']) && $searchCriteria['not_id'] != "")
		{
			$whereClaue .= 	" AND opd.opd_id !=".$searchCriteria['not_id']." ";
		}
		
		$orderField = " opd.order_id,opd.prod_id";
		$orderDir = " DESC";
		
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

		$sqlQuery = "SELECT ".$selectField." FROM order_product_detail AS opd LEFT JOIN product_master AS pm ON opd.prod_id = pm.prod_id ".$whereClaue."  ORDER BY ".$orderField." ".$orderDir."";
		// echo $sqlQuery; exit;
		
		$result     = $this->db->query($sqlQuery);
		$rsData     = $result->result_array();

		return $rsData;
	}

	//Author : Nikunj Bambhroliya
	//Description : return client order status details
	public function getOrderStatus()
	{
		$searchCriteria = array();
		$searchCriteria = $this->searchCriteria;
		
		$selectField = "ops.*";
		if(isset($searchCriteria['selectField']) && $searchCriteria['selectField'] != "")
		{
			$selectField = 	$searchCriteria['selectField'];
		}
		
		$whereClaue = "WHERE 1=1 ";
		
		// By Order id
		if(isset($searchCriteria['order_id']) && $searchCriteria['order_id'] != "")
		{
			$whereClaue .= 	" AND ops.order_id IN (".$searchCriteria['order_id'].") ";
		}

		// By Product id
		if(isset($searchCriteria['prod_id']) && $searchCriteria['prod_id'] != "")
		{
			$whereClaue .= 	" AND ops.prod_id IN (".$searchCriteria['prod_id'].") ";
		}
		
		// By status
		if(isset($searchCriteria['status_id']) && $searchCriteria['status_id'] != "")
		{
			$whereClaue .= 	" AND ops.status_id = '".$searchCriteria['status_id']."' ";
		}
		
		// Not In
		if(isset($searchCriteria['not_id']) && $searchCriteria['not_id'] != "")
		{
			$whereClaue .= 	" AND ops.ops_id !=".$searchCriteria['not_id']." ";
		}

		$groupBy = "";
		// Set Grouping
		if(isset($searchCriteria['groupField']) && $searchCriteria['groupField'] != "")
		{
			$groupBy = " GROUP BY ".$searchCriteria['groupField']." ";
		}

		
		$orderField = " ops.order_id,ops.prod_id";
		$orderDir = " DESC";
		
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

		$sqlQuery = "SELECT ".$selectField." FROM order_product_status AS ops ".$whereClaue.$groupBy." ORDER BY ".$orderField." ".$orderDir."";
		// echo $sqlQuery; exit;
		
		$result     = $this->db->query($sqlQuery);
		$rsData     = $result->result_array();
		return $rsData;
	}

    public function getInwardOrderList()
    {
        $searchCriteria = array();
        $searchCriteria = $this->searchCriteria;

        $selectField = "*";
        if(isset($searchCriteria['selectField']) && $searchCriteria['selectField'] != "")
        {
            $selectField = 	$searchCriteria['selectField'];
        }

        $whereClaue = "WHERE 1=1 ";

        // By Order
        if(isset($searchCriteria['search_order']) && $searchCriteria['search_order'] != "")
        {
            $whereClaue .= 	" AND om.order_no='".$searchCriteria['search_order']."' ";
        }

        // By From Date
        if(isset($searchCriteria['from_date']) && $searchCriteria['from_date'] != "")
        {
            $whereClaue .= 	" AND om.order_date>='".$searchCriteria['from_date']."' ";
        }

        // By To date
        if(isset($searchCriteria['to_date']) && $searchCriteria['to_date'] != "")
        {
            $whereClaue .= 	" AND om.order_date<='".$searchCriteria['to_date']."' ";
        }

        // By Order Type
        if(isset($searchCriteria['type']) && $searchCriteria['type'] != "")
        {
            $whereClaue .= 	" AND om.type ='".$searchCriteria['type']."' ";
        }

        // Not In
        if(isset($searchCriteria['not_id']) && $searchCriteria['not_id'] != "")
        {
            $whereClaue .= 	" AND om.order_id !=".$searchCriteria['not_id']." ";
        }

        $orderField = " om.order_id ";
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

        $sql = "SELECT om.*,opd.prod_id,opd.process_ids,opd.prod_qty,opd.weight_per_qty,opd.prod_total_weight FROM order_master AS om
                LEFT JOIN order_product_detail AS opd
                ON om.order_id = opd.order_id
                ".$whereClaue."
                GROUP BY om.order_id,opd.prod_id
                ORDER BY ".$orderField." ".$orderDir."";

        $result     = $this->db->query($sql);
        $rsData = $result->result_array();

        return $rsData;
    }

    public function getInwardQtyDetails($orderNumber)
    {
        $sql = "SELECT opd.prod_id,opd.prod_qty FROM order_master AS om
                LEFT JOIN order_product_detail AS opd
                ON om.order_id = opd.order_id
                WHERE om.order_no = '".$orderNumber."'
                GROUP BY om.order_id,opd.prod_id";

        $result     = $this->db->query($sql);
        $rsData = $result->result_array();

        $inwardQtyA = [];
        if($rsData && count($rsData) > 0){
            foreach ($rsData as $data){
                $inwardQtyA[$data["prod_id"]] = $data["prod_qty"];
            }
        }

        $sql = "SELECT opd.prod_id,SUM(opd.prod_qty) AS prod_qty FROM order_master AS om
                LEFT JOIN order_product_detail AS opd
                ON om.order_id = opd.order_id
                WHERE om.ref_order_no = '".$orderNumber."'
                GROUP BY opd.prod_id";

        $result     = $this->db->query($sql);
        $rsData = $result->result_array();

        $outwardProceedQtyA = [];
        if($rsData && count($rsData) > 0){
            foreach ($rsData as $data){
                $outwardProceedQtyA[$data["prod_id"]] = $data["prod_qty"];
            }
        }

        $returnA = ['inwardQtyA' => $inwardQtyA, 'outwardProceedQtyA' => $outwardProceedQtyA];

        return $returnA;
    }
}