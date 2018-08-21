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
        <small>{{$sub_title}}</small>
    </h1>
    {!! Breadcrumbs::render('barcode') !!}
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-4">
            <div class="box">
                <?php

                if ($_GET['id']==0) {
                    # code...
                ?>
                <div class="box-header">
                    <h3 class="box-title">Tambah Product</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <form role="form" action="{{route('backend.barcode.store')}}" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group">
                            <label>No Barcode</label>
                            <input type="text" name="no_barcode" id="no_barcode" class="form-control" value="">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" id="submititem">Submit</button>
                        </div>
                    </form>
                </div><!-- /.box-body -->
                <?php
                } else {
                    # code...
                ?>
                <div class="box-header">
                    <h3 class="box-title">Edit Product</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <?php $ids = $_GET['barcode_id']; ?>
                    <form method="post" id="" action="{{route('backend.barcode.update',$ids)}}" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="put">
                        <input type="hidden" name="id" value="{{$ids}}">
                        <div class="form-group">
                            <label>No Barcode</label>
                            <input type="text" name="no_barcode" id="no_barcode" class="form-control" value="<?php echo $_GET['barcode_number'];?>">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" id="submititem">Edit</button>
                        </div>
                    </form>
                </div><!-- /.box-body -->
                <?php
                }
                

                ?>
            </div><!-- /.box -->
        </div><!-- /.col -->
        <div class="col-xs-8">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">List Barcode</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <table id="barcode-table" class="table table-bordered table-striped table-responsive">
                        <thead>
                            <tr>
                                <th>No Barcode</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        @foreach($tampilbarcode as $tampilbarcode)
                        <?php
                            if ($tampilbarcode->barcode_status==0) {
                                # code...
                                $status = "Kosong";

                            } else {
                                # code...
                                $status = "Terpakai";
                            }
                        

                        ?>
                            <tr>
                                <td>{{$tampilbarcode->barcode_number}}</td>
                                <td><?php echo $status; ?></td>
                                <td>
                                    <?php $ids = $tampilbarcode->barcode_id; ?>
                                    <form method="post" id="" action="{{route('backend.barcode.destroy',$ids)}}" enctype="multipart/form-data">
                                        <input type="hidden" name="_method" value="delete">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="id" value="{{$ids}}">
                                        <a class="btn btn-info btn-sm" href="{{url('backend/barcode',$tampilbarcode->barcode_id)}}/edit">Edit</a> 
                                        <button class="btn btn-info btn-sm" onclick="return confirm('Are you sure to delete this data');">Delete</button>
                                    </form>
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