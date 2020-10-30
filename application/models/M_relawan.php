<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_relawan extends CI_Model{

    var $table = 'tbl_relawan';
    var $column_order = array('tbl_wkecamatan.nama_kec','tbl_wdesa.nama_desa','tbl_relawan.nama','tbl_relawan.tgl_lahir','tbl_relawan.kawin','tbl_relawan.jenis_kelamin','tbl_relawan.rt','tbl_relawan.rw','tbl_relawan.tps',null);
    var $column_search = array('tbl_wkecamatan.nama_kec','tbl_wdesa.nama_desa','tbl_relawan.nama','tbl_relawan.alamat', 'tbl_relawan.jenis_kelamin', 'tbl_relawan.kawin','tbl_relawan.nik', );
    var $order = array('tbl_relawan.id_relawan' => 'desc'); // default order 

    public function __construct(){
      parent::__construct();
      $this->load->database();
    }
    
    private function _get_datatables_query(){

      $this->db->from($this->table);
      $this->db->join('tbl_wkecamatan','tbl_wkecamatan.id_kec=tbl_relawan.kdkec', 'left');
      $this->db->join('tbl_wdesa','tbl_wdesa.id_desa=tbl_relawan.kddesa', 'left');
      // $this->db->join('tbl_wdpdt', 'tbl_wdpdt.id=tbl_relawan.kddpt', 'left');
  
      if ($this->session->userdata('id_role') == '3') {
        $this->db->where('tbl_relawan.kdkec',$this->session->userdata('id_kec'));
      }
      $this->db->where('tbl_relawan.thn_pemilihan',$this->session->userdata('thn_data'));
      
      if (($this->session->userdata('id_role') == '1') || ($this->session->userdata('id_role') == '2')){

        if($this->input->post('kdkec'))
        {
          $this->db->like('tbl_relawan.kdkec', $this->input->post('kdkec'));
        }

        if($this->input->post('kddesa'))
        {
          $this->db->like('tbl_relawan.kddesa', $this->input->post('kddesa'));
        }

        if($this->input->post('rt'))
        {
          $this->db->like('tbl_relawan.rt', $this->input->post('rt'));
        }

        if($this->input->post('rw'))
        {
          $this->db->like('tbl_relawan.rw', $this->input->post('rw'));
        }
        if($this->input->post('jenis_kelamin'))
        {
          $this->db->like('tbl_relawan.jenis_kelamin', $this->input->post('jenis_kelamin'));
        }

        if($this->input->post('kawin'))
        {
          $this->db->like('tbl_relawan.kawin', $this->input->post('kawin'));
        }
      }

      $i = 0;
    
      foreach ($this->column_search as $item) // loop column 
      {
        if($_POST['search']['value']) // if datatable send POST for search
        {
          
          if($i===0) // first loop
          {
            $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
            $this->db->like($item, $_POST['search']['value']);
          }
          else
          {
            $this->db->or_like($item, $_POST['search']['value']);
          }

          if(count($this->column_search) - 1 == $i) //last loop
            $this->db->group_end(); //close bracket
        }
        $i++;
      }   
    
    
      if(isset($_POST['order'])) // here order processing
      {
        $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
      } 
      else if(isset($this->order))
      {
        $order = $this->order;
        $this->db->order_by(key($order), $order[key($order)]);
      }
    }

    function get_datatables()
    {
      $this->_get_datatables_query();
      if($_POST['length'] != -1)
      $this->db->limit($_POST['length'], $_POST['start']);
      $query = $this->db->get();
  
      return $query->result();
    }

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from($this->table);
		return $this->db->count_all_results();
  }
  
  public function get_by_id($id)
	{
		$this->db->select('*');
		$this->db->from('tbl_relawan');
		$this->db->join('tbl_wkecamatan','tbl_wkecamatan.id_kec=tbl_relawan.kdkec', 'left');
		$this->db->join('tbl_wdesa','tbl_wdesa.id_desa=tbl_relawan.kddesa', 'left');
		$this->db->where('tbl_relawan.id_relawan',$id);

		$query = $this->db->get();

		return $query->row();
	}
    
  public function save($data){
    $this->db->insert($this->table, $data);
    return $this->db->insert_id();
  }

  public function delete_by_id($id){
    $this->db->where('id_relawan', $id);
    $this->db->delete($this->table);
  }
  
  public function update($where, $data){
    $this->db->update($this->table, $data, $where);
    return $this->db->affected_rows();
  }

  public function select_all() {
		$this->db->select('*');
		$this->db->from('tbl_relawan');

		$this->db->where('tbl_relawan.thn_pemilihan',$this->session->userdata('thn_data'));
		//$this->db->where('tbl_calon.kdkec',$this->session->userdata('id_kec'));

		$data = $this->db->get();

		return $data->result();
	}

	function load_data($ds)
	{
		$this->db->where('kddesa',$ds);
		$this->db->where('tbl_relawan.nik',$this->session->userdata('thn_data'));
		$query = $this->db->get($this->table);
		return $query->result_array();
  }
  
  public function select_by_kec()
	{
		$this->db->select('*');
		$this->db->from('tbl_relawan');
		$this->db->join('tbl_wkecamatan','tbl_wkecamatan.id_kec=tbl_relawan.kdkec', 'left');
		$this->db->join('tbl_wdesa','tbl_wdesa.id_desa=tbl_relawan.kddesa', 'left');
    $this->db->where('tbl_relawan.thn_pemilihan',$this->session->userdata('thn_data'));
    if ($this->session->userdata('id_role') == '3') {
			$this->db->where('tbl_relawan.kdkec',$this->session->userdata('id_kec'));
		}
		//$this->db->order_by('tbl_calon.kdkec,tbl_calon.kddesa,tbl_calon.nourut', 'ASC');
		$this->db->order_by('tbl_wkecamatan.nama_kec,tbl_wdesa.nama_desa', 'ASC');
		$query = $this->db->get();

		return $query->result();
  }
  
	function update_hasil($data, $id)
	{
		$this->db->where('id', $id);
		$this->db->update($this->table, $data);
		return $this->db->affected_rows();
	}

	public function select_by_desa($desa)
	{
		$this->db->select('*');
		$this->db->from('tbl_relawan');
		$this->db->join('tbl_wkecamatan','tbl_wkecamatan.id_kec=tbl_relawan.kdkec', 'left');
		$this->db->join('tbl_wdesa','tbl_wdesa.id_desa=tbl_relawan.kddesa', 'left');
    $this->db->where('tbl_relawan.thn_pemilihan',$this->session->userdata('thn_data'));
    if ($this->session->userdata('id_role') == '3') {
			$this->db->where('tbl_relawan.kddesa',$desa);
		}
		
		$query = $this->db->get();

		return $query->result();
	}
  
  public function select_jml_desa() {
		if ($this->session->userdata('id_role') == '3') {
			$sql = "SELECT COUNT(DISTINCT(kddesa)) AS jmldesa FROM tbl_relawan WHERE thn_pemilihan = '".$this->session->userdata('thn_data')."' AND kdkec = '".$this->session->userdata('id_kec')."'";
		} else {
			$sql = "SELECT COUNT(DISTINCT(kddesa)) AS jmldesa FROM tbl_relawan WHERE thn_pemilihan = '".$this->session->userdata('thn_data')."'";
		}

		$data = $this->db->query($sql);

		return $data->row();
  }
  
  public function select_jml_kec() {
		if ($this->session->userdata('id_role') == '3') {
			$sql = "SELECT COUNT(DISTINCT(kdkec)) AS jmlkec FROM tbl_relawan WHERE thn_pemilihan = '".$this->session->userdata('thn_data')."' AND kdkec = '".$this->session->userdata('id_kec')."'";
		} else {
			$sql = "SELECT COUNT(DISTINCT(kdkec)) AS jmlkec FROM tbl_relawan WHERE thn_pemilihan = '".$this->session->userdata('thn_data')."'";
		}

		$data = $this->db->query($sql);

		return $data->row();
	}

}


