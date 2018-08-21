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
                    <h3 class="box-title">List Inbox</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <table id="barcode-table" class="table table-bordered table-striped table-responsive">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $n = 1; ?>
                            @foreach($tampilinbox as $tampilinbox)
                                <?php
                                if ($tampilinbox->contact_us_status==0) {
                                    # code...
                                    $ket="<span class='label bg-red'>Belum Dibaca</span>";
                                } else {
                                    # code...
                                    $ket="<span class='label bg-green'>Sudah Dibaca</span>";
                                }
                                
                                ?>
                                <tr>
                                    <td>#{{$n}}</td>
                                    <td>{{$tampilinbox->created_at}}</td>
                                    <td>{{$tampilinbox->contact_us_name}}</td>
                                    <td>{{$tampilinbox->contact_us_email}}</td>
                                    <td>{{$tampilinbox->contact_us_subject}}</td>
                                    <td><?php echo $ket; ?></td>
                                    <td>
                                        <form role="form" method="POST" action="{{route('updateinbox')}}" enctype="multipart/form-data">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="id" value="{{$tampilinbox->id}}">
                                            <button type="submit" class="btn btn-info btn-sm">Lihat</button> 
                                        </form>
                                    </td>
                                </tr>
                                <?php $n++; ?>
                            @endforeach
                       
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</section><!-- /.content -->
@stop