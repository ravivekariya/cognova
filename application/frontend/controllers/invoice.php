<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class invoice extends CI_Controller {
 
	function __construct()  
	{
		parent::__construct();
		$this->load->model("order_model",'',true);
		$this->load->model("vendor_model",'',true);
		$this->load->model("invoice_model",'',true);
		$this->load->model("Process_model",'process_model',true);
		$this->load->model("page",'',true);
		$this->load->library('My_PHPMailer');
	}

	### Auther : Nikunj Bambhroliya
	### Desc : Generate invoice for client order
	public function generateInvoice()
	{
		$orderId = $this->Page->getRequest("orderId");
		$isPdf = $this->Page->getRequest("isPdf");

		// generate process array
        $searchCriteria = array();
        $searchCriteria['selectField'] = 'pm.id, pm.name';
        $this->process_model->searchCriteria = $searchCriteria;
        $processArr = $this->process_model->getDetails();

        $processA = [];
        if(is_array($processArr) && count($processArr))
        {
            foreach ($processArr AS $record)
            {
                $processA[$record['id']] = $record['name'];
            }
        }

		// Get Order Details
		$searchCriteria = array();
		$searchCriteria['order_id'] = $orderId;
		$searchCriteria['fetchProductDetail'] = 1;
		$searchCriteria['fetchOrderStatus'] = 0;
		$this->order_model->searchCriteria = $searchCriteria;
		$orderDetailArr = $this->order_model->getOrderDetails();
		$orderDetailArr = $orderDetailArr[0];
		$rsListing['orderDetailArr'] = $orderDetailArr;
		//$this->Page->pr($orderDetailArr); exit;

		// Get Customer Details
		$searchCriteria = array();
		$searchCriteria['vendorId'] = $orderDetailArr["customer_id"];
		$this->vendor_model->searchCriteria = $searchCriteria;
		$vendorArr = $this->vendor_model->getVendor();

		$rsListing['vendorArr'] = $vendorArr[0];
		$rsListing['totalAmountInWords'] = $this->page->convert_digit_to_words($orderDetailArr['final_total_amount']);
		$rsListing['processA'] = $processA;

		if($isPdf != 1){
			$this->load->view('invoice/viewInvoice', $rsListing);
		}
		else{
			$this->load->view('invoice/viewPdfHtml', $rsListing);
		}
	}

	### Auther : Nikunj Bambhroliya
	### Desc : Generate PDF for invoice
	public function generatePdf()
	{
		$orderId = $this->Page->getRequest("orderId");
		$dir = "./upload/invoice/pdf";
		$file_name = "invoice_pdf_fl_".$orderId.".pdf";
		$fullpath = $dir."/".$file_name;
		if(!is_dir($dir)){
			mkdir($dir,0755,true);
		}

		if(file_exists($fullpath))
		{
			unlink($fullpath);
		}
		$url = "http://".$_SERVER['HTTP_HOST']."/oms_bhavik/index.php?c=invoice&m=generateInvoice&orderId=".$orderId."&isPdf=1";
		$temp = array();
		exec('"D:\wkhtmltopdf\bin\wkhtmltopdf.exe" "'.$url.'" "'.$fullpath.'"',$temp);
		//var_dump($temp);
		redirect("http://".$_SERVER['HTTP_HOST']."/oms_bhavik/".$fullpath."","");
	}


	### Auther : Snehal Trapsiya
	### Desc : Generate Mail Form
	public function generateMailForm()
	{
		$orderId = $this->Page->getRequest("orderId");
		
		// Get Order Details
		$searchCriteria = array();
		$searchCriteria['order_id'] = $orderId;
		$searchCriteria['fetchProductDetail'] = 1;
		$searchCriteria['fetchOrderStatus'] = 1;
		$this->order_model->searchCriteria = $searchCriteria;
		$orderDetailArr = $this->order_model->getOrderDetails();
		$orderDetailArr = $orderDetailArr[0];
		$rsListing['orderDetailArr'] = $orderDetailArr;
		//$this->Page->pr($orderDetailArr); exit;
		
		$order_no = $orderDetailArr["order_no"];
		$order_date = $orderDetailArr["order_date"];
		// Get Customer Details
		$searchCriteria = array();
		$searchCriteria['vendorId'] = $orderDetailArr["customer_id"];
		$this->vendor_model->searchCriteria = $searchCriteria;
		$vendorArr = $this->vendor_model->getVendor();
		$rsListing['vendorArr'] = $vendorArr;
		//$this->Page->pr($vendorArr); exit;
		
		$vendor_email = $vendorArr[0]["vendor_email"];
		$vendor_name = $vendorArr[0]["vendor_name"]; 
		
		$arrTemplate	=	$this->Page->getEmailTemplate("INVOICE_EMAIL");
		$subject = $arrTemplate['subject'];
		$message = $arrTemplate['description'];
		
		$message = str_replace('{CUSTOMER_NAME}' , $vendor_name , $message);
		$message = str_replace('{ORDER_NO}' , $order_no , $message);
		$message = str_replace('{ORDER_DATE}' , $order_date , $message);
		
		
		$from_mail	=	$this->Page->getSetting("FYI_FROM_EMAIL");
		
		### Generate Invoice
		// check invoice entry
		$searchCriteria = array();
		$searchCriteria['orderId'] = $orderId;
		$this->invoice_model->searchCriteria = $searchCriteria;
		$invoiceDetailArr = $this->invoice_model->getInvoiceDetails();
		if(count($invoiceDetailArr) == 0)
		{
			// invoice entry
			$arrData = array();
			$arrData['invoice_no'] = $this->invoice_model->generateInvoiceNo();;
			$arrData['order_id'] = $orderId;
			$arrData['invoice_date'] = date("Y-m-d h:i:s");
			$arrData['created_by'] = $this->Page->getSession("intUserId");

			$this->invoice_model->tbl = "invoice";
			$this->invoice_model->insert($arrData);
		}

		// Get Order Details
		$searchCriteria = array();
		$searchCriteria['order_id'] = $orderId;
		$searchCriteria['fetchProductDetail'] = 1;
		$searchCriteria['fetchOrderStatus'] = 1;
		$this->order_model->searchCriteria = $searchCriteria;
		$orderDetailArr = $this->order_model->getOrderDetails();
		$orderDetailArr = $orderDetailArr[0];
		$rsListing['orderDetailArr'] = $orderDetailArr;
		//$this->Page->pr($orderDetailArr); exit;

		// Get Customer Details
		$searchCriteria = array();
		$searchCriteria['vendorId'] = $orderDetailArr["customer_id"];
		$this->vendor_model->searchCriteria = $searchCriteria;
		$vendorArr = $this->vendor_model->getVendor();
		$rsListing['vendorArr'] = $vendorArr[0];

		## Generate PDF
		
		$dir = "./upload/invoice/pdf";
		$file_name = "invoice_pdf_fl_".$orderId.".pdf";
		$fullpath = $dir."/".$file_name;
		if(!is_dir($dir)){
			mkdir($dir,0755,true);
		}

		if(file_exists($fullpath))
		{
			unlink($fullpath);
		}
		$url = "http://".$_SERVER['HTTP_HOST']."/otsinv/index.php?c=invoice&m=generateInvoice&orderId=".$orderId."&isPdf=1";
		$temp = array();
		exec('"D:\wkhtmltopdf\bin\wkhtmltopdf.exe" "'.$url.'" "'.$fullpath.'"',$temp);
		
		$rsListing['cust_email'] = $cust_email;
		$rsListing['from_mail'] = $from_mail;
		$rsListing['subject'] = $subject;
		$rsListing['message'] = $message;
		$this->load->view('invoice/viewEmailForm', $rsListing);
		
	}
	
    public function send_invoice() {
		
		$to	= $this->Page->getRequest('to');
		$from = $this->Page->getRequest('from');
		$cc = $this->Page->getRequest('cc');
		$subject = $this->Page->getRequest('subject');
		$hideditor = $this->Page->getRequest('hideditor');
		$order_id = $this->Page->getRequest('order_id');
				
		//$invoice = $_FILES['invoice']['name'];
		
		$invoice_file = "invoice_pdf_fl_".$order_id.".pdf";
		$path = "./upload/invoice/pdf/".$invoice_file;
		
		$mail = new PHPMailer();
		$mail->IsSMTP(); // we are going to use SMTP
		$mail->SMTPAuth   = true; // enabled SMTP authentication
		$mail->SMTPSecure = "ssl";  // prefix for secure protocol to connect to the server
		$mail->Host       = "smtp.gmail.com";      // setting GMail as our SMTP server
		$mail->Port       = 465;                   // SMTP port to connect to GMail
		$mail->Username   = "snehal.trapsiya@gmail.com";  // user email address
		$mail->Password   = "snehal2993";            // password in GMail
		$mail->SetFrom($from, 'OTSINV');  //Who is sending the email
		$mail->AddReplyTo($from,"OTSINV");  //email address that receives the response
		$mail->Subject    = $subject;
		$mail->Body      = $hideditor;
		$mail->AltBody    = "PHP mailer test message";
		$destino = $to; // Who is addressed the email to
		$mail->AddAddress($destino, "Snehal Trapsiya");

		$mail->AddAttachment($path);      // some attached files
		$mail->AddAttachment("images/phpmailer_mini.gif"); // as many as you want
		if(!$mail->Send()) {
			$data["message"] = "Error: " . $mail->ErrorInfo;
		} else {
			redirect('c=invoice&m=generateInvoice&orderId='.$order_id, 'location');
		}
	}	
	


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */