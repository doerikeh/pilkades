<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tabulasi extends AUTH_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('M_tabulasi', 'tabulasi');
        $this->load->model('M_penyelenggara','desapemilihan');
    }

    public function index(){
        $this->load->helper('url');
		$this->load->helper('form');


		$tabulasisatu = $this->tabulasi->select_detail();
		$data['tabulasi'] 		= $tabulasisatu;

		$data['userdata'] 		= $this->userdata;
		$data['page'] 			= "tabulasi";
		$data['judul'] 			= "Tabulasi";
		$data['deskripsi'] 		= " Tabulasi Relawan Pemilihan Kepala Desa";
		
		$this->template->views('tabulasi/home', $data);
    }

    public function detailkec($kec){
        $tabulasikec = $this->tabulasi->select_detail_kec($kec);
		$data['tabulasikec'] 		= $tabulasikec;

		$nama_kec = $this->tabulasi->getkec_by_kode($kec);
		$data['nama_kec']		= $nama_kec->nama_kec;

		$data['userdata'] 		= $this->userdata;
		$data['page'] 			= "Tabulasi";
		$data['judul'] 			= "Tabulasi";
		$data['deskripsi'] 		= "Tabulasi Hasil Pemilihan Kepala Desa";

		$this->template->views('tabulasi/detailkec', $data);
    }

    public function detaildesa($desa){
        $tabulasidesa = $this->tabulasi->select_detail_desa($desa);
		$data['tabulasidesa'] 		= $tabulasidesa;

		$nama_desa= $this->tabulasi->getdesa_by_kode($desa);
		$data['nama_desa']		= $nama_desa->nama_desa;

		$data['userdata'] 		= $this->userdata;
		$data['page'] 			= "Tabulasi";
		$data['judul'] 			= "Tabulasi";
		$data['deskripsi'] 		= "Tabulasi Hasil Pemilihan Kepala Desa";

		$this->template->views('tabulasi/detaildesa', $data);
    }
}