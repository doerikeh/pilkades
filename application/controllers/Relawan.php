<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Relawan extends AUTH_Controller {
    public function __construct() {
		parent::__construct();
		$this->load->model('M_relawan','relawan');
		$this->load->model('M_desa','desa');
		$this->load->model('M_penyelenggara','desapemilihan');

		$this->load->helper('url', 'form');
    }
    
    public function index() {

		$this->load->helper('url', 'form');

		$kecamatans = $this->desapemilihan->get_list_kec();

		$opt = array('' => '');
		foreach ($kecamatans as $kec) {
			$opt[$kec] = $kec;
		}

		//$data['form_kec'] 		= form_dropdown('',$opt,'','id="nama_kec" class="form-control"');

		$data['kecamatan'] 		= $this->desapemilihan->getKec();
		$data['dataDesanya']   	= $this->desa->select_by_kec();
		$data['userdata'] 		= $this->userdata;
		
		$data['page'] 			= "relawan";
		$data['judul'] 			= "Daftar Relawan";
		$data['deskripsi'] 		= "Data Relawan Tahun ".$this->session->userdata('thn_data');


		$this->template->views('relawan/home', $data);
    }
    
    public function ajax_list()
	{
		$this->load->helper('url');

		$list = $this->relawan->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $relawan) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $relawan->nama_kec;
			$row[] = $relawan->nama_desa;
			$row[] = $relawan->nik;
			$row[] = $relawan->nama;
			$row[] = $relawan->tpt_lahir.',<br /> '.$relawan->tgl_lahir;
			$row[] = $relawan->kawin;
			$row[] = $relawan->jenis_kelamin;
			
			

			if ($this->session->userdata('id_role') == '3') {
				if (getStatusTransaksi('Pengelolaan Data Relawan')) {
					$row[] = '<a class="btn btn-xs btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_person('."'".$relawan->id_relawan."'".')"><i class="glyphicon glyphicon-pencil"></i></a>
					  <a class="btn btn-xs btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_person('."'".$relawan->id_relawan."'".')"><i class="glyphicon glyphicon-trash"></i></a>
					  <a class="btn btn-xs btn-warning" href="javascript:void(0)" title="Lihat" onclick="view_person('."'".$relawan->id_relawan."'".')"><i class="glyphicon glyphicon-search"></i></a>';
				} else {
					$row[] = '
					  <a class="btn btn-xs btn-warning" href="javascript:void(0)" title="Lihat" onclick="view_person('."'".$relawan->id_relawan."'".')"><i class="glyphicon glyphicon-search"></i></a>';	  
				
				}
			} else {
				$row[] = '<a class="btn btn-xs btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_person('."'".$relawan->id_relawan."'".')"><i class="glyphicon glyphicon-pencil"></i></a>
					  <a class="btn btn-xs btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_person('."'".$relawan->id_relawan."'".')"><i class="glyphicon glyphicon-trash"></i></a>
					  <a class="btn btn-xs btn-warning" href="javascript:void(0)" title="Lihat" onclick="view_person('."'".$relawan->id_relawan."'".')"><i class="glyphicon glyphicon-search"></i></a>';
			}
		    
	  		
			$data[] = $row;

        }
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->relawan->count_all(),
                        "recordsFiltered" => $this->relawan->count_filtered(),
                        "data" => $data,
                    );
        //output to json format
        echo json_encode($output);
    }

    public function ajax_edit($id)
	{
		$data = $this->relawan->get_by_id($id);
		$data->tgl_lahir = ($data->tgl_lahir == '0000-00-00') ? '' : $data->tgl_lahir; 
		echo json_encode($data);
    }
    
    public function ajax_add()
	{
		$this->_validate();
		
		$data = array(
                'nama' => $this->input->post('nama'),
                'nkk' => $this->input->post('nkk'),
				'nik' => $this->input->post('nik'),
				'tpt_lahir' => $this->input->post('tpt_lahir'),
				'tgl_lahir' => $this->input->post('tgl_lahir'),
				'jenis_kelamin' => $this->input->post('jenis_kelamin'),
				'kawin' => $this->input->post('kawin'),
				'alamat' => $this->input->post('alamat'),
				'rt' => $this->input->post('rt'),
                'rw' => $this->input->post('rw'),
                'difabel' => $this->input->post('difabel'),
				'ektp' => $this->input->post('ektp'),
				'tps' => $this->input->post('tps'),
                'keterangan' => $this->input->post('keterangan'),
				'sumberdata' => $this->input->post('sumberdata'),
				'kdkec' => $this->input->post('kdkec'),
				'kddesa' => $this->input->post('kddesa'),
				'thn_pemilihan' => $this->input->post('thn_pemilihan'),

			);


		$insert = $this->relawan->save($data);

		echo json_encode(array("status" => TRUE));
    }
    
    public function ajax_update()
	{
		$this->_validate();
		$data = array(
                'nama' => $this->input->post('nama'),
                'nkk' => $this->input->post('nkk'),
                'nik' => $this->input->post('nik'),
                'tpt_lahir' => $this->input->post('tpt_lahir'),
                'tgl_lahir' => $this->input->post('tgl_lahir'),
                'jenis_kelamin' => $this->input->post('jenis_kelamin'),
                'kawin' => $this->input->post('kawin'),
                'alamat' => $this->input->post('alamat'),
                'rt' => $this->input->post('rt'),
                'rw' => $this->input->post('rw'),
                'difabel' => $this->input->post('difabel'),
                'ektp' => $this->input->post('ektp'),
                'keterangan' => $this->input->post('keterangan'),
                'sumberdata' => $this->input->post('sumberdata'),
                'kdkec' => $this->input->post('kdkec'),
				'kddesa' => $this->input->post('kddesa'),
				'thn_pemilihan' => $this->input->post('thn_pemilihan'),

			);

		$this->relawan->update(array('id_relawan' => $this->input->post('id_relawan')), $data);
		echo json_encode(array("status" => TRUE));
    }
    
    public function ajax_delete($id)
	{
		//delete file
		$relawan = $this->relawan->get_by_id($id);
		
		$this->relawan->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
    }
    
    private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('kddesa') == '')
		{
			$data['inputerror'][] = 'kddesa';
			$data['error_string'][] = 'Nama desa tidak boleh kosong';
			$data['status'] = FALSE;
		}

		if($this->input->post('nama') == '')
		{
			$data['inputerror'][] = 'nama';
			$data['error_string'][] = 'Nama tidak boleh kosong';
			$data['status'] = FALSE;
		}

		if($this->input->post('tpt_lahir') == '')
		{
			$data['inputerror'][] = 'tpt_lahir';
			$data['error_string'][] = 'Tempat Lahir tidak boleh kosong';
			$data['status'] = FALSE;
		}

		if($this->input->post('tgl_lahir') == '')
		{
			$data['inputerror'][] = 'tgl_lahir';
			$data['error_string'][] = 'Tanggal Lahir tidak boleh kosong';
			$data['status'] = FALSE;
		}

		if($this->input->post('jenis_kelamin') == '')
		{
			$data['inputerror'][] = 'jenis_kelamin';
			$data['error_string'][] = 'Pilih kelamin';
			$data['status'] = FALSE;
		}


		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
    }
    
    public function export() {
		error_reporting(E_ALL);
    	
		include_once './assets/phpexcel/Classes/PHPExcel.php';
		$objPHPExcel = new PHPExcel();

		$data = $this->relawan->select_by_kec();

		$objPHPExcel = new PHPExcel(); 
		$objPHPExcel->setActiveSheetIndex(0); 
		$rowCount = 1; 

		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, "NO");
		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, "KECAMATAN");
        $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, "DESA");
        $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, "NKK");
        $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, "NIK");
		$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, "NAMA");
        $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, "TEMPAT/TGL LAHIR");
        $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, "KAWIN");
		$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, "L/P");
        $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, "RT");
        $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, "RW");
        $objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, "DIFABEL");
		$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, "EKTP");
		$objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount, "TPS");

		$rowCount++;

		foreach($data as $value){
		    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $rowCount-1); 
		    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $value->nama_kec); 
		    $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $value->nama_desa); 
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $value->nkk); 
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $value->nik); 
		    $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $value->nama); 
		    $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $value->tpt_lahir); 
		    $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $value->jenis_kelamin); 
		    $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $value->kawin); 
		    $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $value->rt); 
			$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $value->rw);
			$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, $value->difabel);
			$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $value->ektp);
			$objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount, $value->tps);
		    $rowCount++; 
		} 

		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
		$objWriter->save('./assets/excel/DataRelawan'.$this->session->userdata('id_kec').'.xlsx'); 

		$this->load->helper('download');
		force_download('./assets/excel/DataRelawan'.$this->session->userdata('id_kec').'.xlsx', NULL);
    }
    
    public function exportPHPOffice() {
		error_reporting(E_ALL);

		$data = $this->relawan->select_by_kec();
    	
		$spreadsheet  = new Spreadsheet();
		$spreadsheet->setActiveSheetIndex(0); 
		$rowCount = 1; 

		$$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, "NO");
		$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, "KECAMATAN");
        $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, "DESA");
        $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, "NKK");
        $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, "NIK");
		$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, "NAMA");
        $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, "TEMPAT/TGL LAHIR");
        $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, "KAWIN");
		$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, "L/P");
        $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, "RT");
        $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, "RW");
        $objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, "DIFABEL");
		$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, "EKTP");
		$objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount, "TPS");

		$rowCount++;

		foreach($data as $value){
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $rowCount-1); 
		    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $value->nama_kec); 
		    $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $value->nama_desa); 
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $value->nkk); 
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $value->nik); 
		    $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $value->nama); 
		    $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $value->tpt_lahir); 
		    $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $value->jenis_kelamin); 
		    $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $value->kawin); 
		    $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $value->rt); 
			$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $value->rw);
			$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, $value->difabel);
			$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $value->ektp);
			$objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount, $value->tps);
		    $rowCount++; 
		} 

		$writer = new Xlsx($spreadsheet);
		if ($this->session->userdata('id_role') == '3') {
			$filename = 'data_relawan_'.$this->session->userdata('thn_data').'_'.$this->session->userdata('id_kec').'.xlsx';
		} else {
			$filename = 'data_relawan_'.$this->session->userdata('thn_data').'_all.xlsx';
		}
		
		//header('Content-Type: application/vnd.ms-excel');
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'. $filename ); 
		header('Cache-Control: max-age=0');
		$writer->save('php://output');

	}

}