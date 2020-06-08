<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Inventory_model extends Data {

    public $searchCriteria;
    function __construct()
    {
        parent::__construct();
        $this->tbl = 'order_master';
    }

    public function getInwardOrderList()
    {
        $sql = "SELECT om.*,opd.prod_id,opd.process_ids,opd.prod_qty FROM order_master AS om
                LEFT JOIN order_product_detail AS opd
                ON om.order_id = opd.order_id
                WHERE om.type = 'inward'
                GROUP BY om.order_no,opd.prod_id";

        $result     = $this->db->query($sql);
        $rsData = $result->result_array();

        return $rsData;
    }

    public function getOutwardOrderList($orderNoA)
    {
        $strOrderNo = implode(",", $orderNoA);
        $strOrderNo = str_replace(',','","', $strOrderNo);

        $where = "";
        if($strOrderNo){
            $where .= ' AND ref_order_no IN ("'.$strOrderNo.'")';
        }

        $sql = "SELECT om.ref_order_no,opd.prod_id,SUM(opd.prod_qty) AS total_qty FROM order_master AS om
                LEFT JOIN order_product_detail AS opd
                ON om.order_id = opd.order_id
                WHERE om.type = 'outward'
                ".$where."
                GROUP BY om.order_no,opd.prod_id";

        $result     = $this->db->query($sql);
        $rsData = $result->result_array();

        return $rsData;
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
}