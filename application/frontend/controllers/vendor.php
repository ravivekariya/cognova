<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class vendor extends CI_Controller {
 
    
	function __construct()  
	{
		parent::__construct();
		$this->load->model("vendor_model",'',true);
		
	}
	
	public function index()
	{
		// Get All Vendors
		$searchCriteria	=	array();
		$searchCriteria['orderField'] = 'insertdate';
		$searchCriteria['orderDir'] = 'DESC';
		$this->vendor_model->searchCriteria = $searchCriteria;
		$rsVendors = $this->vendor_model->getVendor();
		$rsListing['rsVendors']	=	$rsVendors;
		
		// Load Views
		$this->load->view('vendor/list', $rsListing);	
	}
	
	public function AddVendor()
	{
		$data["strAction"] = $this->Page->getRequest("action");
        $data["strMessage"] = $this->Page->getMessage();
        $data["id"] = $this->Page->getRequest("id");

        if ($data["strAction"] == 'E' || $data["strAction"] == 'V' || $data["strAction"] == 'R')
		{
		   $data["rsEdit"] = $this->vendor_model->get_by_id('vendor_id', $data["id"]);
        } 
		else 
		{
            $data["strAction"] = "A";
        }
		$this->load->view('vendor/vendorForm',$data);
	}
	
	public function SaveVendor()
	{
		$strAction = $this->input->post('action');
		$vendor_id   = $this->Page->getRequest('vendor_id');
		
		// Check Duplicate entry
		$searchCriteria = array(); 
		$searchCriteria["name"] = $this->Page->getRequest('txt_vendor_name');
		if ($strAction == 'E')
		{
            $searchCriteria["not_id"] = $vendor_id;
		}
		$this->vendor_model->searchCriteria=$searchCriteria;
		$rsVendor = $this->vendor_model->getVendor();
		if(count($rsVendor) > 0)
		{
			$this->Page->setMessage('ALREADY_EXISTS');
			redirect('c=vendor&m=AddVendor', 'location');
		}

		$arrHeader["vendor_name"]   	=	$this->Page->getRequest('txt_vendor_name');
        $arrHeader["vendor_comp_name"]     	=	$this->Page->getRequest('txt_vendor_comp_name');
        $arrHeader["vendor_phone"]        =   $this->Page->getRequest('txt_vendor_phone');
        $arrHeader["vendor_email"]        =   $this->Page->getRequest('txt_vendor_email');
		$arrHeader["vendor_address"]        =   $this->Page->getRequest('txt_vendor_address');
		$arrHeader["vendor_city"]        =   $this->Page->getRequest('txt_vendor_city');
		$arrHeader["vendor_state"]        =   $this->Page->getRequest('txt_vendor_state');
		$arrHeader["vendor_country"]        =   $this->Page->getRequest('txt_vendor_country');
		$arrHeader["vendor_postal_code"]        =   $this->Page->getRequest('txt_vendor_postal_code');
		$arrHeader["status"]        	= 	$this->Page->getRequest('slt_status');
		
		if ($strAction == 'A' || $strAction == 'R')
		{
            $arrHeader['insertby']		=	$this->Page->getSession("intUserId");
            $arrHeader['insertdate'] 		= 	date('Y-m-d H:i:s');
            $arrHeader['updatedate'] 		= 	date('Y-m-d H:i:s');
			
			$intCenterID = $this->vendor_model->insert($arrHeader);
			$this->Page->setMessage('REC_ADD_MSG');
        }
		elseif ($strAction == 'E')
		{
            $vendor_id				= 	$this->Page->getRequest('vendor_id');
            $arrHeader['updateby'] 		= 	$this->Page->getSession("intUserId");
            $arrHeader['updatedate'] =	date('Y-m-d H:i:s');
			
            $this->vendor_model->update($arrHeader, array('vendor_id' => $vendor_id));
            $this->Page->setMessage('REC_EDIT_MSG');
        }
		
		redirect('c=vendor', 'location');
	}

	public function ajaxSaveVendor()
	{
		// Check vendor
		$searchCriteria = array();
		$searchCriteria["name"] = $_REQUEST['txt_vendor_name'];
		$this->vendor_model->searchCriteria=$searchCriteria;
		$rsVendor = $this->vendor_model->getVendor();

		if(count($rsVendor) > 0)
		{
			echo $option .= "0|exist"; exit;
		}

		$arrHeader["vendor_name"]   	=	$this->Page->getRequest('txt_vendor_name');
        $arrHeader["vendor_comp_name"]     	=	$this->Page->getRequest('txt_vendor_comp_name');
        $arrHeader["vendor_phone"]        =   $this->Page->getRequest('txt_vendor_phone');
        $arrHeader["vendor_email"]        =   $this->Page->getRequest('txt_vendor_email');
		$arrHeader["vendor_address"]        =   $this->Page->getRequest('txt_vendor_address');
		$arrHeader["vendor_city"]        =   $this->Page->getRequest('txt_vendor_city');
		$arrHeader["vendor_state"]        =   $this->Page->getRequest('txt_vendor_state');
		$arrHeader["vendor_country"]        =   $this->Page->getRequest('txt_vendor_country');
		$arrHeader["vendor_postal_code"]        =   $this->Page->getRequest('txt_vendor_postal_code');
		$arrHeader["status"]        	= 	$this->Page->getRequest('slt_status');		
		$arrHeader['insertby']		=	$this->Page->getSession("intUserId");
		$arrHeader['insertdate'] 		= 	date('Y-m-d H:i:s');
		$arrHeader['updatedate'] 		= 	date('Y-m-d H:i:s');
		
		$intCenterID = $this->vendor_model->insert($arrHeader);

		if($intCenterID > 0)
		{
			echo $intCenterID."|success";
		}
		else
		{
			echo "0|error";
		}
		exit;
        
	}
	
	public function delete()
	{
		$arrVendorIds	=	$this->input->post('chk_lst_list1');
		$strVendorIds	=	implode(",", $arrVendorIds);
		$strQuery = "DELETE FROM vendor_master WHERE vendor_id IN (". $strVendorIds .")";
		$this->db->query($strQuery);
		$this->Page->setMessage("DELETE_RECORD");
		// redirect to listing screen
		redirect('c=vendor', 'location');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */