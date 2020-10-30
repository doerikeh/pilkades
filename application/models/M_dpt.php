<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_dpt extends CI_Model{

    var $table = 'tbl_wdpdt';
    var $column_order = array('tbl_wkecamatan.nama_kec','tbl_wdesa.nama_desa','tbl_wdpdt.nama','tbl_wdpdt.tgl_lahir','tbl_wdpdt.jenis_kelamin','tbl_wdpdt.rt','tbl_wdpdt.rw','tbl_wdpdt.tps',null);
    var $column_search = array('tbl_wkecamatan.nama_kec','tbl_wdesa.nama_desa','tbl_wdpdt.nama','tbl_wdpdt.alamat', 'tbl_wdpdt.jenis_kelamin','tbl_wdpdt.nik', );
    var $order = array('tbl_wdpdt.id' => 'desc'); // default order 

    public function __construct(){
      parent::__construct();
      $this->load->database();
    }
    
    private function _get_datatables_query(){

      $this->db->from($this->table);
      $this->db->join('tbl_wkecamatan','tbl_wkecamatan.id_kec=tbl_wdpdt.kdkec', 'left');
      $this->db->join('tbl_wdesa','tbl_wdesa.id_desa=tbl_wdpdt.kddesa', 'left');
  
      if ($this->session->userdata('id_role') == '3') {
        $this->db->where('tbl_wdpdt.kdkec',$this->session->userdata('id_kec'));
      }
      // $this->db->where('tbl_relawan.nik',$this->session->userdata('thn_data'));
      
      if (($this->session->userdata('id_role') == '1') || ($this->session->userdata('id_role') == '2')){

        if($this->input->post('kdkec'))
        {
          $this->db->like('tbl_wdpdt.kdkec', $this->input->post('kdkec'));
        }

        if($this->input->post('kddesa'))
        {
          $this->db->like('tbl_wdpdt.kddesa', $this->input->post('kddesa'));
        }

        if($this->input->post('rt'))
        {
          $this->db->like('tbl_wdpdt.rt', $this->input->post('rt'));
        }

        // if($this->input->post('rw'))
        // {
        //   $this->db->like('tbl_wdpdt.rw', $this->input->post('rw'));
        // }
        // if($this->input->post('tps'))
        // {
        //   $this->db->like('tbl_wdpdt.tps', $this->input->post('tps'));
        // }
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
		$this->db->from('tbl_wdpdt');
		$this->db->join('tbl_wkecamatan','tbl_wkecamatan.id_kec=tbl_wdpdt.kdkec', 'left');
    $this->db->join('tbl_wdesa','tbl_wdesa.id_desa=tbl_wdpdt.kddesa', 'left');
    // $this->db->join('tbl_relawan', 'tbl_relawan.id=tbl_wdpdt.kdrelawan', 'left');
		$this->db->where('tbl_wdpdt.id',$id);

		$query = $this->db->get();

		return $query->row();
  }

  public function get_join_id($id)
  {
    $this->db->select("*");
    $this->db->from('tbl_wdpdt');
    $this->db->join('tbl_wkecamatan','tbl_wkecamatan.id_kec=tbl_wdpdt.kdkec', 'left');
    $this->db->join('tbl_wdesa','tbl_wdesa.id_desa=tbl_wdpdt.kddesa', 'left'); 
    $this->db->join('tbl_relawan', 'tbl_relawan.id_relawan=tbl_wdpdt.kdrelawan', 'right');
    $this->db->where('tbl_wdpdt.id',$id);

		$query = $this->db->get();

		return $query->row();
  }
  
  public function join_by($id){
    $this->db->where($this->table, $data, $where);
    return $this->db->affected_rows();
  }


  public function delete_by_id($id){
    $this->db->where('id', $id);
    $this->db->delete($this->table);
  }
  
  public function update($where, $data){
    $this->db->update($this->table, $data, $where);
    return $this->db->affected_rows();
  }

  public function select_all() {
		$this->db->select('*');
		$this->db->from('tbl_wdpdt');

		$this->db->where('tbl_wdpdt.nik',$this->session->userdata('thn_data'));
		//$this->db->where('tbl_calon.kdkec',$this->session->userdata('id_kec'));

		$data = $this->db->get();

		return $data->result();
	}

	function load_data($ds)
	{
		$this->db->where('kddesa',$ds);
		$this->db->where('tbl_wdpdt.nik',$this->session->userdata('thn_data'));
		$query = $this->db->get($this->table);
		return $query->result_array();
  }
  
  public function select_by_kec()
	{
		$this->db->select('*');
		$this->db->from('tbl_wdpdt');
		$this->db->join('tbl_wkecamatan','tbl_wkecamatan.id_kec=tbl_wdpdt.kdkec', 'left');
		$this->db->join('tbl_wdesa','tbl_wdesa.id_desa=tbl_wdpdt.kddesa', 'left');
		if ($this->session->userdata('id_role') == '3') {
			$this->db->where('tbl_wdpdt.kdkec',$this->session->userdata('id_kec'));
		}
		//$this->db->order_by('tbl_calon.kdkec,tbl_calon.kddesa,tbl_calon.nourut', 'ASC');
		$this->db->order_by('tbl_wkecamatan.nama_kec,tbl_wdesa.nama_desa', 'ASC');
		$query = $this->db->get();

		return $query->result();
	}

	public function select_by_desa($desa)
	{
		$this->db->select('*');
		$this->db->from('tbl_wdpdt');
		$this->db->join('tbl_wkecamatan','tbl_wkecamatan.id_kec=tbl_wdpdt.kdkec', 'left');
		$this->db->join('tbl_wdesa','tbl_wdesa.id_desa=tbl_wdpdt.kddesa', 'left');
		if ($this->session->userdata('id_role') == '3') {
			$this->db->where('tbl_wdpdt.kddesa',$desa);
		}
		
		$query = $this->db->get();

		return $query->result();
  }

  public function insert_batch($data) {
		$this->db->insert_batch($this->table, $data);
		
		return $this->db->affected_rows();
	}

  public function check_nama($nama) {
		$this->db->where('nama', $nama);
		$data = $this->db->get($this->table);

		return $data->num_rows();
	}
  
}


