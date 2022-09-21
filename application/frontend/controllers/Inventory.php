<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model("order_model",'',true);
        $this->load->model("Inventory_model",'inventory_model',true);
        $this->load->model("product_model",'',true);
        $this->load->model("status_model",'',true);
        $this->load->model("vendor_model",'',true);
        $this->load->model("process_model",'',true);
    }
    ####################################################################
    #						START CLIENT ORDER					       #
    ####################################################################

    public function index()
    {
        $GET = $this->input->get();
        unset($GET['c']);
        unset($GET['m']);
        $searchParams = json_encode($GET);
        $type = $this->Page->getRequest("type");

        $rsListing['type'] = $type;
        $rsListing['searchParams'] = $searchParams;
        $rsListing['flashMessage'] = $this->Page->getMessage();
        $this->load->view('inventory/list', $rsListing);
    }

    ### Auther : Nikunj Bambhroliya
    ### Desc : get inventory data
    public function getInventoryData(){
        $type = $this->Page->getRequest("type");
        $draw = $this->input->post('draw');

        $searchCriteria = array();
        $searchCriteria['offset'] = $this->input->post('start');
        $searchCriteria['limit'] = $this->input->post('length');
        // search using datatable search option code start
        if($this->input->post('search')){
            $searchCriteria['keyword'] = $this->input->post('search')['value'];
        }
        $this->inventory_model->searchCriteria = $searchCriteria;
        $resultA = $this->inventory_model->getInwardOrderList();

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

        $data = [];
        if($resultA["count"] > 0)
        {
            foreach($resultA["data"] as $arrRecord)
            {
                $inwardQty = $arrRecord['prod_qty'];
                $rsOutwardQty = $this->inventory_model->getOutwardQtySum($arrRecord['order_id'], $arrRecord['prod_id']);
                //$rsOutwardChallanNo = $this->inventory_model->getOutwardChallanNo($arrRecord['order_no']);
                $outwardQty = $rsOutwardQty[0]['total_outward_qty'];
                //$outwardQty = $arrRecord['total_outward_qty'];
                //$outwardChallanNo = $rsOutwardChallanNo[0]['outward_challan_no'];
                $pendingQty = $inwardQty - $outwardQty;

                $strProcess = "";
                if($arrRecord['process_ids']){
                    $processIdA = json_decode($arrRecord['process_ids']);
                    if(is_array($processIdA) && count($processIdA)){
                        foreach ($processIdA as $processId){
                            $strProcess .= $processA[$processId].", ";
                        }
                    }
                }
                $strProcess = rtrim($strProcess, ", ");

                $data[] = array(
                    "order_no" => $arrRecord['order_no'],
                    "outward_challan_no" => $arrRecord['outward_challan_no'],
                    //"outward_challan_no" => $outwardChallanNo,
                    "order_date" => $arrRecord['order_date'],
                    "customer_id" => $arrRecord['vendor_name'],
                    "prod_id" => $arrRecord['prod_name'],
                    "process" => $strProcess,
                    "inward_qty" => $inwardQty,
                    "customer_challan_no" => $arrRecord['customer_challan_no'],
                    "outward_qty" =>  $outwardQty,
                    "pending_qty" =>  $pendingQty,
                    "pending_from_days" =>  0,
                    "order_note" => $arrRecord['order_note'],
                );
            }
        }

        $result = array(
            "draw" => $draw,
            "recordsTotal" => $resultA['count'],
            "recordsFiltered" => $resultA['count'],
            "data" => $data
        );

        echo json_encode($result);
        exit();
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */