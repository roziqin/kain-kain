@extends('backend/layouts/index')
@section('css')
<link href="{{asset('backend/plugins/iCheck/all.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('backend/plugins/fileinput/fileinput.min.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('js')
<script src="{{asset('backend/plugins/iCheck/icheck.min.js')}}" type="text/javascript"></script>
<script src="{{asset('backend/plugins/fileinput/fileinput.min.js')}}" type="text/javascript"></script>
<script src="{{asset('backend/plugins/bootbox/bootbox.min.js')}}" type="text/javascript"></script>
<script src="{{asset('backend/plugins/input-mask/jquery.inputmask.js')}}" type="text/javascript"></script>
<script src="{{asset('backend/plugins/input-mask/jquery.inputmask.extensions.js')}}" type="text/javascript"></script>
<script src="{{asset('backend/plugins/input-mask/jquery.inputmask.numeric.extensions.js')}}" type="text/javascript"></script>
<script>
$('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
    checkboxClass: 'icheckbox_minimal-blue',
    radioClass: 'iradio_minimal-blue'
});
//Red color scheme for iCheck
$('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
    checkboxClass: 'icheckbox_minimal-red',
    radioClass: 'iradio_minimal-red'
});
//Flat red color scheme for iCheck
$('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
    checkboxClass: 'icheckbox_flat-green',
    radioClass: 'iradio_flat-green'
});
$("#price").inputmask();
$("#price1").inputmask();
$("#input-2").fileinput({
    uploadUrl: "{{route('backend.image.store')}}",
    uploadAsync: true,
    minFileCount: 1,
    maxFileCount: 5,
    allowedFileExtensions: ["jpg", "gif", "png", "jpeg"],
    uploadExtraData: function() {  // callback example
        var out = {_token: "{{ csrf_token() }}"};
        return out;
    }
});
var max_fields = 10; //maximum input boxes allowed
var wrapper = $(".input_fields_wrap"); //Fields wrapper
var add_button = $(".add_field_button"); //Add button 
var button = '<div class="form-group"><div class="row"><div class="col-xs-2"><input type="text" class="form-control" placeholder="Name" name="name[]"></div><div class="col-xs-2"><input type="text" class="form-control" placeholder="Value" name="value[]"></div><div class="col-xs-3"><button type="button" class="btn btn-default remove_field">Remove field</button></div></div></div>'

var x = 1; //initlal text box count
$(add_button).click(function(e) { //on add input button click
    e.preventDefault();
    if (x < max_fields) { //max input box allowed
        x++; //text box increment
        $(wrapper).append(button); //add input box
    }
});

$(wrapper).on("click", ".remove_field", function(e) { //user click on remove text
    e.preventDefault();
    $(this).closest('.form-group').remove();
    x--;
})
</script>
@endsection
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{$title}}
        <small>{{$sub_title}}</small>
    </h1>
    {!! Breadcrumbs::render('productcreate') !!}
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">{{$title}}</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            @if(count($errors))
                            <div class="alert alert-danger">
                                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            <!-- Custom Tabs -->
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#add_pro" data-toggle="tab">Add Product</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="add_pro">
                                        <form role="form" method="post" action="{{route('backend.product.store')}}" enctype="multipart/form-data">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <div class="form-group">
                                                <label>Category</label>
                                                <select id="form-field-select-3" class="form-control search-select" name="id_category">
                                                    <option value="">Pilih Category</option>
                                                    @foreach($category as $key=>$val)
                                                    <option value="{{$key}}">{{$val}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>SKU</label>
                                                <input type="text" name="product_sku" class="form-control" value="{{old('product_sku')}}">
                                            </div>
                                            <div class="form-group">
                                                <label>Product Name</label>
                                                <input type="text" name="product_name" class="form-control" value="{{old('product_name')}}">
                                            </div>
                                            <div class="form-group">
                                                <label>Product Description</label>
                                                <textarea name="product_description" class="form-control">{{old('product_description')}}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Product Price (Meter)</label>
                                                <input id="price1" data-inputmask="'alias': 'decimal', 'groupSeparator': '.', 'autoGroup': true" type="text" name="product_price" class="form-control" value="{{old('product_price')}}">
                                            </div>
                                            <div class="form-group">
                                                <label>Product Price (Roll)</label>
                                                <input id="price" data-inputmask="'alias': 'decimal', 'groupSeparator': '.', 'autoGroup': true" type="text" name="product_price_roll" class="form-control" value="{{old('product_price_roll')}}">
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-xs-2" >
                                                        <label>Per Roll</label>
                                                        <div class="input-group">
                                                            <input type="text" name="product_cm" class="form-control" value="{{old('product_cm')}}" style="text-align: center;">
                                                            <span class="input-group-addon">m</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-2" >
                                                        <label>Product Wide</label>
                                                        <div class="input-group">
                                                            <input type="text" name="product_lebar" class="form-control" value="{{old('product_lebar')}}" style="text-align: center;">
                                                            <span class="input-group-addon">m</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-2" >
                                                        <label>Product Weight</label>
                                                        <div class="input-group">
                                                            <input type="text" name="product_berat" class="form-control" value="{{old('product_berat')}}" style="text-align: center;">
                                                            <span class="input-group-addon">Kg/m</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputFile">Images</label>
                                                <input id="input-2" type="file" name='image[]' multiple=true class="file-loading" data-show-upload="false">
                                            </div>
                                            <div class="input_fields_wrap">
                                                <div class="form-group">
                                                    <label>Pilih Ukuran</label>
                                                    <div class="row">
                                                        <div class="col-xs-2 hidden">
                                                            <input type="hidden" class="form-control" placeholder="Name" name="name[]" value="Ukuran">
                                                        </div>
                                                        <div class="col-xs-2">
                                                            <!--<input type="text" class="form-control" placeholder="Value" name="value[]">-->
                                                            <select class="form-control search-select" name="value[]">
                                                                <option value="Meter">Meter</option>
                                                                <option value="Roll">Roll</option>
                                                                <option value="Meter,Roll">Meter & Roll</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group hidden">
                                                <div class="row">
                                                    <div class="col-xs-3">
                                                        <button type="button" class="btn btn-default add_field_button">Add field</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>
                                                    <input type="checkbox" class="minimal" name='product_status' /> Active ?
                                                </label>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                                <a href="{{URL::previous()}}" class="btn btn-warning">Back</a>
                                            </div>
                                        </form>
                                    </div><!-- /.tab-pane -->
                                </div><!-- /.tab-content -->
                            </div><!-- nav-tabs-custom -->
                        </div><!-- /.col -->
                    </div> <!-- /.row -->
                    <!-- END CUSTOM TABS -->
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</section><!-- /.content -->
@stop