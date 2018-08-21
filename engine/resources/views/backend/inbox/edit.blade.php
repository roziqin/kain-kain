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

<section class="content custom">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Read Inbox</h3>

            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <div class="mailbox-read-info">
                <h3>Subject "{{$tampiledit->contact_us_subject}}"</h3>
                <h5>Dari: {{$tampiledit->contact_us_email}}
                  <span class="mailbox-read-time pull-right">{{$tampiledit->created_at}}</span></h5>
              </div>
              <!-- /.mailbox-read-info -->
              <div class="mailbox-read-message">
                <p>{{$tampiledit->contact_us_message}}</p>
              </div>
              <!-- /.mailbox-read-message -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer" style="display: inline-block; width: 100%;">
              <div class="pull-right" style="display: inline-block;">
                <a href="{{url('backend/inbox?id=0')}}" class="btn btn-default"><i class="fa fa-reply"></i> Kembali</a>
              </div>
            </div>
            <!-- /.box-footer -->
          </div>
        </div><!-- /.col -->
    </div><!-- /.row -->
</section><!-- /.content -->
@stop