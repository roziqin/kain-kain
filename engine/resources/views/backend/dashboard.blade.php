

@extends('backend/layouts/index')
@section('css')
<link href="{{asset('backend/plugins/datatables/dataTables.bootstrap.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('js')
<script src="{{asset('backend/dist/js/Chart.bundle.min.js')}}" type="text/javascript"></script>
<script src="{{asset('backend/dist/js/utils.js')}}" type="text/javascript"></script>
<script>
        

        $(document).ready(function() {
           
            $.get('{{url("backend/getData")}}', function(ev) {
                $('#data').html(ev);
            });
            
        });

        $.ajax({
            url: "{{url('backend/getData')}}",
            type: "GET",
            success: function(data) {
                console.log(data);

                var month = [];
                var total = [];

                for (var i in data) {
                    month.push(data[i].month_text);
                    total.push(data[i].total);
                }

                var chartdata = {
                    labels: month,
                    datasets: [{
                            label: "Total",
                            fill: false,
                            lineTension: 0.1,
                            backgroundColor: "rgba(59, 89, 152, 0.75)",
                            borderColor: "rgba(59, 89, 152, 1)",
                            pointHoverBackgroundColor: "rgba(59, 89, 152, 1)",
                            pointHoverBorderColor: "rgba(59, 89, 152, 1)",
                            data: total
                        },
                    ]
                };


                var ctx = $("#mycanvas");

                var LineGraph = new Chart(ctx, {
                    type: 'line',
                    data: chartdata
                });
            },
            error: function(data) {

            }
        });
        
    </script>


@endsection
@section('content')
<?php //header("Refresh:0"); ?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{$title}}
    </h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Data Jumlah Transaksi Bulanan</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div style="width:100%;">
                        <canvas id="mycanvas" height="100"></canvas>
                    </div>
                    
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <div class="col-md-6">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Barang Terjual/hari</h3>
                </div>
                <!-- /.box-header -->
                <?php
                $data[][]= array();
                $z = 0;
                ?>
                @foreach($jumlahprodukmeter as $jumlahprodukmeter)
                <?php
                    $data[$z][0] = $jumlahprodukmeter->product_id;
                    $data[$z][1] = $jumlahprodukmeter->quantity;
                    $z++;
                ?>
                @endforeach

                <?php
                $data1[][]= array();
                $y = 0;
                ?>
                @foreach($jumlahprodukroll as $jumlahprodukroll)
                <?php
                    $data1[$y][0] = $jumlahprodukroll->product_id;
                    $data1[$y][1] = $jumlahprodukroll->quantity;
                    $y++;
                ?>

                @endforeach
                <div class="box-body no-padding">
                    <table class="table table-striped">
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Nama Kain</th>
                            <th style="width: 40px">Meter</th>
                            <th style="width: 40px">Roll</th>
                        </tr>
                        <?php $n=1; ?>
                        @foreach($tampilproduk as $tampilproduk)
                            <tr>
                                <td>{{$n}}</td>
                                <td>{{$tampilproduk->product_name}}</td>
                                <td style="text-align: right;">
                                    <?php 
                                    $x = 0;
                                    while($x < $z) {
                                        
                                        # code...
                                        if ($data[$x][0]==$tampilproduk->product_id) {
                                            echo round($jumlahprodukmeter->quantity, 2);
                                        }
                                        
                                        
                                        $x++;
                                    }
                                    ?>
                                </td>
                                <td style="text-align: right;">
                                    <?php 
                                    $x = 0;
                                    while($x < $y) {
                                        
                                        # code...
                                        if ($data1[$x][0]==$tampilproduk->product_id) {
                                            echo round($jumlahprodukroll->quantity, 2);
                                        } else {
                                            
                                            
                                        }
                                        $x++;
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php $n++; ?>
                        @endforeach
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
            
        </div>
                
    </div><!-- /.row -->
</section><!-- /.content -->
    
    @stop