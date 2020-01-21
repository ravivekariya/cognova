<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class itemthickness extends CI_Controller {
 
    
	function __construct()  
	{
		parent::__construct();
		$this->load->model("itemthickness_model",'',true);
		
	}
	
	public function index()
	{
		$this->itemthickness_model->tbl="item_thickness_master";
		$arrWhere	=	array();
		$strAction = $this->input->post('action');
			
		// Get All color

		$rsThickness = $this->itemthickness_model->getItemThickness();
		$rsListing['rsThickness']	=	$rsThickness;
		
		// Load Views
		$this->load->view('itemthickness/list', $rsListing);	
	}
	
	public function Add()
	{
		$this->itemthickness_model->tbl="item_thickness_master";
		$data["strAction"] = $this->Page->getRequest("action");
        $data["strMessage"] = $this->Page->getMessage();
        $data["id"] = $this->Page->getRequest("id");

        if ($data["strAction"] == 'E' || $data["strAction"] == 'V' || $data["strAction"] == 'R')
		{
		   $data["rsEdit"] = $this->itemthickness_model->get_by_id('item_thk_id', $data["id"]);
        } 
		else 
		{
            $data["strAction"] = "A";
        }
		$this->load->view('itemthickness/thicknessForm',$data);
	}
	
	public function Save()
	{
		$this->itemthickness_model->tbl="item_thickness_master";
		$strAction = $this->input->post('action');
		$item_thk_id   = $this->Page->getRequest('item_thk_id');
		
		// Check Duplicate entry
		$searchCriteria = array(); 
		$searchCriteria["selectField"] = "item_thk_id";
		$searchCriteria["item_thk_code"] = $this->Page->getRequest('txt_thk_code');
		if ($strAction == 'E')
		{
            $searchCriteria["not_id"] = $item_thk_id;
		}
		$this->itemthickness_model->searchCriteria=$searchCriteria;
		$rsThickness = $this->itemthickness_model->getItemThickness();
		if(count($rsThickness) > 0)
		{
			$this->Page->setMessage('ALREADY_EXISTS');
			redirect('c=itemthickness&m=Add&action=E&id='.$item_thk_id, 'location');
		}

		$arrHeader["item_thk_name"]   	=	$this->Page->getRequest('txt_thk_name');
        $arrHeader["item_thk_code"]     	=	$this->Page->getRequest('txt_thk_code');
		$arrHeader["item_thickness"]     	=	$this->Page->getRequest('txt_thickness');
		$arrHeader["status"]        	= 	$this->Page->getRequest('slt_status');
		
		if ($strAction == 'A' || $strAction == 'R')
		{
            $arrHeader['insertby']		=	$this->Page->getSession("intUserId");
            $arrHeader['insertdate'] 		= 	date('Y-m-d H:i:s');
            $arrHeader['updatedate'] 		= 	date('Y-m-d H:i:s');
			
			$intCenterID = $this->itemthickness_model->insert($arrHeader);
			$this->Page->setMessage('REC_ADD_MSG');
        }
		elseif ($strAction == 'E')
		{
            $arrHeader['updateby'] 		= 	$this->Page->getSession("intUserId");
            $arrHeader['updatedate'] =	date('Y-m-d H:i:s');
			
            $this->itemthickness_model->update($arrHeader, array('item_thk_id' => $item_thk_id));
            $this->Page->setMessage('REC_EDIT_MSG');
        }
		
		redirect('c=itemthickness', 'location');
	}
	
	public function delete()
	{
		$arrThicknessIds	=	$this->input->post('chk_lst_list1');
		$strThicknessIds	=	implode(",", $arrThicknessIds);
		$strQuery = "DELETE FROM item_thickness_master WHERE item_thk_id IN (". $strThicknessIds .")";
		$this->db->query($strQuery);
		$this->Page->setMessage("DELETE_RECORD");
		// redirect to listing screen
		redirect('c=itemthickness', 'location');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */