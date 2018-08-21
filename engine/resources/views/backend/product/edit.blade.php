<?php 
session_start();
?>
@extends('backend/layouts/index')
@section('css')
<link href="{{asset('backend/plugins/iCheck/all.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('backend/plugins/fileinput/fileinput.min.css')}}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{asset('backend/plugins/jQuery-Tags-Input/jquery.tagsinput.css')}}">
<link href="{{asset('backend/plugins/datatables/dataTables.bootstrap.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('js')
<script src="{{asset('backend/plugins/datatables/jquery.dataTables.js')}}" type="text/javascript"></script>
<script src="{{asset('backend/plugins/datatables/dataTables.bootstrap.js')}}" type="text/javascript"></script>
<script src="{{asset('backend/plugins/iCheck/icheck.min.js')}}" type="text/javascript"></script>
<script src="{{asset('backend/plugins/fileinput/fileinput.min.js')}}" type="text/javascript"></script>
<script src="{{asset('backend/plugins/bootbox/bootbox.min.js')}}" type="text/javascript"></script>
<script src="{{asset('backend/plugins/input-mask/jquery.inputmask.js')}}" type="text/javascript"></script>
<script src="{{asset('backend/plugins/input-mask/jquery.inputmask.extensions.js')}}" type="text/javascript"></script>
<script src="{{asset('backend/plugins/input-mask/jquery.inputmask.numeric.extensions.js')}}" type="text/javascript"></script>
<script src="{{asset('backend/plugins/jQuery-Tags-Input/jquery.tagsinput.js')}}"></script>
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('backend/plugins/select2/dist/css/select2.min.css')}}">
<style type="text/css">
    .select2-container {
        width: 100% !important;
    }
    .select2-container--default .select2-selection--single {
        border-radius: 0px;
        height: 34px;
        border-color: #d2d6de;
    }
</style>
<script src="{{asset('backend/plugins/select2/dist/js/select2.full.min.js')}}"></script>
<script>

//Initialize Select2 Elements
$('.select2').select2()

$('#tags_2').tagsInput({
    width: 'auto'
});
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

/*
   var url = "{{url('api/item/')}}";
      $.getJSON(url, function(data) {
          $.each(data, function(index, data) {
           $('#item-table-body').append('<tr><td>'+data.item_barcode+'</td><td>'+data.item_perroll+'</td><td>'+data.item_totalmeter+'</td><td></td></tr>');
    });
 
   });
*/
/*
    var base_url = "{{url('backend/product/')}}";
    var public = "{{URL::asset('')}}";
    var proDt = $("#item-table").dataTable({
        "iDisplayLength": "{{shopOpt('product_perpage_admin')}}",
        "sAjaxSource": "{{route('api.item')}}",
        "bServerSide": true,
        "bProcessing": true,
        "aoColumns": [
            {"mData": "item_id", sWidth: '15%', "bSortable": false, "bSearchable": false,},
            {"mData": "barcode_number", sWidth: '15%'},
            {"mData": "item_perroll", sWidth: '15%'},
            {"mData": "item_totalmeter", sWidth: '15%'},
            {
                "mData": null,
                "sWidth": '20%',
                "bSortable": false,
                "mRender": function(data, type, full) {
                    return '<a class="btn btn-info btn-sm" href="' + base_url + '/' + full.id + '/edit">' + 'Edit' + '</a> <a class="btn btn-info btn-sm delete-row" href="deleteitem">' + 'Delete' + '</a>';
                }
            },
        ],
    });
    */


var base_url = "{{url('backend/product/')}}";
var public = "{{URL::asset('')}}";

$("#price").inputmask();
$("#price1").inputmask();
$("#input-2").fileinput({
    uploadUrl: "{{route('backend.image.store')}}",
    uploadAsync: true,
    minFileCount: 1,
    maxFileCount: 5,
    allowedFileExtensions: ["jpg", "gif", "png", "jpeg"],
    uploadExtraData: function() {  // callback example
        var out = {_token: "{{ csrf_token() }}", id_product: "{{$product->id}}"};
        return out;
    }
});
var img = function() {
    $.get("{{route('backend.image.show',$product->id)}}", function(data) {
        $("#image-pro").html(data);
    });
}
$('#input-2').on('fileuploaded', function(event, data, previewId, index) {
    var form = data.form, files = data.files, extra = data.extra,
            response = data.response, reader = data.reader;
    img();
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
$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
    var target = $(e.target).attr("href") // activated tab
    if (target == '#add_image') {
        img();
    }
})
$("#image-pro").on('click', '.del-img', function(e) {
    e.preventDefault();
    var id = $(this).attr('href');
    bootbox.confirm("Are you sure to delete this product?", function(result) {
        if (result) {
            $.ajax({
                method: "DELETE",
                url: "{{url('backend/image')}}/" + id,
                data: {_token: "{{csrf_token()}}"}
            }).done(function(msg) {
                if (msg.success) {
                    img();
                }
            });
        }
    });
});
$("#formitem").submit(function(e) {
    e.preventDefault();
    var loginForm = $("#formitem");
    var formData = loginForm.serialize();
            console.log("cek");

      /*alert(formData);*/

    $.ajax({
        url:"additem",
        method: "GET",
        type:'get',
        data:formData,
        success:function(data){
            console.log("ok");
             //alert(data);     //for redirecting instead of alert try below code

            if(data == "") {       //True Case i.e. passed validation
            }
            else {                 //False Case: With error msg
                $("#msg").html(data);  //$msg is the id of empty msg

            }
                $("#item_barcode").val("");
                $("#item_perroll").val("");
             
        },
        error: function (data) {
             /*console.log(data);*/
            alert(data);
        }
    });

    $.ajax({
            url: "getbarcode",
            type: "GET",
            dataType: "json",
            success:function(data)
            {

                console.log(data);
                $('select[name="barcodeid"]').empty();
                $.each(data, function(key, value) {
                    $('select[name="barcodeid"]').append('<option value="'+ value.barcode_id +'">'+ value.barcode_number +'</option>');
                });

            }
        });

});
 
