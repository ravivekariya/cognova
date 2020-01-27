<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Process extends CI_Controller {


    function __construct()
    {
        parent::__construct();
        $this->load->model("Process_model",'process_model',true);
    }

    public function index()
    {
        // Get All process
        $searchCriteria	=	array();
        $searchCriteria['orderField'] = 'created_at';
        $searchCriteria['orderDir'] = 'DESC';
        $this->process_model->searchCriteria = $searchCriteria;
        $rsProcess = $this->process_model->getDetails();
        $rsListing['rsProcess']	=	$rsProcess;
        $rsListing['strMessage']	=	$this->Page->getMessage();;

        // Load Views
        $this->load->view('process/list', $rsListing);
    }

    public function AddProcess()
    {
        $data["strAction"] = $this->Page->getRequest("action");
        $data["strMessage"] = $this->Page->getMessage();
        $data["id"] = $this->Page->getRequest("id");

        if ($data["strAction"] == 'E' || $data["strAction"] == 'V' || $data["strAction"] == 'R')
        {
            $data["rsEdit"] = $this->process_model->get_by_id('id', $data["id"]);
        }
        else
        {
            $data["strAction"] = "A";
        }

        $this->load->view('process/processForm',$data);
    }

    public function save()
    {
        $strAction = $this->input->post('action');
        $id   = $this->Page->getRequest('process_id');

        // Check Duplicate entry
        $searchCriteria = array();
        $searchCriteria["selectField"] = "pm.id";
        $searchCriteria["name"] = $this->Page->getRequest('txt_name');
        if ($strAction == 'E')
        {
            $searchCriteria["not_id"] = $id;
        }
        $this->process_model->searchCriteria=$searchCriteria;
        $rsProcess = $this->process_model->getDetails();
        if(count($rsProcess) > 0)
        {
            $this->Page->setMessage('ALREADY_EXISTS');
            redirect('c=Process&m=AddProcess&action=E&id='.$id, 'location');
        }

        $arrHeader["name"]     	=	$this->Page->getRequest('txt_name');
        $arrHeader["description"]        =   $this->Page->getRequest('txt_desc');
        $arrHeader["status"]        	= 	$this->Page->getRequest('slt_status');

        if ($strAction == 'A' || $strAction == 'R')
        {
            $arrHeader['created_by']		=	$this->Page->getSession("intUserId");
            $arrHeader['created_at'] 		= 	time();
            $arrHeader['updated_at'] 		= 	time();

            $intCenterID = $this->process_model->insert($arrHeader);
            $this->Page->setMessage('REC_ADD_MSG');
        }
        elseif ($strAction == 'E')
        {
            $arrHeader['updated_by'] 		= 	$this->Page->getSession("intUserId");
            $arrHeader['updated_at'] =	time();

            $this->process_model->update($arrHeader, array('id' => $id));
            $this->Page->setMessage('REC_EDIT_MSG');
        }

        redirect('c=Process', 'location');
    }

    public function delete()
    {
        $arrIds	=	$this->input->post('chk_lst_list1');
        $strIds	=	implode(",", $arrIds);
        $strQuery = "DELETE FROM process WHERE id IN (". $strIds .")";
        $this->db->query($strQuery);
        $this->Page->setMessage("REC_DEL_MSG");
        // redirect to listing screen
        redirect('c=Process', 'location');
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */