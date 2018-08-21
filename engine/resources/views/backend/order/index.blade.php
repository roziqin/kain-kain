    <?php 
    session_start();
    $_SESSION['tabcontent'] = '';
    ?>

@extends('backend/layouts/index')
@section('css')
<link href="{{asset('backend/plugins/datatables/dataTables.bootstrap.css')}}" rel="stylesheet" type="text/css" />
@endsection
@endsection
@section('js')
<script src="{{asset('backend/plugins/datatables/jquery.dataTables.js')}}" type="text/javascript"></script>
<script src="{{asset('backend/plugins/datatables/dataTables.bootstrap.js')}}" type="text/javascript"></script>

<script type="text/javascript">
    $('#barcode-table').DataTable();
</script>
@endsection
@section('content')
<?php //header("Refresh:0"); ?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{$title}}
    </h1>
    {!! Breadcrumbs::render('barcode') !!}
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">List Order</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <table id="barcode-table" class="table table-bordered table-striped table-responsive">
                        <thead>
                            <tr>
                                <th>No Order</th>
                                <th>Customer</th>
                                <th>Alamat Pengiriman</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tampilorder as $tampilorder)
                                <tr>
                                    <td>{{$tampilorder->order_id}}</td>
                                    <td>{{$tampilorder->name}}</td>
                                    <td>{{$tampilorder->address}}, {{$tampilorder->name_regencies}}, {{$tampilorder->name_provinces}}</td>
                                    <td>{{$tampilorder->created_at}}</td>
                                    <td>{{$tampilorder->total_price}}</td>
                                    <td>{{$tampilorder->order_status}}</td>
                                    <td>

                                        <a class="btn btn-info btn-sm" href="{{url('backend/order',$tampilorder->id)}}/edit">Edit</a> 
                                    </td>
                                </tr>
                            @endforeach
                       
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</section><!-- /.content -->
@stop