</script>
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
                <!--                <div class="box-header">
                                    <h3 class="box-title"></h3>
                                </div> /.box-header -->
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
                                    <?php if ($_SESSION['tabcontent']=='additem') {
                                        # code...
                                        $item = 'active';
                                        $other = '';
                                    } else {
                                        # code...
                                        $item = '';
                                        $other = 'active';
                                    }
                                    ?>
                                    
                                    <li class="<?php echo $other;?>"><a href="#add_pro" data-toggle="tab">Edit Product</a></li>
                                    <li><a href="#add_image" data-toggle="tab">Manage Image</a></li>
                                    <li><a href="#add_meta" data-toggle="tab">Meta Product</a></li>
                                    <li class="<?php echo $item;?>"><a href="#add_item" data-toggle="tab">Item Product</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane <?php echo $other;?>" id="add_pro">
                                        <form role="form" method="post" action="{{route('backend.product.update',$product->id)}}" enctype="multipart/form-data">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="_method" value="put">
                                            <input type="hidden" name="id" value="{{$product->id}}">
                                            <div class="form-group">
                                                <label>Category</label>
                                                <select id="form-field-select-3" class="form-control search-select" name="id_category">
                                                    <option value="">Pilih Category</option>
                                                    @foreach($category as $key=>$val)
                                                    <option value="{{$key}}" {{$key == $product->id_category ? 'selected="selected"' : ''}}>{{$val}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>SKU</label>
                                                <input type="text" name="product_sku" class="form-control" value="{{$product->product_sku}}">
                                            </div>
                                            <div class="form-group">
                                                <label>Product Name</label>
                                                <input type="text" name="product_name" class="form-control" value="{{$product->product_name}}">
                                            </div>
                                            <div class="form-group">
                                                <label>Product Description</label>
                                                <textarea name="product_description" class="form-control">{{$product->product_description}}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Product Price (Meter)</label>
                                                <input id="price1" data-inputmask="'alias': 'decimal', 'groupSeparator': '.', 'autoGroup': true" type="text" name="product_price" class="form-control" value="{{$product->product_price}}">
                                            </div>
                                            <div class="form-group">
                                                <label>Product Price (Roll)</label>
                                                <input id="price" data-inputmask="'alias': 'decimal', 'groupSeparator': '.', 'autoGroup': true" type="text" name="product_price_roll" class="form-control" value="{{$product->product_price_roll}}">
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-xs-2" >
                                                        <label>Per Roll</label>
                                                        <div class="input-group">
                                                            <input type="text" name="product_cm" class="form-control" value="{{$product->product_cm}}" style="text-align: center;">
                                                            <span class="input-group-addon">m</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-2" >
                                                        <label>Product Wide</label>
                                                        <div class="input-group">
                                                            <input type="text" name="product_lebar" class="form-control" value="{{$product->product_lebar}}" style="text-align: center;">
                                                            <span class="input-group-addon">m</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-2" >
                                                        <label>Product Weight</label>
                                                        <div class="input-group">
                                                            <input type="text" name="product_berat" class="form-control" value="{{$product->product_berat}}" style="text-align: center;">
                                                            <span class="input-group-addon">Kg/m</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="input_fields_wrap">
                                                @if(count($product->attribute) > 0)
                                                @foreach($product->attribute as $key => $attr)
                                                <div class="form-group">
                                                    @if($key == 0)
                                                    <label>Pilih Ukuran</label>
                                                    @endif
                                                    <div class="row">
                                                        <div class="col-xs-2 hidden">
                                                            <input type="text" class="form-control" placeholder="Name" name="name[]" value="{{$attr->name}}">
                                                        </div>
                                                        <div class="col-xs-2">
                                                            <!--<input type="text" class="form-control" placeholder="Value" name="value[]" value="{{$attr->values}}">-->
                                                            <?php
                                                            $m='';
                                                            $r='';
                                                            $mr='';
                                                            if ($attr->values=='Meter') {
                                                                # code...
                                                                $m = 'selected';
                                                            } elseif ($attr->values=='Roll') {
                                                                # code...
                                                                $r = 'selected';
                                                            } else {
                                                                # code...
                                                                $mr = 'selected';
                                                            }
                                                            

                                                            ?>
                                                            <select class="form-control search-select" name="value[]">
                                                                <option value="Meter" <?php echo $m; ?>>Meter</option>
                                                                <option value="Roll" <?php echo $r; ?>>Roll</option>
                                                                <option value="Meter,Roll" <?php echo $mr; ?>>Meter & Roll</option>
                                                            </select>
                                                        </div>
                                                        @if($key != 0)
                                                        <div class="col-xs-3">
                                                            <button type="button" class="btn btn-default remove_field">Remove field</button>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endforeach
                                                @else
                                                <div class="form-group">
                                                    <label>Attributes</label>
                                                    <div class="row">
                                                        <div class="col-xs-2">
                                                            <input type="text" class="form-control" placeholder="Name" name="name[]" value="{{old('name[]')}}">
                                                        </div>
                                                        <div class="col-xs-2">
                                                            <input type="text" class="form-control" placeholder="Value" name="value[]" value="{{old('value[]')}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
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
                                                    @if($product->product_status == 1)
                                                 
                                                    <input type="checkbox" class="minimal" name='status' checked /> Active ?
                                                    @else
                                          
                                                    <input type="checkbox" class="minimal" name='status' /> Active ?
                                                    @endif
                                                </label>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                                <a href="{{URL::previous()}}" class="btn btn-warning">Back</a>
                                            </div>
                                        </form>
                                    </div><!-- /.tab-pane -->
                                    <div class="tab-pane" id="add_image">
                                        <form role="form" method="post" action="{{route('backend.image.store')}}" enctype="multipart/form-data">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="id_product" value="{{$product->id}}">
                                            <div class="form-group">
                                                <label for="exampleInputFile">Images</label>
                                                <input id="input-2" type="file" name='image[]' multiple=true class="file-loading" data-show-upload="false">
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                                <a href="{{URL::previous()}}" class="btn btn-warning">Back</a>
                                            </div>
                                        </form>
                                        <div id="image-pro">
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="add_meta">
                                        <form method="post" action="{{route('backend.product.meta')}}">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="id_product" value="{{ $product->id }}">
                                            <div class="form-group">
                                                <label>Meta Title</label>
                                                <input type="text" name="meta_title" class="form-control" value="{{$product->metaproduct->meta_title or ''}}">
                                            </div>
                                            <div class="form-group">
                                                <label>Meta Description</label>
                                                <textarea name="meta_description" class="form-control">{{$product->metaproduct->meta_description or ''}}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Meta Keywords</label>
                                                <input id="tags_2" type="text" class="tags" name="meta_keyword" value="{{$product->metaproduct->meta_keyword or ''}}">
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                                <a href="{{URL::previous()}}" class="btn btn-warning">Back</a>
                                            </div>
                                        </form>
                                    </div><!-- /.tab-pane -->
                                    <div class="tab-pane <?php echo $item;?>" id="add_item">
                                        <div class="row">
                                            <form method="get" id="" action="{{route('backend.product.inputitem')}}" enctype="multipart/form-data">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="_method" value="put">
                                                <input type="hidden" name="id_product" value="{{ $product->id }}">
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label>Item Barcode</label>
                                                        <select class="form-control select2" id="dropdownbarcode" name="barcodeid">
                                                            <option value="0">Pilih Barcode</option>
                                                            @foreach($tampilbarcode as $tampilbarcode)
                                                                <option value="{{$tampilbarcode->barcode_id}}">{{$tampilbarcode->barcode_number}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label>Item Per Roll(m)</label>
                                                        <input type="text" name="item_perroll" id="item_perroll" class="form-control" value="">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label>&nbsp</label><br>
                                                        <button type="submit" class="btn btn-primary" id="submititem">Submit</button>
                                                        <a href="{{URL::previous()}}" class="btn btn-warning">Back</a>
                                                    </div>
                                                </div>
                                            </form>
                                        </div><!-- /row  -->
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <table id="item-table" class="table table-bordered table-responsive table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Item Barcode</th>
                                                            <th>Item Per Roll(m)</th>
                                                            <th>Item Total Meter</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    $n = 1;
                                                    ?>
                                                    @foreach($tampilitem as $tampilitem)
                                                        <tr>
                                                            <td><?php echo $n; ?></td>
                                                            <td><?php echo $tampilitem->barcode_number ; ?></td>
                                                            <td><?php echo $tampilitem->item_perroll ; ?></td>
                                                            <td><?php echo $tampilitem->item_totalmeter ; ?></td>
                                                            <td>
                                                                <?php 
                                                                $iditem = $tampilitem->item_id ;
                                                                $iditemproduct = $tampilitem->item_id_product ; 
                                                                $idbarcode = $tampilitem->barcode_id ;
                                                                ?>
                                                                <form method="post" id="" action="{{route('backend.item.destroy',$iditem)}}" enctype="multipart/form-data">
                                                                    <input type="hidden" name="_method" value="delete">
                                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                                    <input type="hidden" name="iditem" value="{{$iditem}}">
                                                                    <input type="hidden" name="iditemproduct" value="{{$iditemproduct}}">
                                                                    <input type="hidden" name="idbarcode" value="{{$idbarcode}}">
                                                                    <a class="btn btn-info btn-sm" href="{{url('backend/items',$tampilitem->item_id)}}/edit">Edit</a> 
                                                                    <button class="btn btn-info btn-sm" onclick="return confirm('Are you sure to delete this data');">Delete</button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    <?php $n++; ?>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
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