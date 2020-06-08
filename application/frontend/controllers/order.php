<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class order extends CI_Controller {
 
	function __construct()  
	{
		parent::__construct();
		$this->load->model("order_model",'',true);
		$this->load->model("product_model",'',true);
		$this->load->model("status_model",'',true);
		$this->load->model("process_model",'',true);
        $this->load->model("vendor_model",'',true);
	}
	####################################################################
	#						START CLIENT ORDER					       #
	####################################################################

	public function index()
	{
		$search_order = $this->Page->getRequest("search_order");
		$from_date = $this->Page->getRequest("from_date");
		$to_date = $this->Page->getRequest("to_date");
		$type = $this->Page->getRequest("type");

		// Get Order List
		$searchCriteria = array();
		$searchCriteria['search_order'] = $search_order;
		$searchCriteria['from_date'] = $from_date;
		$searchCriteria['to_date'] = $to_date;
		$searchCriteria['type'] = $type;
		$this->order_model->searchCriteria = $searchCriteria;
		$orderListArr = $this->order_model->getInwardOrderList();

        // get Product List
        $searchCriteria = ['status' => 'ACTIVE'];
        $this->product_model->searchCriteria = $searchCriteria;
        $prodResA = $this->product_model->getProduct();
        $prodA = [];
        if(is_array($prodResA) && count($prodResA) > 0){
            foreach ($prodResA AS $record){
                $prodA[$record["prod_id"]] = $record["prod_name"];
            }
        }

        // get vendor List
        $searchCriteria = ['status' => 'ACTIVE'];
        $this->vendor_model->searchCriteria = $searchCriteria;
        $prodVendorA = $this->vendor_model->getVendor();
        $vendorA = [];
        if(is_array($prodVendorA) && count($prodVendorA) > 0){
            foreach ($prodVendorA AS $record){
                $vendorA[$record["vendor_id"]] = $record["vendor_name"];
            }
        }

        // get process List
        $searchCriteria = ['status' => 'ACTIVE'];
        $this->process_model->searchCriteria = $searchCriteria;
        $resProcessA = $this->process_model->getDetails();
        $processA = [];
        if(is_array($resProcessA) && count($resProcessA) > 0){
            foreach ($resProcessA AS $record){
                $processA[$record["id"]] = $record["name"];
            }
        }

		/*$orderListArr = array();
		if(count($orderListRes) > 0)
		{
			foreach($orderListRes AS $orderRow)
			{
				$orderListArr[] = $orderRow;
			}
		}*/
		//$this->Page->pr($orderListArr); exit;

        $rsListing['prodA'] = $prodA;
        $rsListing['processA'] = $processA;
        $rsListing['vendorA'] = $vendorA;
        $rsListing['orderListArr'] = $orderListArr;
		$rsListing['search_order'] = $search_order;
		$rsListing['from_date'] = $from_date;
		$rsListing['to_date'] = $to_date;
		$rsListing['type'] = $type;
		$this->load->view('order/listClientOrder', $rsListing);
	}
	### Auther : Nikunj Bambhroliya
	### Desc : create client order
	public function createOrder()
	{
		$type = $this->Page->getRequest("type");
		$action = $this->Page->getRequest("action");
		$orderId = $this->Page->getRequest("orderId");
		$refOrderNo = $this->Page->getRequest("refOrderNo");

		if($action == "E")
		{
			// Get Order Details
			$searchCriteria = array();
			$searchCriteria['order_id'] = $orderId;
			$searchCriteria['fetchProductDetail'] = 1;
			$this->order_model->searchCriteria = $searchCriteria;
			$orderDetailArr = $this->order_model->getOrderDetails();
			$orderDetailArr = $orderDetailArr[0];
            $refOrderNo = $orderDetailArr["ref_order_no"];

			$rsListing['strAction'] = "E";
			$rsListing['orderId'] = $orderId;
			$rsListing['orderNo'] = $orderDetailArr['order_no'];
			$rsListing['orderDetailArr'] = $orderDetailArr;
		}
		else
		{
            if($refOrderNo){
                // Get Order Details
                $searchCriteria = array();
                $searchCriteria['order_no'] = $refOrderNo;
                $searchCriteria['fetchProductDetail'] = 1;
                $this->order_model->searchCriteria = $searchCriteria;
                $orderDetailArr = $this->order_model->getOrderDetails();
                $orderDetailArr = $orderDetailArr[0];

                if(!$orderDetailArr){
                    $this->Page->setFlashMessage('error', 'Inward Order not found');
                    redirect('c=order&m=createOrder&type='.$type, 'location');
                }

                $rsListing['orderDetailArr'] = $orderDetailArr;
            }

		    $rsListing['strAction'] = "A";
			$rsListing['orderId'] = $orderId;
			$rsListing['orderNo'] = $this->order_model->generateOrderNo();
			$rsListing['refOrderNo'] = $refOrderNo;
			$rsListing['flashMessage'] = $this->Page->getMessage();
		}

        // get Total Inward and processed(Outward) qty.
        if($refOrderNo){
            $proceedQtyA = $this->order_model->getInwardQtyDetails($refOrderNo);
        }

        $rsListing['type'] = $type;
        $rsListing['proceedQtyA'] = $proceedQtyA;

		// Load Views
		$this->load->view('order/orderForm', $rsListing);	
	}

	### Auther : Nikunj Bambhroliya
	### Desc : view client order
	public function viewClientOrder()
	{
		$userId = $this->Page->getSession("intUserId");
		$orderId = $this->Page->getRequest("orderId");

		// Get Order Details
		$searchCriteria = array();
		$searchCriteria['order_id'] = $orderId;
		$searchCriteria['fetchProductDetail'] = 1;
		$this->order_model->searchCriteria = $searchCriteria;
		$orderDetailArr = $this->order_model->getOrderDetails();
		$orderDetailArr = $orderDetailArr[0];
		$rsListing['orderDetailArr'] = $orderDetailArr;

		// Get Customer Details
		$searchCriteria = array();
		$searchCriteria['cusomerId'] = $orderDetailArr["customer_id"];
		$this->customer_model->searchCriteria = $searchCriteria;
		$customerArr = $this->customer_model->getCustomerDetails();
		$rsListing['customerArr'] = $customerArr[0];

		$this->load->view('order/viewClientOrder', $rsListing);
	}

	### Auther : Nikunj Bambhroliya
	### Desc : save Order
	public function saveOrder()
	{
		$strAction = $this->Page->getRequest("hdnAction");
		$orderId = $this->Page->getRequest("hdnOrderId");
		$productArr = $_REQUEST["productArr"];
        $type = $this->Page->getRequest("hdnType");

		$cnt = 0;
		// Order Master Entry
		$arrData = array();

        if($this->Page->getRequest("txtOrderNo")){
            $arrData['order_no'] = $this->Page->getRequest("txtOrderNo");
        }

		if($this->Page->getRequest("txtOrderRefNo")){
            $arrData['ref_order_no'] = $this->Page->getRequest("txtOrderRefNo");
        }
		$arrData['order_date'] = $this->Page->getRequest("txtOrderDate");
		$arrData['customer_id'] = $this->Page->getRequest("selCustomer");
		$arrData['customer_challan_no'] = $this->Page->getRequest("txtCustChallanNo");
		$arrData['material_grade'] = $this->Page->getRequest("txtMaterialGrade");
		$arrData['specification'] = $this->Page->getRequest("txtSpec");
		$arrData['sub_total_amount'] = $this->Page->getRequest("subTotal");
		$arrData['order_status'] = "pending";
		$arrData['order_note'] = $this->Page->getRequest("txtNote");
		$arrData['type'] = $type;

		if($strAction == "A")
		{
			$arrData['insertby']	=	$this->Page->getSession("intUserId");
			$arrData['insertdate'] 	= 	date('Y-m-d H:i:s');

			$this->order_model->tbl = "order_master";
			$orderId = $this->order_model->insert($arrData);

			if($type == "inward"){
                // save order ref. keep track
                $this->order_model->tbl = "order_track";
                $this->order_model->insert(["order_id" => $orderId]);
            }
		}
		else
		{
			$arrData['updateby']	=	$this->Page->getSession("intUserId");
			$arrData['updatedate'] 	= 	date('Y-m-d H:i:s');
			
			$whereArr = array();
			$whereArr['order_id'] = $orderId;

			$this->order_model->tbl = "order_master";
			$this->order_model->update($arrData,$whereArr);
		}

		// Remove existing products
		//$strQuery = "DELETE FROM order_product_detail WHERE order_id=".$orderId."";
		//$this->db->query($strQuery);

		// Add Order Product Details
		if($orderId != "" && $orderId != 0)
		{
			foreach($productArr AS $prod_id=>$arr)
			{
				// Order Product Detail Entry
				$arrData = array();
				$arrData['order_id'] = $orderId;
				$arrData['prod_Id'] = $arr['prodId'];
				$arrData['prod_qty'] = $arr['prodQty'];
				$arrData['weight_per_qty'] = $arr['weightPerQty'];
				$arrData['prod_total_weight'] = $arr['prodTotalWeight'];
				$arrData['process_ids'] = (isset($arr['processIds']) && is_array($arr['processIds'])) ? json_encode($arr['processIds']) : null;

				if($strAction == "A")
				{
					$arrData['insertby'] = $this->Page->getSession("intUserId");
					$arrData['insertdate'] = date('Y-m-d H:i:s');

                    $this->order_model->tbl = "order_product_detail";
                    $this->order_model->insert($arrData);
				}
				else
				{
					$arrData['updateby']	=	$this->Page->getSession("intUserId");
					$arrData['updatedate'] 	= 	date('Y-m-d H:i:s');

                    $whereArr = array();
                    $whereArr['order_id'] = $orderId;
                    $whereArr['prod_id'] = $arr['prodId'];

                    $this->order_model->tbl = "order_product_detail";
                    $this->order_model->update($arrData, $whereArr);
				}
				$cnt++;
			}
		}

		if($cnt > 0)
		{
			echo "1"; exit;
		}
		else
		{
			echo "2"; exit;
		}
	}

	### Auther : Nikunj Bambhroliya
	### Desc : get order status details
	public function getOrderStatus()
	{
		$orderId = $this->Page->getRequest("orderId");
		$statusId = $this->Page->getRequest("statusId");
		$seq = $this->Page->getRequest("seq");		
		
		// Get Order Status Master Details
		$searchCriteria = array();
		$searchCriteria['selectField'] = 'sts.status_id,sts.status_name,sts.seq';
		$searchCriteria['status'] = 'ACTIVE';
		$this->status_model->searchCriteria = $searchCriteria;
		$statusMasterArr = $this->status_model->getClientOrderStatusMaster();

		$stsBySeqArr = array();
		if(count($statusMasterArr) > 0)
		{
			foreach($statusMasterArr AS $row)
			{
				$stsBySeqArr[$row['seq']] = $row['status_id'];
			}
		}

		// get previous status id
		$prvStatusId = $stsBySeqArr[$seq-1];
		
		// Get Order Product Details
		$searchCriteria = array();
		$searchCriteria["selectField"] = "pm.prod_id,pm.prod_type,pm.prod_name,opd.prod_qty";
		$searchCriteria["order_id"] = $orderId;
		$this->order_model->searchCriteria = $searchCriteria;
		$orderProductDetailArr = $this->order_model->getOrderProductDetails();

		// Get Order Status
		$searchCriteria = array();
		$searchCriteria["selectField"] = "ops.prod_id,SUM(ops.qty) AS qty";
		$searchCriteria["order_id"] = $orderId;
		$searchCriteria["status_id"] = $statusId;
		$searchCriteria["groupField"] = "ops.prod_id";
		$this->order_model->searchCriteria = $searchCriteria;
		$orderStatusDetailArr = $this->order_model->getOrderStatus();

		if(count($orderStatusDetailArr) > 0)
		{
			$statusArr = array();
			foreach($orderStatusDetailArr AS $row)
			{
				$statusArr[$row['prod_id']] = $row['qty'];
			}
		}

		// Get Previous Status Details
		$prvStatusArr = array();
		if($prvStatusId != "")
		{
			$searchCriteria = array();
			$searchCriteria["selectField"] = "ops.prod_id,SUM(ops.qty) AS qty";
			$searchCriteria["order_id"] = $orderId;
			$searchCriteria["status_id"] = $prvStatusId;
			$searchCriteria["groupField"] = "ops.prod_id";
			$this->order_model->searchCriteria = $searchCriteria;
			$prvOrderStatusDetailArr = $this->order_model->getOrderStatus();

			if(count($prvOrderStatusDetailArr) > 0)
			{
				foreach($prvOrderStatusDetailArr AS $row)
				{
					$prvStatusArr[$row['prod_id']] = $row['qty'];
				}
			}
		}
		//$this->Page->pr($prvStatusArr); exit;

		$productArr = array();
		if(count($orderProductDetailArr) > 0)
		{
			foreach($orderProductDetailArr AS $row)
			{
				if($seq == 1 || (isset($prvStatusArr[$row['prod_id']]) && $prvStatusArr[$row['prod_id']] != ""))
				{
					$row['prv_proceed_qty'] = intval($prvStatusArr[$row['prod_id']]);
					if($row['prv_proceed_qty'] > 0)
					{
						$row['process_qty'] = $row['prv_proceed_qty'];
					}
					else
					{
						$row['process_qty'] = intval($row['prod_qty']);
					}
					$row['proceed_qty'] = intval($statusArr[$row['prod_id']]);
					$row['remain_qty'] = intval($row['process_qty']) - intval($row['proceed_qty']);
					if($row['remain_qty'] > 0)
					{
						$productArr[$row['prod_id']] = $row;
					}
				}
			}
		}
		//$this->Page->pr($productArr); exit;
		$rsListing["orderProductDetailArr"] = $productArr;

		// Get Order Status Details
		$this->load->view("order/orderStatusDetail",$rsListing);
	}

	### Auther : Nikunj Bambhroliya
	### Desc : save order status
	public function saveOrderStatus()
	{
		$orderId = $_REQUEST['orderId'];
		$statusId = $_REQUEST['statusId'];
		$prodArr = $_REQUEST['prodArr'];

		// check status for release inventory
		$release_inventory = 0;
		$searchCriteria = array();
		$searchCriteria["selectField"] = "sts.release_inventory";
		$searchCriteria['statusid'] = $statusId;
		$this->status_model->searchCriteria = $searchCriteria;
		$res = $this->status_model->getClientOrderStatusMaster();
		$release_inventory = $res[0]['release_inventory'];

		if(count($prodArr) > 0)
		{
			foreach($prodArr AS $prod_id => $qty)
			{
				$arrData = array();
				$arrData['order_id'] = $orderId;
				$arrData['prod_id'] = $prod_id;
				$arrData['status_id'] = $statusId;
				$arrData['qty'] = $qty;
				$arrData['insert_by'] = $this->Page->getSession("intUserId");
				$arrData['insert_date'] = date("Y-m-d h:i:s");
				$this->order_model->tbl = "order_product_status";
				$this->order_model->insert($arrData);

				if($release_inventory == "1"){
					// remove in stock from inventory
					$arrData = array();
					$arrData['order_id'] = $orderId;
					$arrData['prod_id'] = $prod_id;
					$arrData['prod_qty'] = -1 * abs($qty);
					$arrData['action'] = "minus";
					$arrData['status'] = "in_stock";
					$arrData['insertby'] =	$this->Page->getSession("intUserId");
					$arrData['insertdate'] = date('Y-m-d H:i:s');

					$this->inventory_model->tbl = "inventory_master";
					$this->inventory_model->insert($arrData);
				}
			}
		}
		echo "1"; exit;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */