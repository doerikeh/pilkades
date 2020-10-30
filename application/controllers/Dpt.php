<?php
defined('BASEPATH') OR exit('No direct script access allowed');



class Dpt extends AUTH_Controller {
    public function __construct() {
		parent::__construct();
		$this->load->model('M_dpt','dpt');
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
		
		$data['page'] 			= "dpt";
		$data['judul'] 			= "Daftar dpt";
		$data['deskripsi'] 		= "Data dpt Tahun ".$this->session->userdata('thn_data');


		$this->template->views('dpt/home', $data);
    }
    
    public function ajax_list()
	{
		$this->load->helper('url');

		$list = $this->dpt->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $dpt) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $dpt->nama_kec;
			$row[] = $dpt->nama_desa;
			$row[] = $dpt->nik;
			$row[] = $dpt->nama;
			$row[] = $dpt->tpt_lahir.',<br /> '.$dpt->tgl_lahir;
			$row[] = $dpt->jenis_kelamin;
			

			if ($this->session->userdata('id_role') == '3') {
				if (getStatusTransaksi('Pengelolaan Data dpt')) {
					$row[] = '<a class="btn btn-xs btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_person('."'".$dpt->id."'".')"><i class="glyphicon glyphicon-pencil"></i></a>
					  <a class="btn btn-xs btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_person('."'".$dpt->id."'".')"><i class="glyphicon glyphicon-trash"></i></a>
					  <a class="btn btn-xs btn-warning" href="javascript:void(0)" title="Lihat" onclick="view_person('."'".$dpt->id."'".')"><i class="glyphicon glyphicon-search"></i></a>
					  <a class="btn btn-xs btn-warning" href="javascript:void(0)" title="join" onclick="join_relawan('."'".$dpt->id."'".')"><i class="fas fa-user-friends"></i></a>';
					  
				} else {
					$row[] = '
					  <a class="btn btn-xs btn-warning" href="javascript:void(0)" title="Lihat" onclick="view_person('."'".$dpt->id."'".')"><i class="glyphicon glyphicon-search"></i></a>';	  
				
				}
			} else {
				$row[] = '<a class="btn btn-xs btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_person('."'".$dpt->id."'".')"><i class="glyphicon glyphicon-pencil"></i></a>
					  <a class="btn btn-xs btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_person('."'".$dpt->id."'".')"><i class="glyphicon glyphicon-trash"></i></a>
					  <a class="btn btn-xs btn-warning" href="javascript:void(0)" title="Lihat" onclick="view_person('."'".$dpt->id."'".')"><i class="glyphicon glyphicon-search"></i></a>
					  <a class="btn btn-xs btn-warning" href="javascript:void(0)" title="join" onclick="join_relawan('."'".$dpt->id."'".')"><i class="fas fa-user-friends"></i></a>';
					  
			}
		    
	  		
			$data[] = $row;

        }
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->dpt->count_all(),
                        "recordsFiltered" => $this->dpt->count_filtered(),
                        "data" => $data,
                    );
        //output to json format
        echo json_encode($output);
	}
	
	public function ajax_join($id){
		$data = $this->dpt->get_join_id($id);
		$data->tgl_lahir = ($data->tgl_lahir == '0000-00-00') ? '' : $data->tgl_lahir; 
		echo json_encode($data);
	}

	public function ajax_joinrelawan()
	{
		$this->_validate();
		$data = array(
                'nama' => $this->input->post('nama'),
                'nkk' => $this->input->post('nkk'),
                'nik' => $this->input->post('nik'),
                'tpt_lahir' => $this->input->post('tpt_lahir'),
                'tgl_lahir' => $this->input->post('tgl_lahir'),
				'jenis_kelamin' => $this->input->post('jenis_kelamin'),
				'kawin' => $this->input->post('id_relawan.kawin'),
                'alamat' => $this->input->post('alamat'),
                'rt' => $this->input->post('rt'),
                'rw' => $this->input->post('rw'),
				'tps' => $this->input->post('id_relawan.tps'),
				'ektp' => $this->input->post('id_relawan.ektp'),
				'sumberdata' => $this->input->post('id_relawan.sumberdata'),
				'keterangan' => $this->input->post('id_relawan.keterangan'),
                'kdkec' => $this->input->post('kdkec'),
                'kddesa' => $this->input->post('kddesa'),
			);

		$this->dpt->join_by(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
    }

    public function ajax_edit($id)
	{
		$data = $this->dpt->get_by_id($id);
		$data->id = ($data->id == '') ? '' : $data->id; 
		echo json_encode($data);
    }
    
    // public function ajax_add()
	// {
	// 	$this->_validate();
		
	// 	$data = array(
    //             'nama' => $this->input->post('nama'),
    //             'nkk' => $this->input->post('nkk'),
	// 			'nik' => $this->input->post('nik'),
	// 			'tpt_lahir' => $this->input->post('tpt_lahir'),
	// 			'tgl_lahir' => $this->input->post('tgl_lahir'),
	// 			'jenis_kelamin' => $this->input->post('jenis_kelamin'),
	// 			'kawin' => $this->input->post('kawin'),
	// 			'alamat' => $this->input->post('alamat'),
	// 			'rt' => $this->input->post('rt'),
    //             'rw' => $this->input->post('rw'),
    //             'difabel' => $this->input->post('difabel'),
	// 			'ektp' => $this->input->post('ektp'),
	// 			'tps' => $this->input->post('tps'),
    //             'keterangan' => $this->input->post('keterangan'),
	// 			'sumberdata' => $this->input->post('sumberdata'),
	// 			'kdkec' => $this->input->post('kdkec'),
	// 			'kddesa' => $this->input->post('kddesa'),
	// 		);


	// 	$insert = $this->dpt->save($data);

	// 	echo json_encode(array("status" => TRUE));
    // }
    
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
                'alamat' => $this->input->post('alamat'),
                'rt' => $this->input->post('rt'),
                'rw' => $this->input->post('rw'),
                'tps' => $this->input->post('tps'),
                'kdkec' => $this->input->post('kdkec'),
                'kddesa' => $this->input->post('kddesa'),
			);

		$this->dpt->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
    }
    
    public function ajax_delete($id)
	{
		//delete file
		$dpt = $this->dpt->get_by_id($id);
		
		$this->dpt->delete_by_id($id);
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

    public function import() {
		$this->form_validation->set_rules('excel', 'File', 'trim|required');

		if ($_FILES['excel']['name'] == '') {
			$this->session->set_flashdata('msg', 'File harus diisi');
		} else {
			$config['upload_path'] = './assets/excel/';
			$config['allowed_types'] = 'xls|xlsx';
			
			$this->load->library('upload', $config);
			
			if ( ! $this->upload->do_upload('excel')){
				$error = array('error' => $this->upload->display_errors());
			}
			else{
				$data = $this->upload->data();
				
				error_reporting(E_ALL);
				date_default_timezone_set('Asia/Jakarta');

				include './assets/phpexcel/Classes/PHPExcel/IOFactory.php';

				$inputFileName = './assets/excel/' .$data['file_name'];
				$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
				$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

				$index = 0;
				foreach ($sheetData as $key => $value) {
					if ($key != 1) {
						
						$check = $this->dpt->check_nama($value['C']);
						if ($check != 1) {
							$resultData[$index]['id'] = $value['A'];
							$resultData[$index]['nkk'] = $value['B'];
							$resultData[$index]['nik'] = $value['C'];
							$resultData[$index]['nama'] = $value['D'];
							$resultData[$index]['tpt_lahir'] = $value['E'];
							$resultData[$index]['tgl_lahir'] = date('Y-m-d');
							$resultData[$index]['jenis_kelamin'] = $value['G'];
							$resultData[$index]['alamat'] = $value['H'];
							$resultData[$index]['rt'] = $value['I'];
							$resultData[$index]['rw'] = $value['J'];
							$resultData[$index]['tps'] = $value['K'];
							$resultData[$index]['kdkec'] = $value['L'];
							$resultData[$index]['kddesa'] = $value['M'];
						}
					}
					$index++;
				}

				unlink('./assets/excel/' .$data['file_name']);

				if (count($resultData) != 0) {
					$result = $this->dpt->insert_batch($resultData);
					if ($result > 0) {
						$this->session->set_flashdata('msg', show_succ_msg('Data DPT Berhasil diimport ke database'));
						redirect('');
					}
				} else {
					$this->session->set_flashdata('msg', show_msg('Data DPt Gagal diimport ke database (Data Sudah terupdate)', 'warning', 'fa-warning'));
					redirect('');
				}

			}
		}
	}

}