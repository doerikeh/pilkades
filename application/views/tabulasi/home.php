<div class="msg" style="display:none;">
  <?php echo @$this->session->flashdata('msg'); ?>
</div>
<?php
    foreach($tabulasi as $data){
        $merk[] = $data->nama_kec;
        // $stok[] = $data->DSSH;
        $hasil[] = (float) $data->s_hasil;
        if ($data->DSSH > 0) {
            $stok[] = (float) ($data->DSSH);
        } else { $stok[] = 0;}
    }
?>
<!-- Default box -->
<div class="box">

    <div class="box-body">
        <div class="panel panel-primary no-print">
            <div class="panel-body">
                <span class="pull-right">
                <button class="btn btn-default" onclick="document.location.reload(true);"><i class="glyphicon glyphicon-refresh"></i> Reload</button>
                </span>
                <?php 
                echo '<h4 class="text-muted"><b>Tabulasi Hasil Pemilihan Kepala Desa Tahun '.$this->session->userdata('thn_data').'</b></h4>';
                ?>
            </div>
        </div>
        <div class="row">
        <div class="col-md-8 text-center">
            <h3>Grafik Suara Masuk</h3>
            <span>Grafik Suara Masuk</span>
            <div>
                <canvas id="canvassatu" ></canvas>
            </div>
        </div>

        <div class="col-md-4">
            <center>
                <h3>Tabel Suara Masuk</h3>
            <span>Suara Masuk Per Kecamatan</span>
            <br />
            <br />
            <table class="table-condensed" border="1px">
                <tr>
                    <th class="text-center bg-blue" style="width: 250px">Kecamatan</th>
                    <th class="text-center bg-blue" style="width: 100px">Suara masuk</th>
                    <!-- <th class="text-center bg-blue" style="width: 100px">%</th> -->
                </tr>                
                <?php
                foreach ($tabulasi as $kec) {
                    echo "<tr>";
                    echo "<td><a href='".base_url()."tabulasi/detailkec/".$kec->kdkec."'>".$kec->nama_kec."</a></td>";
                    echo "<td class='text-right'>".number_format($kec->DSSH)."</td>";
                    
                    // if ($kec->DSSH > 0) {
                    //     echo "<td class='text-right'>".number_format( $kec->DSSH)*100,2)."</td>";
                    // } else { echo "<td class='text-right'>".number_format(0,2)."%</td>";}
                    // echo "</tr>";
                    
                }
                ?>
            </table>
            </center>
        </div>
    	</div>
    </div><!-- /.box-body -->
    <div class="box-footer">
        &nbsp;
    </div><!-- /.box-footer-->
</div><!-- /.box -->

<!-- "/".number_format($data->DSSH). /$data->DSSH)*100,2)."% -->
<!--Load chart js-->
<script type="text/javascript" src="<?php echo base_url().'assets/plugins/chartjs/Chart.min.js'?>"></script>
<script>

    var ctx = document.getElementById("canvassatu").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'horizontalBar',
        data: {
            labels: <?php echo json_encode($merk);?>,
            datasets: [{
                label: '% Suara Masuk',
                data: <?php echo json_encode($stok);?>,
                backgroundColor: [
                'rgba(255, 99, 132, 0.8)',
                'rgba(54, 162, 235, 0.8)',
                'rgba(255, 206, 86, 0.8)',
                'rgba(75, 192, 192, 0.8)',
                'rgba(153, 102, 255, 0.8)',
                'rgba(255, 159, 64, 0.8)'
                ],
                borderColor: [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            },

        }
    });


</script>