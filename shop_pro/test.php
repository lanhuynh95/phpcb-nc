<?php
/**
 * BoAuthentication class
 * @category controller
 * @package Bitone
 * @subpackage modules
 * @author Osiz Technologies Pvt Ltd
 * @link http://osiztechnologies.com/
 */

class BoUser extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->library('session');
		$loggedUserId = $this->session->userdata('loggedJTEAdminUserID');

		if (isset($loggedUserId) && !empty($loggedUserId)) {

		} else {

			redirect('NgzgHPuFEZ', 'refresh');
		}

		$this->load->library('form_validation');
		$this->load->library('excel');
		$this->load->library('Pdf');
		//$this->load->model('CommonModel');
		$this->load->model('BoLoginModel');
		$this->load->database();
		$this->load->helper('url');
		$ip = $_SERVER['REMOTE_ADDR'];
		$getParticularIP = $this->BoLoginModel->getParticularIP($ip);
		if ($getParticularIP == 1) {

		}
	}

	function view() {

		$admin_id = $this->session->userdata('loggedJTEAdminUserID');
		$condition = array("admin_id" => $admin_id);
		$admindetals = $this->CommonModel->getTableData("sub_admin_permissions", $condition)->row();

		if ($admindetals->user_details == 0) {

			$this->session->set_flashdata("error", "Access denied");
			redirect("BoDashboard");

		}

		$order_by = array("user_id", "desc");
		$data['user_details'] = $this->CommonModel->getTableData("userdetails", '', $order_by);
		$this->load->view("admin/user/view_user_list", $data);

	}

	function change_user_status($id = "") {
		$admin_id = $this->session->userdata('loggedJTEAdminUserID');
		$condition = array("admin_id" => $admin_id);
		$admindetals = $this->CommonModel->getTableData("sub_admin_permissions", $condition)->row();

		if ($admindetals->user_details == 0) {

			$this->session->set_flashdata("error", "Access denied");
			redirect("BoDashboard");

		}

		$userid = insep_decode($id);
		$condition = array("user_id" => $userid);
		$user_details = $this->CommonModel->getTableData("userdetails", $condition);
		if ($user_details->num_rows() == 1) {
			$user_details = $user_details->row();
			if ($user_details->user_status == 1) {

				$data['user_status'] = 0;
				$this->session->set_flashdata("success", "User status deactivated successfully");
			} else {
				$data['user_status'] = 1;
				$this->session->set_flashdata("success", "User status activated successfully");
			}

			$this->CommonModel->updateTableData("userdetails", $data, $condition);

		} else {
			$this->session->set_flashdata("error", "Invalid link");
		}

		redirect('BoUser/view');

	}

	function DetailsUser($id = "") {
	  
		$admin_id = $this->session->userdata('loggedJTEAdminUserID');
		$condition = array("admin_id" => $admin_id);
		$admindetals = $this->CommonModel->getTableData("sub_admin_permissions", $condition)->row();

		if ($admindetals->user_details == 0) {

			$this->session->set_flashdata("error", "Access denied");
			redirect("BoDashboard");

		}

		$userid = insep_decode($id);

		$condition = array("user_id" => $userid);

		if ($this->input->post("updatenote")) {

			$updatedata["usernote"] = $this->input->post("usernote");
			$this->CommonModel->updateTableData("userdetails", $updatedata, $condition);

		}

		$user_details = $this->CommonModel->getTableData("userdetails", $condition);
		if ($user_details->num_rows() == 1) {
			$user_details = $user_details->row();

			$data['user_data'] = $user_details;

			$data['kyc_data'] = $user_details = $this->CommonModel->getTableData("user_verification", $condition);

			// wizcast add 2018/08/02
			$dob = $data['user_data']->dob;
			$dob = explode("/", $dob);
			$str = $dob[1] . "-" . $dob[0] . "-" . $dob[2];
			log_message('info','AGE_str:'.$str);
			$data['user_data']->age = findage($str);

			log_message('info','AGE_data:'.$data['user_data']->age);
			// wizcast add 

			$this->load->view("admin/user/editusers", $data);

		} else {
			$this->session->set_flashdata("error", "Invalid link");
		}

		//redirect('BoUser/view');

	}
	public function deposit($id = ""){
		$admin_id = $this->session->userdata('loggedJTEAdminUserID');
		$condition = array("admin_id" => $admin_id);
		$admindetals = $this->CommonModel->getTableData("sub_admin_permissions", $condition)->row();

		if ($admindetals->user_details == 0) {

			$this->session->set_flashdata("error", "Access denied");
			redirect("BoDashboard");
		}
		$userid = insep_decode($id);
		$data = array();
		
		$data['tab_type'] = 1;
		
		$condition = array();
		$condition["user_id"] = $userid;
		$condition["type"] = 1;
		
		$post = $this->input->post();
		$get = $this->input->get();
		$input = array_merge($post, $get);
		
		$sort_array = [
			1 => 'requested_time',
			2 => 'currency',
			3 => 'transactionId',
			4 => 'total_amount',
			5 => 'status',
		];
		if (isset($input['sort']) && !empty($input['sort'])) {
			if(isset($sort_array[abs($input['sort'])])){
				if($input['sort'] > 0){
					$order_by = array($sort_array[abs($input['sort'])], "asc");
				}else{
					$order_by = array($sort_array[abs($input['sort'])], "desc");
				}
			}
		}
		if(!isset($order_by)){
			$input['sort'] = "-1";
			$order_by = array($sort_array[1], "desc");
		}
		
		$data['from_date'] = '';
		if(isset($input['from_date']) && !empty($input['from_date'])){
			$data['from_date'] = $input['from_date'];
			$fromdate_array = explode("/", $input['from_date']);
			$fromdate = $fromdate_array[2] . "-" . $fromdate_array[0] . "-" . $fromdate_array[1] . " " . "00:00:00";
			$todate = $this->input->post("dep_to_date");
			
			$condition["requested_time >="] = $fromdate;
		}
		
		$data['to_date'] = '';
		if(isset($input['to_date']) && !empty($input['to_date'])){
			$data['to_date'] = $input['to_date'];
			$todate_array = explode("/", $input['to_date']);
			$todate = $todate_array[2] . "-" . $todate_array[0] . "-" . $todate_array[1] . " " . "23:59:59";
			
			$condition["requested_time <="] = $todate;
		}
		
		$perpage = frontPageMax();
		$page_count = $this->uri->segment(4);
		$page_url = $this->uri->segment(3);
		if(is_null($page_count)){
			$page_count = 0;
		}

		
		if(array_key_exists('export_excel',$_POST)){
			 $fileName = 'data-' . time() . '.xlsx';

		$data1= $this->CommonModel->getTableData('tansation', $condition)->result_array();
        // load excel library
        // $this->load->library('excel');
        // $data = $this->BoLoginModel->tradehistory_exel($userid);
        //Khởi tạo đối tượng
        $excel = new PHPExcel();
		//Chọn trang cần ghi (là số từ 0->n)
        $excel->setActiveSheetIndex(0);
		//Tạo tiêu đề cho trang. (có thể không cần)
        $excel->getActiveSheet()->setTitle('demo ghi dữ liệu');

		//Xét chiều rộng cho từng, nếu muốn set height thì dùng setRowHeight()
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
        



        $excel->getActiveSheet()->setCellValue('A1', 'Date & Time');
        $excel->getActiveSheet()->setCellValue('B1', 'Currency');
        $excel->getActiveSheet()->setCellValue('C1', 'transaction ID');
        $excel->getActiveSheet()->setCellValue('D1', 'Deposit Amount');
        $excel->getActiveSheet()->setCellValue('E1', 'Ation');
		// thực hiện thêm dữ liệu vào từng ô bằng vòng lặp
		// dòng bắt đầu = 2
		
        $numRow = 2;
        foreach ($data1 as $row) {
            $excel->getActiveSheet()->setCellValue('A' . $numRow, $row['requested_time']);
            $excel->getActiveSheet()->setCellValue('B' . $numRow, $row['currency']);
            $excel->getActiveSheet()->setCellValue('C' . $numRow, $row['transactionId']);

            $amount = number_format($row['total_amount'], 9);
            $amount = substr($amount, 0, strlen($amount) -1);
            $excel->getActiveSheet()->setCellValueExplicit(
				    'D'.$numRow,
				    $amount
				);
            $excel->getActiveSheet()->setCellValue('E' . $numRow, $row['status']);
            $numRow++;
        }
        PHPExcel_IOFactory::createWriter($excel, 'Excel2007')->save('uploads/data.xlsx');
        return redirect('uploads/data.xlsx');
    }


		if(array_key_exists('btn_pdf',$_POST)){
		$data1['data']= $this->CommonModel->getTableData('tansation', $condition)->result_array();
		
		foreach ($data1['data'] as $key => $value) {
			$amount = number_format($value['total_amount'], 9);
            $data1['data'][$key]['total_amount']=substr($amount, 0, strlen($amount) -1);



		}
		$data1['list_head']=['Date & Time','Currency','transaction ID','Deposit Amount','Ation'];
		$data1['list_key']=['requested_time','currency','transactionId','total_amount','status'];
		$data1['title']='DEPOSIT';
		$this->createPDF($data1);
		exit();

		}









		$data['page_count'] = $page_count;
		$base = base_url()."BoUser/deposit/".$page_url ;
		$data['url'] = $base;

		$activity_count = $this->CommonModel->getTableData('tansation', $condition, $order_by, 'count(*) as cnt')->row()->cnt;
		pageconfig($activity_count, $base, $perpage);

		$data["deposit"] = $this->CommonModel->getTableData('tansation', $condition, $order_by, '', '', '', '', $page_count, $perpage);
		$data["deposit2"] = 'No Data Available In Table'; 
		$data['now_sort'] = 0;
		if(isset($input['sort'])){
			$data['now_sort'] = $input['sort'];
			unset($input['sort']);
		}
		$now_url = $base.'/'.$page_count.'?';
		$now_url .= http_build_query($input);
		$data['now_url'] = $now_url;
		
		$data['pagination'] = $this->pagination->create_links();

		$this->load->view("admin/user/editusers", $data);
	}
	public function withdraw($id = "") {
		$admin_id = $this->session->userdata('loggedJTEAdminUserID');
		$condition = array("admin_id" => $admin_id);
		$admindetals = $this->CommonModel->getTableData("sub_admin_permissions", $condition)->row();

		if ($admindetals->user_details == 0) {

			$this->session->set_flashdata("error", "Access denied");
			redirect("BoDashboard");

		}
		$userid = insep_decode($id);
		$data = array();
		$data['tab_type'] = 2;

		$condition = array();
		$condition["user_id"] = $userid;
		$condition["type"] = 2;
		
		$post = $this->input->post();
		$get = $this->input->get();
		$input = array_merge($post, $get);
		
		$sort_array = [
			1 => 'requested_time',
			2 => 'currency',
			3 => 'transation_hash',
			4 => 'transactionId',
			5 => 'total_amount',
			6 => 'fee',
			7 => 'transfer_amount',
			8 => 'status',
		];
		if (isset($input['sort']) && !empty($input['sort'])) {
			if(isset($sort_array[abs($input['sort'])])){
				if($input['sort'] > 0){
					$order_by = array($sort_array[abs($input['sort'])], "asc");
				}else{
					$order_by = array($sort_array[abs($input['sort'])], "desc");
				}
			}
		}
		
		if(!isset($order_by)){
			$input['sort'] = "-1";
			$order_by = array($sort_array[1], "desc");
		}
		
		$data['from_date'] = '';
		if(isset($input['from_date']) && !empty($input['from_date'])){
			$data['from_date'] = $input['from_date'];
			$fromdate_array = explode("/", $input['from_date']);
			$fromdate = $fromdate_array[2] . "-" . $fromdate_array[0] . "-" . $fromdate_array[1] . " " . "00:00:00";
			$todate = $this->input->post("dep_to_date");
			
			$condition["requested_time >="] = $fromdate;
		}
		
		$data['to_date'] = '';
		if(isset($input['to_date']) && !empty($input['to_date'])){
			$data['to_date'] = $input['to_date'];
			$todate_array = explode("/", $input['to_date']);
			$todate = $todate_array[2] . "-" . $todate_array[0] . "-" . $todate_array[1] . " " . "23:59:59";
			
			$condition["requested_time <="] = $todate;
		}
		

		$perpage = frontPageMax();
		$page_count = $this->uri->segment(4);
		$page_url = $this->uri->segment(3);
		if(is_null($page_count)){
			$page_count = 0;
		}
		


		if(array_key_exists('export_excel',$_POST)){
			
			 $fileName = 'data-' . time() . '.xlsx';
		$data1= $this->CommonModel->getTableData('tansation', $condition)->result_array(); 
        // load excel library
        // $this->load->library('excel');
        // $data = $this->BoLoginModel->tradehistory_exel($userid);
        //Khởi tạo đối tượng
        $excel = new PHPExcel();
		//Chọn trang cần ghi (là số từ 0->n)
        $excel->setActiveSheetIndex(0);
		//Tạo tiêu đề cho trang. (có thể không cần)
        $excel->getActiveSheet()->setTitle('demo ghi dữ liệu');

		//Xét chiều rộng cho từng, nếu muốn set height thì dùng setRowHeight()
         $excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
        $excel->getActiveSheet()->getColumnDimension('H')->setWidth(30);


        $excel->getActiveSheet()->setCellValue('A1', 'Date & Time');
        $excel->getActiveSheet()->setCellValue('B1', 'Currency');
        $excel->getActiveSheet()->setCellValue('C1', 'Blockchain Tax id');
        $excel->getActiveSheet()->setCellValue('D1', 'Transaction ID');
        $excel->getActiveSheet()->setCellValue('E1', 'Withdraw Amount');
        $excel->getActiveSheet()->setCellValue('F1', 'Fees');
        $excel->getActiveSheet()->setCellValue('G1', 'Receive Amount');
        $excel->getActiveSheet()->setCellValue('H1', 'Ation');
		// thực hiện thêm dữ liệu vào từng ô bằng vòng lặp
		// dòng bắt đầu = 2
        $numRow = 2;
        foreach ($data1 as $row) {
            $excel->getActiveSheet()->setCellValue('A' . $numRow, $row['requested_time']);
            $excel->getActiveSheet()->setCellValue('B' . $numRow, $row['currency']);
            $excel->getActiveSheet()->setCellValue('C' . $numRow, $row['transation_hash']);
            $excel->getActiveSheet()->setCellValue('D' . $numRow, $row['transactionId']);

            $amount = number_format($row['total_amount'], 9);
			$amount = substr($amount, 0, strlen($amount) -1); 
            // $excel->getActiveSheet()->setCellValue('D' . $numRow, $amount);
            $excel->getActiveSheet()->setCellValueExplicit(
				    'E'.$numRow,
				    $amount );

            $amount1 = number_format($row['fee'], 9);
            $amount1 = substr($amount1, 0, strlen($amount1) -1);
            //$excel->getActiveSheet()->setCellValue('D' . $numRow, $amount1);
            $excel->getActiveSheet()->setCellValueExplicit(
				    'F'.$numRow,
				    $amount1 );

            $amount2 = number_format($row['transfer_amount'], 9);
            $amount2 = substr($amount2, 0, strlen($amount2) -1);
            // $excel->getActiveSheet()->setCellValue('D' . $numRow, $amount2);
            $excel->getActiveSheet()->setCellValueExplicit(
				    'G'.$numRow,
				    $amount2 );

            $excel->getActiveSheet()->setCellValue('H' . $numRow, $row['status']);
            $numRow++;
         
        }
             
		// Khởi tạo đối tượng PHPExcel_IOFactory để thực hiện ghi file
	// ở đây mình lưu file dưới dạng excel2007
      PHPExcel_IOFactory::createWriter($excel, 'Excel2007')->save('uploads/data.xlsx');
        return redirect('uploads/data.xlsx');
    	}


		if(array_key_exists('btn_pdf',$_POST)){

		$data1['data']= $this->CommonModel->getTableData('tansation', $condition)->result_array();
		foreach ($data1['data'] as $key => $value) {
			$amount = number_format($value['total_amount'], 9);
            $data1[$key]['total_amount']=substr($amount, 0, strlen($amount) -1);

            $amount1 = number_format($value['fee'], 9);
            $data1[$key]['fee']=substr($amount1, 0, strlen($amount1) -1);

            $amount2 = number_format($value['transfer_amount'], 9);
             $data1[$key]['transfer_amount']=substr($amount2, 0, strlen($amount2) -1);
		}
		$data1['list_head']=['Date & Time','Currency','transaction ID','Withdraw Amount','Fees','Receive Amount','Status'];
		$data1['list_key']=['requested_time','currency','transactionId','total_amount','fee','transfer_amount','status'];
		$data1['title']='WITHDRAW';
		$this->createPDF($data1);
		exit();


		}




		$data['page_count'] = $page_count;
		$base = base_url()."BoUser/withdraw/".$page_url;
		$data['url'] = $base;

		$activity_count = $this->CommonModel->getTableData('tansation', $condition, $order_by, 'count(*) as cnt')->row()->cnt;
		pageconfig($activity_count, $base, $perpage);

		$data["withdraw"] = $this->CommonModel->getTableData('tansation', $condition, $order_by, '', '', '', '', $page_count, $perpage);
		$data2 = $this->CommonModel->getTableData('tansation', $condition, $order_by, '', '', '', '');


		$data['now_sort'] = 0;
		if(isset($input['sort'])){
			$data['now_sort'] = $input['sort'];
			unset($input['sort']);
		}
		$now_url = $base.'/'.$page_count.'?';
		$now_url .= http_build_query($input);
		$data['now_url'] = $now_url;
		
		$data['pagination'] = $this->pagination->create_links();


		$this->load->view("admin/user/editusers", $data);
	}


	public function orderHistory($id = ""){
		$admin_id = $this->session->userdata('loggedJTEAdminUserID');
		$condition = array("admin_id" => $admin_id);
		$admindetals = $this->CommonModel->getTableData("sub_admin_permissions", $condition)->row();

		if ($admindetals->user_details == 0) {

			$this->session->set_flashdata("error", "Access denied");
			redirect("BoDashboard");

		}
		$userid = insep_decode($id);
		$data = array();
		$data['tab_type'] = 4;
		$this->load->model('common_model');

		$this->db->order_by('datetime', 'desc');

		$condition = array();
		$condition["a.userId"] = $userid;
		
		$hisjoins = array('trade_pairs as b' => 'a.pair = b.id', 'userdetails as c' => 'a.userId = c.user_id', 'currency as d' => 'b.from_symbol_id = d.id', 'currency as e' => 'b.to_symbol_id = e.id');

		$post = $this->input->post();
		$get = $this->input->get();
		$input = array_merge($post, $get);
		
		$sort_array = [
			1 => 'order_no',
			2 => 'orderDate',
			3 => 'Type',
			4 => 'a.pair',
			5 => 'Price',	
			6 => 'Amount',
			7 => 'status',
		];
		if (isset($input['sort']) && !empty($input['sort'])) {
			if(isset($sort_array[abs($input['sort'])])){
				if($input['sort'] > 0){
					$order_by = array($sort_array[abs($input['sort'])], "asc");
				}else{
					$order_by = array($sort_array[abs($input['sort'])], "desc");
				}
			}
		}
		
		if(!isset($order_by)){
			$input['sort'] = "-1";
			$order_by = array($sort_array[1], "desc");
		}
		
		$data['from_date'] = '';
		if(isset($input['from_date']) && !empty($input['from_date'])){
			$data['from_date'] = $input['from_date'];
			$fromdate_array = explode("/", $input['from_date']);
			$fromdate = $fromdate_array[2] . "-" . $fromdate_array[0] . "-" . $fromdate_array[1] . " " . "00:00:00";
			$todate = $this->input->post("dep_to_date");
			
			$condition["tradetime >="] = $fromdate;
		}
		
		$data['to_date'] = '';
		if(isset($input['to_date']) && !empty($input['to_date'])){
			$data['to_date'] = $input['to_date'];
			$todate_array = explode("/", $input['to_date']);
			$todate = $todate_array[2] . "-" . $todate_array[0] . "-" . $todate_array[1] . " " . "23:59:59";
			
			$condition["tradetime <="] = $todate;
		}

		$perpage = frontPageMax();
		$page_count = $this->uri->segment(4);
		$page_url = $this->uri->segment(3);
		if(is_null($page_count)){
			$page_count = 0;
		}
		$data['page_count'] = $page_count;
		$base = base_url()."BoUser/orderHistory/".$page_url;
		$data['url'] = $base;
		$data['search_string'] = '';

		$activity_count = $this->common_model->getJoinedTableData('coin_order as a', $hisjoins, $condition, 'count(*) as cnt')->row()->cnt;
		pageconfig($activity_count, $base, $perpage);

		$data["order_history"] = $this->common_model->getJoinedTableData('coin_order as a', $hisjoins, $condition, 'a.*,b.from_symbol_id as from_currency_id, b.to_symbol_id as to_currency_id, c.username as username, d.currency_symbol as from_currency_symbol, e.currency_symbol as to_currency_symbol', '', '', '', $page_count, $perpage, $order_by);
		$data2 = $this->common_model->getJoinedTableData('coin_order as a', $hisjoins, $condition, 'a.*,b.from_symbol_id as from_currency_id, b.to_symbol_id as to_currency_id, c.username as username, d.currency_symbol as from_currency_symbol, e.currency_symbol as to_currency_symbol', '', '', '');



		if(array_key_exists('export_excel',$_POST)){

			// create file name
        $fileName = 'data-' . time() . '.xlsx';
        // load excel library
        // $this->load->library('excel');
        // $data = $this->BoLoginModel->tradehistory_exel($userid);
        //Khởi tạo đối tượng
        $excel = new PHPExcel();
		//Chọn trang cần ghi (là số từ 0->n)
        $excel->setActiveSheetIndex(0);
		//Tạo tiêu đề cho trang. (có thể không cần)
        $excel->getActiveSheet()->setTitle('demo ghi dữ liệu');

		//Xét chiều rộng cho từng, nếu muốn set height thì dùng setRowHeight()
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(30);


        $excel->getActiveSheet()->setCellValue('A1', 'Order No');
        $excel->getActiveSheet()->setCellValue('B1', 'Date & Time');
        $excel->getActiveSheet()->setCellValue('C1', 'Type');
        $excel->getActiveSheet()->setCellValue('D1', 'Pair');
        $excel->getActiveSheet()->setCellValue('E1', 'Price');
        $excel->getActiveSheet()->setCellValue('F1', 'Amount');
        $excel->getActiveSheet()->setCellValue('G1', 'Status');
		// thực hiện thêm dữ liệu vào từng ô bằng vòng lặp
		// dòng bắt đầu = 2
		
        $numRow = 2;

        foreach ($data2->result_array() as $key=> $row) {
         
            $excel->getActiveSheet()->setCellValueExplicit(
				    'A'.$numRow,
				    $row['order_no']
				    
				);
           
            $excel->getActiveSheet()->setCellValue('B' . $numRow, $row['orderDate'].' '.$row['orderTime']);
            $excel->getActiveSheet()->setCellValue('C' . $numRow, $row['Type']);
            $excel->getActiveSheet()->setCellValue('D' . $numRow, $row['from_currency_symbol'] );

			 $price = number_format($row['Price'], 9);
             $price=substr($price, 0, strlen($price) -1);
			$amount = number_format($row['Amount'], 9);
              $amount=substr($amount, 0, strlen($amount) -1);

            $excel->getActiveSheet()->setCellValueExplicit(
				    'E'.$numRow,
				    $price
				    
				);

                    $amount1 = substr($amount, 0, strlen($amount) -1);
            $excel->getActiveSheet()->setCellValueExplicit(
				    'F'.$numRow,
				    $amount

				);
            $excel->getActiveSheet()->setCellValue('G' . $numRow, $row['status']);
            $numRow++;
         
        }
		// Khởi tạo đối tượng PHPExcel_IOFactory để thực hiện ghi file
	// ở đây mình lưu file dưới dạng excel2007
      PHPExcel_IOFactory::createWriter($excel, 'Excel2007')->save('uploads/data.xlsx');
        return redirect('uploads/data.xlsx');
    
		}

         if(array_key_exists('btn_pdf',$_POST)){

		$data1['data']= $this->common_model->getJoinedTableData('coin_order as a', $hisjoins, $condition, 'a.*,b.from_symbol_id as from_currency_id, b.to_symbol_id as to_currency_id, c.username as username, d.currency_symbol as from_currency_symbol, e.currency_symbol as to_currency_symbol', '', '', '')->result_array();

		foreach ($data1['data'] as $key => $value) {
			$data1['data'][$key]['datetime11'] = $value['orderDate'].' '.$value['orderTime'];
			$data1['data'][$key]['currency'] = $value['from_currency_symbol'].'/'.$value['to_currency_symbol'];

			$amount = number_format($value['Price'], 9);
            $data1['data'][$key]['total_amount']=substr($amount, 0, strlen($amount) -1);

            $amount1 = number_format($value['Amount'], 9);
            $data1['data'][$key]['amount']=substr($amount1, 0, strlen($amount1) -1);

		}
	
		$data1['list_head']=['Order No','Date & Time','Type','Pair','Price','Amount','Status'];
		$data1['list_key']=['order_no','datetime','Type','currency','total_amount','amount','status'];
		$data1['title']='ORDER HISTORY';
		
		$this->createPDF($data1);
		exit(); 
		} 



		$data['now_sort'] = 0;
		if(isset($input['sort'])){
			$data['now_sort'] = $input['sort'];
			unset($input['sort']);
		}
		$now_url = $base.'/'.$page_count.'?';
		$now_url .= http_build_query($input);
		$data['now_url'] = $now_url;
		
		$data['pagination'] = $this->pagination->create_links();


		$this->load->view("admin/user/editusers", $data);
		//$this->load->view("admin/user/detail/orderhistory", $data);
	}

	function update_kyc() {
		$admin_id = $this->session->userdata('loggedJTEAdminUserID');
		$condition = array("admin_id" => $admin_id);
		$admindetals = $this->CommonModel->getTableData("sub_admin_permissions", $condition)->row();

		if ($admindetals->user_details == 0) {

			$this->session->set_flashdata("error", "Access denied");
			redirect("BoDashboard");

		}

		$status = $this->input->post("status");
		$user_id = $this->input->post("userid");
		$doc = $this->input->post("doc");
		$userdata = get_user($user_id);
		$name = $userdata->username;
		$site_data = site_settings();
		$sitename = $site_data->site_name;

		if ($status == 3) {
			$reason = $this->input->post("reason_text");
			$update['reject_reason'] = $reason;
			$status = "Rejected";
			$email_data = getEmailTeamplete(5);
			$subject = $email_data->subject;
			$template = $email_data->template;
			$data = array(
				"###NAME###" => $name,
				"###SITENAME###" => $sitename,
				"###STATUS###" => $status,
				"###REASON###" => $reason,
			);
			//$message=strtr($template,$data);

			if ($doc == 1) {

				$update['proof1_status'] = 3;
				$data["###DOCUMENT###"] = "Passport(Front)";

				$update['id_proof1_reason'] = $reason;

			} elseif ($doc == 2) {

				$update['proof2_status'] = 3;
				$data["###DOCUMENT###"] = "Passport(Back)";
				$update['id_proof2_reason'] = $reason;

			} elseif ($doc == 3) {

				$update['proof3_status'] = 3;
				$data["###DOCUMENT###"] = "Id proof(Front)";
				$update['id_proof3_reason'] = $reason;

			} elseif ($doc == 4) {

				$update['proof4_status'] = 3;
				$data["###DOCUMENT###"] = "Id proof(Back)";
				$update['id_proof4_reason'] = $reason;

			} elseif ($doc == 5) {

				$update['proof4_status'] = 3;
				$data["###DOCUMENT###"] = "Profile Picture";
				$update['id_proof5_reason'] = $reason;

			}

		} else if ($status == 5) {
			$reason = $this->input->post("reason_text");
			$update['reject_reason'] = $reason;
			$status = "Need info";
			$email_data = getEmailTeamplete(5);
			$subject = $email_data->subject;
			$template = $email_data->template;
			$data = array(
				"###NAME###" => $name,
				"###SITENAME###" => $sitename,
				"###STATUS###" => $status,
				"###REASON###" => $reason,
			);
			//$message=strtr($template,$data);

			if ($doc == 1) {

				$update['proof1_status'] = 5;
				$data["###DOCUMENT###"] = "Passport(Front)";

				$update['id_proof1_reason'] = $reason;

			} elseif ($doc == 2) {

				$update['proof2_status'] = 5;
				$data["###DOCUMENT###"] = "Passport(Back)";
				$update['id_proof2_reason'] = $reason;

			} elseif ($doc == 3) {

				$update['proof3_status'] = 5;
				$data["###DOCUMENT###"] = "Id proof(Front)";
				$update['id_proof3_reason'] = $reason;

			} elseif ($doc == 4) {

				$update['proof4_status'] = 5;
				$data["###DOCUMENT###"] = "Id proof(Back)";
				$update['id_proof4_reason'] = $reason;

			} elseif ($doc == 5) {

				$update['proof4_status'] = 5;
				$data["###DOCUMENT###"] = "Profile Picture";
				$update['id_proof5_reason'] = $reason;

			}

		} else {
			$status = "Approved";
			$reason = $this->input->post("reason_text");
			$email_data = getEmailTeamplete(4);
			$subject = $email_data->subject;
			$template = $email_data->template;
			$data = array(

				"###STATUS###" => $status,
			);
			if ($doc == 1) {

				$update['proof1_status'] = 2;

				$data["###DOCUMENT###"] = "Passport(Front)";

			} elseif ($doc == 2) {

				$update['proof2_status'] = 2;
				$data["###DOCUMENT###"] = "Passport(Back)";

			} elseif ($doc == 3) {

				$update['proof3_status'] = 2;
				$data["###DOCUMENT###"] = "Id proof(Front)";

			} elseif ($doc == 4) {
				$update['proof4_status'] = 2;
				$data["###DOCUMENT###"] = "Id proof(Back)";

			} elseif ($doc == 5) {
				$update['proof5_status'] = 2;
				$data["###DOCUMENT###"] = "Profile picture";

			}
		}

		$data["###NAME###"] = $userdata->username;
		$data["###LOGOIMG###"] = getSiteLogo();
		$data["###EMAILIMG###"] = base_url() . "assets/frontend/images/email_send.png";
		$data["###FBIMG###"] = base_url() . "assets/frontend/images/facebook.png";
		$data["###TWIMG###"] = base_url() . "assets/frontend/images/twitter.png";
		$data["###GPIMG###"] = base_url() . "assets/frontend/images/gplus.png";
		$data["###LEIMG###"] = base_url() . "assets/frontend/images/linkedin.png";
		$data["###HDIMG###"] = base_url() . "assets/frontend/images/email.png";
		$data["###FBLINK###"] = $site_data->facebooklink;
		$data["###TWLINK###"] = $site_data->twitterlink;
		$data["###GPLINK###"] = $site_data->googlelink;
		$data["###LELINK###"] = $site_data->linkedinlink;

		$message = strtr($template, $data);

		$condition = array("user_id" => $user_id);
		$update['verification_status'] = $status;

		$this->CommonModel->updateTableData("user_verification", $update, $condition);

		$kycdata = $this->CommonModel->getTableData("user_verification", $condition)->row();

		if ($kycdata->proof1_status == 2 && $kycdata->proof2_status == 2 && $kycdata->proof3_status == 2) {

			$ky_update["kyc_status"] = "Verified";
			$ky_update["user_status"] = 1;

		} else if ($kycdata->proof1_status == 5 || $kycdata->proof2_status == 5 || $kycdata->proof3_status == 5) {
			$ky_update["kyc_status"] = "Need info";
			$ky_update["user_status"] = 3;
		} else if ($kycdata->proof1_status == 3 || $kycdata->proof2_status == 3 || $kycdata->proof3_status) {
			$ky_update["kyc_status"] = "Rejected";
			$ky_update["user_status"] = 3;

		} else {
			$ky_update["user_status"] = 1;

			$ky_update["kyc_status"] = "unVerified";
		}

		$this->CommonModel->updateTableData("userdetails", $ky_update, $condition);

		$email = get_user_email($user_id);
		send_mail($email, $subject, $message);

		echo "ok";

	}

	function user_wallet() {

		$admin_id = $this->session->userdata('loggedJTEAdminUserID');
		$condition = array("admin_id" => $admin_id);
		$admindetals = $this->CommonModel->getTableData("sub_admin_permissions", $condition)->row();

		if ($admindetals->wallet == 0) {

			$this->session->set_flashdata("error", "Access denied");
			redirect("BoDashboard");

		}

		$order_by = array("user_id", "desc");
		$data['user_details'] = $this->CommonModel->getTableData("userdetails", '', $order_by);
		$this->load->view("admin/user/wallet", $data);

	}

	function view_address($id = "") {

		$admin_id = $this->session->userdata('loggedJTEAdminUserID');
		$condition = array("admin_id" => $admin_id);
		$admindetals = $this->CommonModel->getTableData("sub_admin_permissions", $condition)->row();

		if ($admindetals->wallet == 0) {

			$this->session->set_flashdata("error", "Access denied");
			redirect("BoDashboard");

		}

		$where = array("user_id", "desc");
		$user_id = insep_decode($id);
		$where = array("user_id" => $user_id);
		$userdata = $this->CommonModel->getTableData("userdetails", $where);

		$data["user_data"] = $userdata->row();

		$address = $this->CommonModel->getTableData("address", $where);
		$address = (array) $address->row();
		//XRPのアドレスはadmin_walletのusr_xrpを一律で表示
		$address['XRP'] = $this->CommonModel->getTableData("admin_wallet")->row()->user_xrp;

		$data["address"] = $address;

		$this->load->view("admin/user/wallet_address", $data);

	}

	function view_balance($id = "") {

		$admin_id = $this->session->userdata('loggedJTEAdminUserID');
		$condition = array("admin_id" => $admin_id);
		$admindetals = $this->CommonModel->getTableData("sub_admin_permissions", $condition)->row();

		if ($admindetals->wallet == 0) {

			$this->session->set_flashdata("error", "Access denied");
			redirect("BoDashboard");

		}

		$user_id = insep_decode($id);
		$where = array("user_id" => $user_id);
		$userdata = $this->CommonModel->getTableData("userdetails", $where);
		$data["user_data"] = $userdata->row();
		$balance = $this->CommonModel->getTableData("wallet", $where);
		$balance = (array) $balance->row();
		$data["balance"] = $balance;
		$this->load->view("admin/user/wallet_balance", $data);

	}

	function tfa() {

		$admin_id = $this->session->userdata('loggedJTEAdminUserID');
		$condition = array("admin_id" => $admin_id);
		$admindetals = $this->CommonModel->getTableData("sub_admin_permissions", $condition)->row();

		if ($admindetals->tfa == 0) {

			$this->session->set_flashdata("error", "Access denied");
			redirect("BoDashboard");

		}

		$order_by = array("user_id", "desc");
		$data['user_details'] = $this->CommonModel->getTableData("userdetails", '', $order_by);
		$this->load->view("admin/user/tfa", $data);

	}

	function change_tfa_status($id = "") {

		$admin_id = $this->session->userdata('loggedJTEAdminUserID');
		$condition = array("admin_id" => $admin_id);
		$admindetals = $this->CommonModel->getTableData("sub_admin_permissions", $condition)->row();

		if ($admindetals->tfa == 0) {

			$this->session->set_flashdata("error", "Access denied");
			redirect("BoDashboard");

		}

		$userid = insep_decode($id);
		$condition = array("user_id" => $userid);
		$user_details = $this->CommonModel->getTableData("userdetails", $condition);
		if ($user_details->num_rows() == 1) {
			$user_details = $user_details->row();
			if ($user_details->tfa_status == 0) {

				$this->session->set_flashdata("error", "User tfa already inactive");

			} else {
				$data['tfa_status'] = 0;
				$this->CommonModel->updateTableData("userdetails", $data, $condition);

				$this->session->set_flashdata("success", "User tfa status successfully updated");

			}

		} else {
			$this->session->set_flashdata("error", "Invalid link");
		}

		redirect('BoUser/tfa');

	}

	function request_kyc($user_id = "") {

		$user_id = insep_decode($user_id);

		$condition = array("user_id" => $user_id);

		$requestdata = $this->CommonModel->getTableData("user_verification", $condition)->row();

		$userdata = $this->CommonModel->getTableData("userdetails", $condition)->row();

		if ($userdata->kyc_status != "Requested") {
			$update["kyc_status"] = "Unverified";
			$update["user_status"] = 3;
			$update["forcelogout"] = 1;

			$userdata = $this->CommonModel->updateTableData("userdetails", $update, $condition);

			//$userdata1 = $this->CommonModel->updateTableData("userdetails", $update, $condition);

			$vupdate["proof1_status"] = "0";
			$vupdate["proof2_status"] = "0";
			$vupdate["proof3_status"] = "0";
			$vupdate["proof4_status"] = "0";
			$vupdate["proof5_status"] = "0";
			$vupdate["verification_status"] = "Requested";
			$userdata = $this->CommonModel->updateTableData("user_verification", $vupdate, $condition);

			$site_data = site_settings();

			$name = $userdata->username;

			$email_data = getEmailTeamplete(17);
			$subject = $email_data->subject;
			$template = $email_data->template;

			$data = array(
				"###NAME###" => $name,

			);
			$data["###LOGOIMG###"] = getSiteLogo();
			$data["###EMAILIMG###"] = base_url() . "assets/frontend/images/email_send.png";
			$data["###FBIMG###"] = base_url() . "assets/frontend/images/facebook.png";
			$data["###TWIMG###"] = base_url() . "assets/frontend/images/twitter.png";
			$data["###GPIMG###"] = base_url() . "assets/frontend/images/gplus.png";
			$data["###LEIMG###"] = base_url() . "assets/frontend/images/linkedin.png";
			$data["###HDIMG###"] = base_url() . "assets/frontend/images/email.png";
			$data["###FBLINK###"] = $site_data->facebooklink;
			$data["###TWLINK###"] = $site_data->twitterlink;
			$data["###GPLINK###"] = $site_data->googlelink;
			$data["###LELINK###"] = $site_data->linkedinlink;
			$email = get_user_email($user_id);

			$message = strtr($template, $data);

			send_mail($email, $subject, $message);

			$this->session->set_flashdata("success", "New Kyc document requested");

		} else {

			$this->session->set_flashdata("error", "New Kyc document already requested");

		}
		redirect('BoUser/view');

	}
	public function posts_history_login()
    {
        $userid = insep_decode($this->input->post('user_id'));


       $columns = array(
            0 => 'act_id',
            1 => 'date',
            2 => 'ip_address',
            3 => 'os_name',
            4 => 'browser_name',
        );

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order = $columns[$this->input->post('order')[0]['column']];
        $dir = $this->input->post('order')[0]['dir'];

        $totalData = $this->BoLoginModel->allposts_count_history($userid);

        $totalFiltered = $totalData;

        if (empty($this->input->post('search')['value'])) {
            $posts = $this->BoLoginModel->allposts_history($limit, $start, $order, $dir,$userid);
        } else {
            $search = $this->input->post('search')['value'];

            $posts = $this->BoLoginModel->posts_search_history($limit, $start, $search, $order, $dir,$userid);

            $totalFiltered = $this->BoLoginModel->posts_search_count_history($search,$userid);
        }

        $data = array();
        if (!empty($posts)) {
            $i = 1;
            foreach ($posts as $post) {


                $data[] = $post;
                $i++;
            }
        }

        $json_data = array(
            "draw" => intval($this->input->post('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);
    }

    function view_history_login($id="")
    {
        $data['user_id']=$id;



        $this->load->view("admin/user/editusers",$data);

    }
 
   
    function withdrawExcel($id = "",$date_start,$date_end)
    {
    	$userid = insep_decode($id);

        // create file name
        $fileName = 'data-' . time() . '.xlsx';
        // load excel library
        // $this->load->library('excel');
        $date_start=$date_start.' 00:00:00';
 		$date_end=$date_end." 23:59:59";

        $data = $this->BoLoginModel->withdraw_exel($userid,$date_start,$date_end);
        
        //Khởi tạo đối tượng
        $excel = new PHPExcel();
		//Chọn trang cần ghi (là số từ 0->n)
        $excel->setActiveSheetIndex(0);
		//Tạo tiêu đề cho trang. (có thể không cần)
        $excel->getActiveSheet()->setTitle('demo ghi dữ liệu');

		//Xét chiều rộng cho từng, nếu muốn set height thì dùng setRowHeight()
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
        $excel->getActiveSheet()->getColumnDimension('H')->setWidth(30);


        $excel->getActiveSheet()->setCellValue('A1', 'Date & Time');
        $excel->getActiveSheet()->setCellValue('B1', 'Currency');
        $excel->getActiveSheet()->setCellValue('C1', 'Blockchain Tax id');
        $excel->getActiveSheet()->setCellValue('D1', 'Transaction ID');
        $excel->getActiveSheet()->setCellValue('E1', 'Withdraw Amount');
        $excel->getActiveSheet()->setCellValue('F1', 'Fees');
        $excel->getActiveSheet()->setCellValue('G1', 'Receive Amount');
        $excel->getActiveSheet()->setCellValue('H1', 'Ation');
		// thực hiện thêm dữ liệu vào từng ô bằng vòng lặp
		// dòng bắt đầu = 2
        $numRow = 2;
        foreach ($data as $row) {
            $excel->getActiveSheet()->setCellValue('A' . $numRow, $row['requested_time']);
            $excel->getActiveSheet()->setCellValue('B' . $numRow, $row['currency']);
            $excel->getActiveSheet()->setCellValue('C' . $numRow, $row['transation_hash']);
            $excel->getActiveSheet()->setCellValue('D' . $numRow, $row['transactionId']);

            $amount = number_format($row['total_amount'], 9);
			$amount = substr($amount, 0, strlen($amount) -1); 
            // $excel->getActiveSheet()->setCellValue('D' . $numRow, $amount);
            $excel->getActiveSheet()->setCellValueExplicit(
				    'E'.$numRow,
				    $amount );

            $amount1 = number_format($row['fee'], 9);
            $amount1 = substr($amount1, 0, strlen($amount1) -1);
            //$excel->getActiveSheet()->setCellValue('D' . $numRow, $amount1);
            $excel->getActiveSheet()->setCellValueExplicit(
				    'F'.$numRow,
				    $amount1 );

            $amount2 = number_format($row['transfer_amount'], 9);
            $amount2 = substr($amount2, 0, strlen($amount2) -1);
            // $excel->getActiveSheet()->setCellValue('D' . $numRow, $amount2);
            $excel->getActiveSheet()->setCellValueExplicit(
				    'G'.$numRow,
				    $amount2 );

            $excel->getActiveSheet()->setCellValue('H' . $numRow, $row['status']);
            $numRow++;
        }
		// Khởi tạo đối tượng PHPExcel_IOFactory để thực hiện ghi file
	// ở đây mình lưu file dưới dạng excel2007
        PHPExcel_IOFactory::createWriter($excel, 'Excel2007')->save('uploads/data.xlsx');
        return redirect('uploads/data.xlsx');
    }
    	public function tradeHistory($id = ""){
		$admin_id = $this->session->userdata('loggedJTEAdminUserID');
		$condition = array("admin_id" => $admin_id);
		$admindetals = $this->CommonModel->getTableData("sub_admin_permissions", $condition)->row();

		if ($admindetals->user_details == 0) {

			$this->session->set_flashdata("error", "Access denied");
			redirect("BoDashboard");

		}
		$userid = insep_decode($id);
		$data = array();
		$data['tab_type'] = 3;

		$select = "";

		$user_id = $userid;
		$this->load->model('common_model');

		$condition = array();
		$condition["a.userId"] = $userid;
		
		$post = $this->input->post();
		$get = $this->input->get();
		$input = array_merge($post, $get);
		
		$sort_array = [
			1 => 'a.order_no',
			2 => 'each_tradetime',
			3 => 'a.Type',
			4 => 'a.pair',
			5 => 'f.sellorderId',
			6 => 'filledAmount',
			7 => 'a.status',
		];
		if (isset($input['sort']) && !empty($input['sort'])) {
			if(isset($sort_array[abs($input['sort'])])){
				if($input['sort'] > 0){
					$order_by = array($sort_array[abs($input['sort'])], "asc");
				}else{
					$order_by = array($sort_array[abs($input['sort'])], "desc");
				}
			}
		}
		
		if(!isset($order_by)){
			$input['sort'] = "-1";
			$order_by = array($sort_array[1], "desc");
		}
		
		$data['from_date'] = '';
		if(isset($input['from_date']) && !empty($input['from_date'])){
			$data['from_date'] = $input['from_date'];
			$fromdate_array = explode("/", $input['from_date']);
			$fromdate = $fromdate_array[2] . "-" . $fromdate_array[0] . "-" . $fromdate_array[1] . " " . "00:00:00";
			$todate = $this->input->post("dep_to_date");
			
			$condition["a.datetime >="] = $fromdate;
		}
		
		$data['to_date'] = '';
		if(isset($input['to_date']) && !empty($input['to_date'])){
			$data['to_date'] = $input['to_date'];
			$todate_array = explode("/", $input['to_date']);
			$todate = $todate_array[2] . "-" . $todate_array[0] . "-" . $todate_array[1] . " " . "23:59:59";
			
			$condition["a.datetime <="] = $todate;
		}
		$data['BtnAll']='';
		
		if(isset($_REQUEST['BtnAll'])) {

			$data['BtnAll']=$_REQUEST['BtnAll'];
			if($_REQUEST['BtnAll']==1) {$condition['a.Type'] = 'buy';}
			else if($_REQUEST['BtnAll']==2) $condition['a.Type'] = 'sell';
		}


		$names = array('filled', 'partially');
		$where_in = array('a.status', $names);
		$hisjoins1 = array('trade_pairs as b' => 'a.pair = b.id', 'userdetails as c' => 'a.userId = c.user_id', 'currency as d' => 'b.from_symbol_id = d.id', 'currency as e' => 'b.to_symbol_id = e.id', 'ordertemp as f' => 'a.trade_id = f.sellorderId OR a.trade_id = f.buyorderId');

		$perpage = frontPageMax();
		$page_count = $this->uri->segment(4);
		$page_url = $this->uri->segment(3);
		if(is_null($page_count)){
			$page_count = 0;
		}
		$data['page_count'] = $page_count;
		$base = base_url()."BoUser/tradeHistory/".$page_url;
		$data['url'] = $base;

		

		$activity_count = $this->common_model->getJoinedTableData('coin_order as a', $hisjoins1, $condition, 'count(*) as cnt', '', '', '', '', '', '', '', $where_in)->row()->cnt;
		pageconfig($activity_count, $base, $perpage);

		$data["trade_history"] = $this->common_model->getJoinedTableData('coin_order as a', $hisjoins1, $condition, 'a.*,f.buyorderId,f.sellorderId,b.from_symbol_id as from_currency_id,a.Type, b.to_symbol_id as to_currency_id, c.username as username, d.currency_symbol as from_currency_symbol, e.currency_symbol as to_currency_symbol,filledAmount,f.bidPrice,f.askPrice,f.datetime as each_tradetime', '', '', '', $page_count, $perpage, $order_by, '', $where_in);
		$condition["a.status"] = 'filled';
		$data2 = $this->common_model->getJoinedTableData('coin_order as a', $hisjoins1, $condition, 'a.*,f.buyorderId,f.sellorderId,b.from_symbol_id as from_currency_id,a.Type, b.to_symbol_id as to_currency_id, c.username as username, d.currency_symbol as from_currency_symbol, e.currency_symbol as to_currency_symbol,filledAmount,f.bidPrice,f.askPrice,f.datetime as each_tradetime', '', '', '', '');



		if(array_key_exists('export_excel',$_POST)){
			 $fileName = 'data-' . time() . '.xlsx';

			       // load excel library
        // $this->load->library('excel');
        // $data = $this->BoLoginModel->tradehistory_exel($userid);
        //Khởi tạo đối tượng
        $excel = new PHPExcel();
		//Chọn trang cần ghi (là số từ 0->n)
        $excel->setActiveSheetIndex(0);
		//Tạo tiêu đề cho trang. (có thể không cần)
        $excel->getActiveSheet()->setTitle('demo ghi dữ liệu');

		//Xét chiều rộng cho từng, nếu muốn set height thì dùng setRowHeight()
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(30);


        $excel->getActiveSheet()->setCellValue('A1', 'Order No');
        $excel->getActiveSheet()->setCellValue('B1', 'Date & Time');
        $excel->getActiveSheet()->setCellValue('C1', 'Type');
        $excel->getActiveSheet()->setCellValue('D1', 'Pair');
        $excel->getActiveSheet()->setCellValue('E1', 'Price');
        $excel->getActiveSheet()->setCellValue('F1', 'Amount');
        $excel->getActiveSheet()->setCellValue('G1', 'Status');
		// thực hiện thêm dữ liệu vào từng ô bằng vòng lặp
		// dòng bắt đầu = 2
		
        $numRow = 2;
        foreach ($data2->result_array() as $key=> $row) {
        	

           
        	if ($row['sellorderId'] < $row['buyorderId']) 
			{
                  $price = $row['askPrice'];
            } else {
                  $price = $row['bidPrice'];
            }
         
            $amount = number_format($price, 9);
            $amount1 = substr($amount, 0, strlen($amount) -1);
            $excel->getActiveSheet()->setCellValueExplicit(
				    'A'.$numRow,
				    $row['order_no']
				    
				);
           
            $excel->getActiveSheet()->setCellValue('B' . $numRow, $row['each_tradetime']);
            $excel->getActiveSheet()->setCellValue('C' . $numRow, $row['Type']);
            $excel->getActiveSheet()->setCellValue('D' . $numRow, $row['from_currency_symbol'] . '/' . $row['to_currency_symbol']);

            $excel->getActiveSheet()->setCellValueExplicit(
				    'E'.$numRow,
				    $amount1
				    
				);

            		$amount1 = number_format($row['filledAmount'], 9);
                    $amount1 = substr($amount1, 0, strlen($amount1) -1);
            $excel->getActiveSheet()->setCellValueExplicit(
				    'F'.$numRow,
				    $amount1
				    
				);
            $excel->getActiveSheet()->setCellValue('G' . $numRow, $row['status']);
            $numRow++;
         
        }
             
		// Khởi tạo đối tượng PHPExcel_IOFactory để thực hiện ghi file
	// ở đây mình lưu file dưới dạng excel2007
      PHPExcel_IOFactory::createWriter($excel, 'Excel2007')->save('uploads/data.xlsx');
        return redirect('uploads/data.xlsx');
    	}

    	if(array_key_exists('btn_pdf',$_POST)){


		$data1['data']= $this->common_model->getJoinedTableData('coin_order as a', $hisjoins1, $condition, 'a.*,f.buyorderId,f.sellorderId,b.from_symbol_id as from_currency_id, b.to_symbol_id as to_currency_id, c.username as username, d.currency_symbol as from_currency_symbol, e.currency_symbol as to_currency_symbol,filledAmount,f.bidPrice,f.askPrice,f.datetime as each_tradetime', '', '', '', '')->result_array();

		foreach ($data1['data'] as $key => $value) {

			
			$data1['data'][$key]['currency'] = $value['from_currency_symbol'].'/'.$value['to_currency_symbol'];

			if ($value['sellorderId'] < $value['buyorderId']) {
                              $price = $value['askPrice'];
                        } else {
                              $price = $value['bidPrice'];
                        }
			$amount = number_format($value['Price'], 9);
            $data1['data'][$key]['total_amount']=substr($amount, 0, strlen($amount) -1);

            $amount1 = number_format($value['filledAmount'], 9);
            $data1['data'][$key]['amount']=substr($amount1, 0, strlen($amount1) -1);

		}
	
		$data1['list_head']=['Order No','Date & Time','Type','Pair','Price','Amount','Status'];
		$data1['list_key']=['order_no','each_tradetime','Type','currency','total_amount','amount','status'];
		$data1['title']='TRADE HISTORY';
		
		
		$this->createPDF($data1);
		exit(); 
		} 




		$data['now_sort'] = 0;
		if(isset($input['sort'])){
			$data['now_sort'] = $input['sort'];
			unset($input['sort']);
		}
		$now_url = $base.'/'.$page_count.'?';
		$now_url .= http_build_query($input);
		$data['now_url'] = $now_url;
		
		$data['pagination'] = $this->pagination->create_links();

		$this->load->view("admin/user/editusers", $data);

	}
	 public function createPDF(array $data = []) {
	 	
	 	ob_start();
        
        $html = $this->load->view('pdf/index', $data, TRUE);

        // Include the main TCPDF library (search for installation path).
        $this->load->library('Pdf');
        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('TechArise');
        $pdf->SetTitle('TechArise');
        $pdf->SetSubject('TechArise');
        $pdf->SetKeywords('TechArise');

        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, 0, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(0);
        $pdf->SetFooterMargin(0);

        // set auto page breaks
        //$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->SetAutoPageBreak(TRUE, 10.5);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }

        // set font
        $pdf->SetFont('dejavusans', '', 10);

        // add a page
        $pdf->AddPage();

        // output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');

        // reset pointer to the last page
        $pdf->lastPage();
        ob_clean();
        //Close and output PDF document
        $pdf->Output('data.pdf', 'D');
        }
 }

