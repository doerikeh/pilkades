<!-- Default box -->
<div class="box">
    <div class="box-body">
        <?php
            if (($this->session->userdata('id_role') == 1) || ($this->session->userdata('id_role') == 2)){
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Filter</h3>
                <span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up" data-toggle="tooltip" title="Collapse"></i></span>
            </div>
            <div class="panel-body">
                <form id="form-filter" class="form-horizontal" method="POST">

                    <div class="form-group">
                        <label class="control-label col-md-2">Kecamatan</label>
                        <div class="col-md-4">

                            <select id="kdkec" class="form-control select2" style="width: 100%" >
                                <option value="">Semua</option>
                                <?php
                                foreach($kecamatan as $city){
                                    echo "<option value='".$city['id_kec']."'>".$city['nama_kec']."</option>";
                                }
                                ?>
                            </select>
                            <span class="help-block"></span>

                        </div>
                        <label class="control-label col-md-2">Desa</label>
                        <div class="col-sm-4">
                            <select id="kddesa" class="form-control select2" style="width: 100%">
                                <option value="">Semua</option>
                                <?php
                                foreach($dataDesanya as $desaku){
                                    echo "<option value='".$desaku->id_desa."'>".$desaku->nama_desa."</option>";
                                }
                                ?>
                            </select>
                            <span class="help-block"></span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label col-md-2">Jenis Kelamin</label>
                        <div class="col-md-4">
                            <select id="kelamin" class="form-control select2" style="width: 100%" >
                                <option value="">Semua</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                            <span class="help-block"></span>

                        </div>
                        <label class="control-label col-md-2">Kawin</label>
                        <div class="col-sm-4">
                            <select id="kawin" class="form-control select2" style="width: 100%">
                                    <option value="">Semua</option>
                                    <option value="sudah">Sudah</option>
                                    <option value="Belum">Belum</option>
                                </select>
                            <span class="help-block"></span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="LastName" class="col-sm-2 control-label"></label>
                        <div class="col-sm-4">
                            <button type="button" id="btn-filter" class="btn btn-primary">Filter</button>
                            <button type="button" id="btn-reset" class="btn btn-default">Reset</button>
                        </div>
                    </div>
                </form>
            </div> <!-- panel-body -->
        </div> <!-- panel -->
        <?php
            }
        ?>
        <div class="panel panel-primary">
            <div class="panel-body">
                    <div class="col-md-6 col-xs-12">
                    <?php 
                    echo '<h4 class="text-muted"><b>Daftar Relawan '.$this->session->userdata('thn_data').'</b></h4>';
                    ?>
                    </div>
                    <div class="col-md-2 col-xs-12">
                    <?php

                    if ($this->session->userdata('id_role') == 3) {
                        if (getStatusTransaksi('Pengelolaan Data Relawan')) {
                         echo '<button class="btn btn-success btn-block" onclick="add_person()" ><i class="glyphicon glyphicon-plus" ></i> Tambah</button>';
                        } else {
                            echo '<button class="btn btn-success btn-block" onclick="add_person()" disabled><i class="glyphicon glyphicon-plus" ></i> Tambah</button>';
                        }
                    } else {
                        echo '<button class="btn btn-success btn-block" onclick="add_person()"><i class="glyphicon glyphicon-plus" ></i> Tambah</button>';
                    }
                    ?>
                    </div>
                    <div class="col-md-2 col-xs-12">
                <button class="btn btn-default btn-block" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>
            </div>
            <div class="col-lg-2 col-xs-12">
                <a href="<?php echo base_url('relawan/export'); ?>" class="btn btn-default  btn-block"><i class="fa fa-file-excel-o"></i> Export Excel</a>
                
                </div>
            </div>
        </div>
    	<div class="table-responsive">
            <div class="col-md-12">
            <table class="table table-hover table-condensed" id="table">
        		<thead>
                    <tr>
						<th>No.</th>
						<th>Kecamatan</th>
						<th>Desa</th>
                        <th>Nik</th>
                        <th>Nama</th>
                        <th>TTL</th>
                        <th>Kawin</th>
                        <th>L/P</th>
                        <th style="width:80px;">Aksi</th>
            		</tr>
        		</thead>
        		<tbody>
                </tbody>
                <tfoot>
                    <tr>
						<th></th>
                        <th></th>
                        <th></th>
						<th></th>
                        <th></th>
                        <th></th>
						<th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
	        </table>
            </div>	
    	</div><!-- /.table-responsive -->
    </div><!-- /.box-body -->
    <div class="box-footer">
        &nbsp;
    </div><!-- /.box-footer-->
</div><!-- /.box -->


<!-- Bootstrap modal -->
<div class="modal" id="modal_form" role="dialog" tabindex="-1" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Relawan Form</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" value="" name="id"/> 
                    <input type="hidden" value="<?php echo $this->session->userdata('thn_data'); ?>" name="thn_pemilihan"/> 

                    <div class="form-body">
                        <!-- Desa -->
                        <div class="form-group">
                            <label class="control-label col-md-3">Kecamatan/Desa</label>
                            <div class="col-md-4">
                                <select id="kdkec" name="kdkec" class="form-control select2" style="width: 100%" >
                                   <option>-- Pilih Kecamatan --</option>
                                   <?php
                                   foreach($kecamatan as $city){
                                     echo "<option value='".$city['id_kec']."'>".$city['nama_kec']."</option>";
                                   }
                                   ?>
                                </select>
                                <span class="help-block"></span>

                            </div>
                            <div class="col-sm-5">
                                <select id="kddesa" name="kddesa" class="form-control select2" style="width: 100%">
                                  <option>-- Pilih Desa --</option>
                                  <?php
                                   foreach($dataDesanya as $desaku){
                                     echo "<option value='".$desaku->id_desa."'>".$desaku->nama_desa."</option>";
                                   }
                                   ?>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Nama Relawan</label>
                            <div class="col-md-9">

                                <input name="nama" placeholder="Nama Lengkap" class="form-control" type="text">
                                <span class="help-block"></span>

                            </div>

                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">N.K.K</label>
                            <div class="col-md-4">
                                <input name="nkk" placeholder="NKK" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">N.I.K</label>
                            <div class="col-md-4">
                                <input name="nik" placeholder="NIK" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Tempat/Tanggal Lahir</label>
                            <div class="col-md-6">
                                <input name="tpt_lahir" placeholder="Tempat Lahir" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                            <div class="col-md-3">
                                <input name="tgl_lahir" placeholder="yyyy-mm-dd" class="form-control datepicker" type="text" >
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Jenis Kelamin</label>
                            <div class="col-md-3">
                                <select name="jenis_kelamin" class="form-control">
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                            <label class="control-label col-md-3">Kawin</label>
                            <div class="col-md-3">
                                <select name="kawin" class="form-control" style="width: 100%;">
                                    <option value="Sudah">Sudah</option>
                                    <option value="Belum">Belum</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Alamat</label>
                            <div class="col-md-9">
                                <textarea name="alamat" rows = "2" placeholder="Alamat" class="form-control"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Rt</label>
                            <div class="col-md-4">
                                <input name="rt" placeholder="Rt" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Rw</label>
                            <div class="col-md-4">
                                <input name="rw" placeholder="Rw" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Difabel</label>
                            <div class="col-md-4">
                                <input name="difabel" placeholder="Difabel" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Ektp</label>
                            <div class="col-md-3">
                                <select name="ektp" class="form-control" style="width: 100%;">
                                    <option value="Sudah">Sudah</option>
                                    <option value="Belum">Belum</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Tps</label>
                            <div class="col-md-4">
                                <input name="tps" placeholder="Tps" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Sumber Data</label>
                            <div class="col-md-9">
                                <textarea name="sumberdata" placeholder="Sumber Data" class="form-control"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Keterangan Tambahan</label>
                            <div class="col-md-9">
                                <textarea name="keterangan" placeholder="Keterangan tambahan" class="form-control"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->


<!-- Bootstrap modal -->
<div class="modal" id="modal_view" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Lihat Detail Relawan</h3>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="col-md-8 detailRelawan">
                        <legend id="mydaerah">Relawan</legend>
                        <div class="row">
                            <div class="col-sm-4">Nama Relawan</div>
                            <div class="col-sm-8 namaCalon" id="mynama">nama</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">N.I.K</div>
                            <div class="col-sm-8 detailCalonText" id="mynkk">-</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">N.I.K</div>
                            <div class="col-sm-8 detailCalonText" id="mynik">-</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">TTL</div>
                            <div class="col-sm-8 detailCalonText" id="myttl">-</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">Jenis Kelamin</div>
                            <div class="col-sm-8 detailCalonText" id="jenis_kelamin">-</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">Kawin</div>
                            <div class="col-sm-8 detailCalonText" id="mykawin">-</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">Alamat</div>
                            <div class="col-sm-8 detailCalonText" id="myalamat">-</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">rt</div>
                            <div class="col-sm-8 detailCalonText" id="myrt">-</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">rw</div>
                            <div class="col-sm-8 detailCalonText" id="myrw">-</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">Difabel</div>
                            <div class="col-sm-8 detailCalonText" id="mydifabel">-</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">Ektp</div>
                            <div class="col-sm-8 detailCalonText" id="myektp">-</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">Tps</div>
                            <div class="col-sm-8 detailCalonText" id="mytps">-</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">Keterangan Tambahan</div>
                            <div class="col-sm-8 detailCalonText" id="myketerangan">-</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">Sumber Data</div>
                            <div class="col-sm-8 detailCalonText" id="mysumberdata">-</div>
                        </div>
                    </div>
                    <div class="box-body">
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->


<script type="text/javascript">

