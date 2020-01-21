<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class itemcolor extends CI_Controller {
 
    
	function __construct()  
	{
		parent::__construct();
		$this->load->model("itemcolor_model",'',true);
		
	}
	
	public function index()
	{
		$this->itemcolor_model->tbl="item_color_master";
		$arrWhere	=	array();
		$strAction = $this->input->post('action');
			
		// Get All color

		$rsColors = $this->itemcolor_model->getItemColor();
		$rsListing['rsColors']	=	$rsColors;
		
		// Load Views
		$this->load->view('itemcolor/list', $rsListing);	
	}
	
	public function Add()
	{
		$this->itemcolor_model->tbl="item_color_master";
		$data["strAction"] = $this->Page->getRequest("action");
        $data["strMessage"] = $this->Page->getMessage();
        $data["id"] = $this->Page->getRequest("id");

        if ($data["strAction"] == 'E' || $data["strAction"] == 'V' || $data["strAction"] == 'R')
		{
		   $data["rsEdit"] = $this->itemcolor_model->get_by_id('item_color_id', $data["id"]);
        } 
		else 
		{
            $data["strAction"] = "A";
        }
		$this->load->view('itemcolor/colorForm',$data);
	}
	
	public function Save()
	{
		$this->itemcolor_model->tbl="item_color_master";
		$strAction = $this->input->post('action');
		$item_color_id   = $this->Page->getRequest('item_color_id');
		
		// Check Duplicate entry
		$searchCriteria = array(); 
		$searchCriteria["selectField"] = "item_color_id";
		$searchCriteria["item_color_code"] = $this->Page->getRequest('txt_color_code');
		if ($strAction == 'E')
		{
            $searchCriteria["not_id"] = $item_color_id;
		}
		$this->itemcolor_model->searchCriteria=$searchCriteria;
		$rsColor = $this->itemcolor_model->getItemColor();
		if(count($rsColor) > 0)
		{
			$this->Page->setMessage('ALREADY_EXISTS');
			redirect('c=itemcolor&m=Add&action=E&id='.$item_color_id, 'location');
		}

		$arrHeader["item_color_name"]   	=	$this->Page->getRequest('txt_color_name');
        $arrHeader["item_color_code"]     	=	$this->Page->getRequest('txt_color_code');
		$arrHeader["status"]        	= 	$this->Page->getRequest('slt_status');
		
		if ($strAction == 'A' || $strAction == 'R')
		{
            $arrHeader['insertby']		=	$this->Page->getSession("intUserId");
            $arrHeader['insertdate'] 		= 	date('Y-m-d H:i:s');
            $arrHeader['updatedate'] 		= 	date('Y-m-d H:i:s');
			
			$intCenterID = $this->itemcolor_model->insert($arrHeader);
			$this->Page->setMessage('REC_ADD_MSG');
        }
		elseif ($strAction == 'E')
		{
            $arrHeader['updateby'] 		= 	$this->Page->getSession("intUserId");
            $arrHeader['updatedate'] =	date('Y-m-d H:i:s');
			
            $this->itemcolor_model->update($arrHeader, array('item_color_id' => $item_color_id));
            $this->Page->setMessage('REC_EDIT_MSG');
        }
		
		redirect('c=itemcolor', 'location');
	}
	
	public function delete()
	{
		$arrColorIds	=	$this->input->post('chk_lst_list1');
		$strColorIds	=	implode(",", $arrColorIds);
		$strQuery = "DELETE FROM item_color_master WHERE item_color_id IN (". $strColorIds .")";
		$this->db->query($strQuery);
		$this->Page->setMessage("DELETE_RECORD");
		// redirect to listing screen
		redirect('c=itemcolor', 'location');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */