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
		
		$selectField = "*,DATE_FORMAT(order_date, '%d-%m-%Y') AS f_order_date";
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
		
		$selectField = "om.*,DATE_FORMAT(om.order_date, '%d-%m-%Y') AS order_date,oot.id AS outward_challan_no,DATE_FORMAT(om.delivery_date, '%d-%m-%Y') AS delivery_date";
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

		$sqlQuery = "SELECT ".$selectField." 
		             FROM order_master AS om
		             LEFT JOIN outward_order_track AS oot 
		             ON om.order_id = oot.order_id
		             ".$whereClaue."  
		             ORDER BY ".$orderField." ".$orderDir."";
		// echo $sqlQuery; exit;
		
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

        $selectField = "*,DATE_FORMAT(order_date, '%d-%m-%Y') AS f_order_date";
        if(isset($searchCriteria['selectField']) && $searchCriteria['selectField'] != "")
        {
            $selectField = 	$searchCriteria['selectField'];
        }

        $whereClaue = "WHERE 1=1 ";

        // By Order
        if(isset($searchCriteria['search_order']) && $searchCriteria['search_order'] != "")
        {
            $searchOrder = str_replace(",","','", $searchCriteria['search_order']);
            $whereClaue .= 	" AND om.order_no IN ('".$searchOrder."') ";
        }

        // By ref order no like
        if($this->Page->getRequest("ref_order_no"))
        {
            $whereClaue .= 	" AND om.ref_order_no LIKE '".$this->Page->getRequest("ref_order_no")."%' ";
        }

        // By outward challan no
        if($this->Page->getRequest("outward_challan_no"))
        {
            $whereClaue .= 	" AND oot.id LIKE '".$this->Page->getRequest("outward_challan_no")."%' ";
        }

        // By order no like
        if($this->Page->getRequest("order_no"))
        {
            $whereClaue .= 	" AND om.order_no LIKE '".$this->Page->getRequest("order_no")."%' ";
        }

        // By date
        if($this->Page->getRequest("order_date_from") && $this->Page->getRequest("order_date_to"))
        {
            $whereClaue .= 	" AND om.order_date BETWEEN '".date("Y-m-d", strtotime($this->Page->getRequest("order_date_from")))."' AND '".date("Y-m-d", strtotime($this->Page->getRequest("order_date_to")))."' ";
        } else if($this->Page->getRequest("order_date_from") && !$this->Page->getRequest("order_date_to")){
            $whereClaue .= 	" AND om.order_date >= '".date("Y-m-d", strtotime($this->Page->getRequest("order_date_from")))."' ";
        } else if(!$this->Page->getRequest("order_date_from") && $this->Page->getRequest("order_date_to")){
            $whereClaue .= 	" AND om.order_date <= '".date("Y-m-d", strtotime($this->Page->getRequest("order_date_to")))."' ";
        }

        // By product
        if($this->Page->getRequest("prod_id"))
        {
            $productA = $this->Page->getRequest("prod_id");

            if(is_array($productA) && !empty($productA)){
                $whereClaue .= 	" AND opd.prod_id IN (".implode(",",$productA).") ";
            } else {
                $whereClaue .= 	" AND opd.prod_id = '".$this->Page->getRequest("prod_id")."' ";
            }
        }

        // By customer
        if($this->Page->getRequest("search_customer"))
        {
            $whereClaue .= 	" AND om.customer_id = '".$this->Page->getRequest("search_customer")."' ";
        }

        // By customer
        if($this->Page->getRequest("customer_id"))
        {
            $whereClaue .= 	" AND om.customer_id = '".$this->Page->getRequest("customer_id")."' ";
        }

        // By processIds
        if($this->Page->getRequest("processIds"))
        {
            $processA = $this->Page->getRequest("processIds");

            if(is_array($processA) && !empty($processA)){
                $tempA = [];
                foreach ($processA AS $processId){
                    $tempA[] = "opd.process_ids REGEXP '[[:<:]]{$processId}[[:>:]]'";
                }

                if(count($tempA) > 0){
                   $tempS = implode(" OR ", $tempA);

                    $whereClaue .= " AND (".$tempS.")";
                }

            } else {
                $whereClaue .= 	" AND opd.process_ids REGEXP '[[:<:]]{$this->Page->getRequest("processIds")}[[:>:]]' ";
            }
        }

        // By Qty
        if($this->Page->getRequest("prod_qty"))
        {
            $whereClaue .= 	" AND opd.prod_qty LIKE '".$this->Page->getRequest("prod_qty")."%' ";
        }

        // By Inward Qty
        /*if($this->Page->getRequest("inward_qty"))
        {
            $whereClaue .= 	" AND inward_qty LIKE '".$this->Page->getRequest("inward_qty")."%' ";
        }*/

        // By Material grade
        if($this->Page->getRequest("material_grade"))
        {
            $whereClaue .= 	" AND om.material_grade LIKE '%".$this->Page->getRequest("material_grade")."%' ";
        }

        // By specification
        if($this->Page->getRequest("specification"))
        {
            $whereClaue .= 	" AND om.specification LIKE '%".$this->Page->getRequest("specification")."%' ";
        }

        // By Remarks
        if($this->Page->getRequest("order_note"))
        {
            $whereClaue .= 	" AND om.order_note LIKE '%".$this->Page->getRequest("order_note")."%' ";
        }

        // By weight
        if($this->Page->getRequest("weight"))
        {
            $whereClaue .= 	" AND opd.weight_per_qty LIKE '%".$this->Page->getRequest("weight")."%' ";
        }

        // By Total weight
        if($this->Page->getRequest("total_weight"))
        {
            $whereClaue .= 	" AND opd.prod_total_weight LIKE '%".$this->Page->getRequest("total_weight")."%' ";
        }

        // By order no like
        if($this->Page->getRequest("customer_challan_no"))
        {
            $whereClaue .= 	" AND om.customer_challan_no LIKE '%".$this->Page->getRequest("customer_challan_no")."%' ";
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

        // keyword search
        if(isset($searchCriteria['keyword']) && $searchCriteria['keyword'] != "")
        {
            $whereClaue .= 	" AND (om.order_no LIKE '%".$searchCriteria['keyword']."%'
                                    OR om.ref_order_no  LIKE '%".$searchCriteria['keyword']."%'
                                    OR om.customer_challan_no  LIKE '%".$searchCriteria['keyword']."%'
                                    OR DATE_FORMAT(om.order_date, '%d-%m-%Y')  LIKE '%".$searchCriteria['keyword']."%'
                                    OR om.order_note  LIKE '%".$searchCriteria['keyword']."%'
                                    OR om.material_grade  LIKE '%".$searchCriteria['keyword']."%'
                                    OR om.specification  LIKE '%".$searchCriteria['keyword']."%'
                                    OR opd.prod_qty  LIKE '%".$searchCriteria['keyword']."%'
                                    OR opd.weight_per_qty  LIKE '%".$searchCriteria['keyword']."%'
                                    OR opd.prod_total_weight  LIKE '%".$searchCriteria['keyword']."%'
                                    OR vm.vendor_name  LIKE '%".$searchCriteria['keyword']."%'
                                    OR pm.prod_name  LIKE '%".$searchCriteria['keyword']."%'
                                    ) ";
        }

        $orderField = " om.order_id ";
        $orderDir = " DESC"; // Change ASC To DESC By Pramod

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

        // set limit clause
        $limitClause = "";
        if(isset($searchCriteria['limit']) && isset($searchCriteria['offset']))
        {
            $limitClause = " LIMIT ".$searchCriteria['offset'].",".$searchCriteria['limit']." ";
        }

        $sql = "SELECT 
                    om.*,DATE_FORMAT(om.order_date, '%d-%m-%Y') AS order_date, opd.prod_id,opd.process_ids,opd.prod_qty,opd.weight_per_qty,opd.prod_total_weight,vm.vendor_name,pm.prod_name,oot.id AS outward_challan_no,
                    (SELECT prod_qty FROM order_product_detail AS opdi WHERE opdi.order_id = opd.ref_order_id AND opdi.prod_id = opd.prod_id) AS inward_qty,
                      (SELECT 
                        DATE_FORMAT(om2.order_date, '%d-%m-%Y') 
                      FROM
                        order_master AS om2 
                      WHERE om.ref_order_no = om2.order_no AND om2.type = 'inward' LIMIT 1) AS inward_date
                FROM order_master AS om
                LEFT JOIN order_product_detail AS opd
                ON om.order_id = opd.order_id
                LEFT JOIN vendor_master AS vm
                ON om.customer_id = vm.vendor_id
                LEFT JOIN product_master AS pm
                ON opd.prod_id = pm.prod_id
                LEFT JOIN outward_order_track AS oot 
                ON om.order_id = oot.order_id
                ".$whereClaue."
                GROUP BY om.order_id,opd.prod_id
                ORDER BY ".$orderField." ".$orderDir."";
        //echo $sql; exit;
        $result     = $this->db->query($sql);
        $count = count($result->result_array());

        $sql .= $limitClause;
        $result = $this->db->query($sql);

        $rsData['data'] = $result->result_array();
        $rsData['count'] = $count;

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