var save_method; //for save method string
var table;
var base_url = '<?php echo base_url();?>';

    $(document).ready(function() {

        //datatables
    table = $('#table').DataTable({ 
            
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('relawan/ajax_list')?>",
            "type": "POST",
            "data": function ( data ) {
                data.kdkec = $('#kdkec').val();
                data.kddesa = $('#kddesa').val();
                data.nik = $('#nik').val();
                data.nama_desa = $('#nama_desa').val();
                data.kawin = $('#kawin').val();
                data.jenis_kelamin = $('#jenis_kelamin').val();
                
            }
        },

            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "targets": [ 0 ], //first column / numbering column
                    "orderable": false, //set not orderable
                    responsivePriority: 1,
                    className: 'all'
                },
                { 
                    targets:[ -1 ],
                    orderable: false,
                    responsivePriority: 2, 
                    className: 'all'
                },
                {
                    targets:[ 1 ], 
                    visible: false, 
                    className: 'never'
                }
            ],
            aLengthMenu: [
                    [10, 25, 50, 100, 200, -1],
                    [10, 25, 50, 100, 200, "All"]
                ],
            iDisplayLength: 10,
            "order": [[ 0, 'asc' ]],
                drawCallback: function ( settings ) {
                    var api = this.api();
                    var rows = api.rows( {page:'current'} ).nodes();
                    var last=null;
                    api.column(1, {page:'current'} ).data().each( function ( group, i ) {
                    if ( last !== group ) {
                        $(rows).eq( i ).before(
                        '<tr class="bg-light-blue color-palette disabled"><td colspan="12"><b>'+group+'</b></td></tr>'
                        );

                    last = group;
                    }
                    } );
                }
    });



        //datepicker
        $('.datepicker').datepicker({
            autoclose: true,
            format: "yyyy-mm-dd",
            todayHighlight: true,
            orientation: "top auto",
            todayBtn: false,
            todayHighlight: true,  
            language: "id",
            locale: "id",
        });

        $('#btn-filter').click(function(){ //button filter event click
            table.ajax.reload();  //just reload table
            //alert($("input:checkbox:checked").val());
        });

        $('#btn-reset').click(function(){ //button reset event click
            $('#form-filter')[0].reset();
            //$('#photo').prop('checked', false);
            //$('#photo').iCheck('uncheck');
            table.ajax.reload();  //just reload table
        });

        //set input/textarea/select event when change value, remove class error and remove text help block 
        $("input").change(function(){
            $(this).parent().parent().removeClass('has-error');
            $(this).next().empty();
        });
        $("textarea").change(function(){
            $(this).parent().parent().removeClass('has-error');
            $(this).next().empty();
        });

        $('textarea').keypress(function(event) {
        if (event.which == 13) {
            event.preventDefault();
            var s = $(this).val();
            $(this).val(s+"\n");
        }
        });

        $("select2").change(function(){
            $(this).parent().parent().removeClass('has-error');
            $(this).next().empty();
        });

        //nested combobox
        $("#kdkec").change(function (){
            var url = "<?php echo site_url('relawan/add_ajax_desa');?>/"+$(this).val();
            $('#kddesa').load(url);
            return false;
        });

        $('input').iCheck({
            checkboxClass: 'icheckbox_minimal-red',
            radioClass: 'iradio_minimal-red',
            increaseArea: '20%' // optional
        });
        
        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        checkboxClass: 'icheckbox_minimal-red',
        radioClass: 'iradio_minimal-red'
        });

        $(document).on('click', '.panel-heading span.clickable', function(e){
            var $this = $(this);
            if(!$this.hasClass('panel-collapsed')) {
                $this.parents('.panel').find('.panel-body').slideUp();
                $this.addClass('panel-collapsed');
                $this.find('i').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
            } else {
                $this.parents('.panel').find('.panel-body').slideDown();
                $this.removeClass('panel-collapsed');
                $this.find('i').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
            }
        })  
    });

    function add_person()
    {
        save_method = 'add';
        $('#form')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#modal_form').modal('show'); // show bootstrap modal
        $('.modal-title').text('Tambah Relawan'); // Set Title to Bootstrap modal title

    }

    function edit_person(id)
    {
        save_method = 'update';
        $('#form')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string

    
        //Ajax Load data from ajax
        $.ajax({
            url : "<?php echo site_url('relawan/ajax_edit')?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {
                $('[name="id"]').val(data.id);
                $('[name="nama"]').val(data.nama);
                $('[name="nkk"]').val(data.nkk)
                $('[name="nik"]').val(data.nik);
                $('[name="tmp_lahir"]').val(data.tmp_lahir);
                $('[name="tgl_lahir"]').datepicker('update',data.tgl_lahir);
                $('[name="jenis_kelamin"]').val(data.jenis_kelamin);
                $('[name="kawin"]').val(data.kawin);
                $('[name="alamat"]').val(data.alamat);
                $('[name="rt"]').val(data.rt);
                $('[name="rw"]').val(data.rw);
                $('[name="difabel"]').val(data.difabel);
                $('[name="ektp"]').val(data.ektp);
                $('[name="keterangan"]').val(data.keterangan);
                $('[name="sumberdata"]').val(data.sumberdata);
                $('[name="tps"]').val(data.tps);
                //$('[name="id_kab"]').val(data.kdkab);
                $('[name="kdkec"]').val(data.kdkec);
                $('[name="kddesa"]').val(data.kddesa);

                $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                $('.modal-title').text('Edit relawan'); // Set title to Bootstrap modal title

            


            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax yeuh!!!');
            }
        });
    }

    function view_person(id)
    {
        save_method = 'update';
        $('#form')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string


        //Ajax Load data from ajax
        $.ajax({
            url : "<?php echo site_url('relawan/ajax_edit')?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {
                var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

                var date = new Date(data.tgl_lahir);
                var day = date.getDate(data.tgl_lahir);
                var month = date.getMonth(data.tgl_lahir);
                var yy = date.getYear();
                var year = (yy < 1000) ? yy + 1900 : yy;

                $('[name="id"]').val(data.id);
                
                $('#mynama').text(data.nama.toUpperCase());
                $('#mynkk').text(data.nkk);
                $('#mynik').text(data.nik);
                $('#myttl').text(data.tpt_lahir+', '+ day + ' ' + months[month] + ' ' + year);
                if((data.kelamin) == 'L')
                {
                    $('#jenis_kelamin').text('Laki-laki');
                }
                else
                {
                    $('#jenis_kelamin').text('Perempuan');
                }
                $('#mykawin').text(data.kawin);
                $("#myalamat").html(data.alamat);
                $('#myrt').text(data.rt);
                $('#myrw').text(data.rw);
                $("#mydifabel").html(data.difabel);
                $("#myektp").html(data.ektp);
                $("#mytps").html(data.tps);
                $("#myketerangan").html(data.keterangan);
                $('#mysumberdata').text(data.sumberdata);

                $('#modal_view').modal('show'); // show bootstrap modal when complete loaded
                $('.modal-title').text('Detail relawan'); // Set title to Bootstrap modal title

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax yeuh!!!');
            }
        });
    }

    function reload_table()
    {
        table.ajax.reload(null,false); //reload datatable ajax 
    }

    function save()
	{
	    $('#btnSave').text('saving...'); //change button text
	    $('#btnSave').attr('disabled',true); //set button disable 
	    var url;

	    if(save_method == 'add') {
	        url = "<?php echo site_url('relawan/ajax_add')?>";
	    } else {
	        url = "<?php echo site_url('relawan/ajax_update')?>";
	    }

	    // ajax adding data to database

	    var formData = new FormData($('#form')[0]);
	    $.ajax({
	        url : url,
	        type: "POST",
	        data: formData,
	        contentType: false,
	        processData: false,
	        dataType: "JSON",
	        success: function(data)
	        {

	            if(data.status) //if success close modal and reload ajax table
	            {
	                $('#modal_form').modal('hide');
	                reload_table();
	            }
	            else
	            {
	                for (var i = 0; i < data.inputerror.length; i++) 
	                {
	                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
	                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
	                }
	            }
	            $('#btnSave').text('Simpan'); //change button text
	            $('#btnSave').attr('disabled',false); //set button enable 


	        },
	        error: function (jqXHR, textStatus, errorThrown)
	        {
	            alert('Error adding / update data');
	            $('#btnSave').text('save'); //change button text
	            $('#btnSave').attr('disabled',false); //set button enable 

	        }
	    });
	}


    function xdelete_person(id)
    {
        if(confirm('Are you sure delete this data?'))
        {
            // ajax delete data to database
            $.ajax({
                url : "<?php echo site_url('relawan/ajax_delete')?>/"+id,
                type: "POST",
                dataType: "JSON",
                success: function(data)
                {
                    //if success reload ajax table
                    $('#modal_form').modal('hide');
                    reload_table();
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error deleting data');
                }
            });

        }
    }

    function delete_person(id)
    {
        swal({
            title: "Anda yakin?",
            text: "Data yang sudah terhapus tidak akan bisa dikembalikan.",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Ya, hapus!",
            cancelButtonText: "Tidak",
            closeOnConfirm: false,
            closeOnCancel: false
        },

        function(isConfirm) {

        if (isConfirm) {

            $.ajax({
                url : "<?php echo site_url('relawan/ajax_delete')?>/"+id,
                type: "POST",
                dataType: "JSON",
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error deleting data');
                },

                success: function(data) {
                    $('#modal_form').modal('hide');
                    reload_table();
                    swal("Terhapus!", "Data berhasil dihapus.", "success");
                }
            });
        } else {
            swal("Dibatalkan", "Data batal dihapus :)", "error");
        }

    });

}   

</script>