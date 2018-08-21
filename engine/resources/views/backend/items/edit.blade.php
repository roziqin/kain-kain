@extends('backend/layouts/index')
@section('css')
<link href="{{asset('backend/plugins/iCheck/all.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('backend/plugins/fileinput/fileinput.min.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('js')
<script src="{{asset('backend/plugins/iCheck/icheck.min.js')}}" type="text/javascript"></script>
<script src="{{asset('backend/plugins/fileinput/fileinput.min.js')}}" type="text/javascript"></script>

@endsection
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{$title}}
        <small>{{$sub_title}}</small>
    </h1>
    {!! Breadcrumbs::render('productedit') !!}
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">{{$title}}</h3>
                </div><!-- /.box-header -->
                <form role="form" action="{{route('backend.items.update',$iditem)}}" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="put">
                    <input type="hidden" name="id" value="{{$iditem}}">
                    <div class="box-body">
                        <div class="form-group">
                            <label>Item Barcode</label>
                            <input type="hidden" name="productid" id="productid" class="form-control" value="<?php echo $tampilitembarcode->item_id_product; ?>">
                            <input type="text" name="barcodenumber" id="barcodenumber" class="form-control" value="<?php echo $tampilitembarcode->barcode_number; ?>" disabled="disabled">
                        </div>
                        <div class="form-group">
                            <label>Item Per Roll(m)</label>
                            <input type="text" name="item_perroll" id="item_perroll" class="form-control" value="<?php echo $tampilitembarcode->item_perroll; ?>">
                        </div>
                    </div><!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="{{URL::previous()}}" class="btn btn-warning">Back</a>
                    </div>
                </form>
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</section><!-- /.content -->
@stop