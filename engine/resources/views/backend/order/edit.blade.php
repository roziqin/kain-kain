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
        <div class="col-xs-9">
            <div class="box order"  style="display: inline-block; width: 100%">
                <div class="box-header">
                    <h3 class="box-title">Order #{{$tampiledit->order_id}} details</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="col-xs-4">
                        <h5><b>General Details</b></h5><br>
                        <p><b>Order Tanggal:</b> {{$tampiledit->created_at}}</p>
                        <p><b>Order Status:</b> {{$tampiledit->order_status}}</p>
                        <p><b>Customer:</b> {{$tampiledit->name}}</p>

                    </div>
                    <div class="col-xs-4">
                        <h5><b>Billing Details</b></h5><br>
                        <p><b>Alamat:</b> {{$tampiledit->address}}, {{$tampiledit->name_regencies}}, {{$tampiledit->name_provinces}}</p>
                        <p><b>Email:</b> {{$tampiledit->email}}</p>
                        <p><b>Telepon:</b> {{$tampiledit->mob_phone}}</p>
                    </div>
                    <div class="col-xs-4">
                        <h5><b>Shipping Details</b></h5><br>
                        <p><b>Alamat:</b> {{$tampiledit->address}}, {{$tampiledit->name_regencies}}, {{$tampiledit->name_provinces}}</p>
                    </div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
            <table class="item">
                <tr>
                    <th>Barang</th>
                    <th width="60px">Harga</th>
                    <th width="60px">Jumlah</th>
                    <th width="60px">Ukuran</th>
                    <th width="60px">Total</th>
                </tr>
                <?php $tot = 0; ?>
                @foreach($tampilitem as $tampilitem)
                    <?php 

                        $ukuran=explode(":",$tampilitem->ukuran);
                        if ($ukuran[1]=='Roll') {
                             # code...
                            $harga = $tampilitem->product_price_roll;
                        } else {
                             # code...
                            $harga = $tampilitem->product_price;
                        }

                        $j = $harga*$tampilitem->quantity;
                        $tot+=$j;
                          
                    ?>
                    <tr>
                        <td>{{$tampilitem->product_name}}</td>
                        <td>{{$harga}}</td>
                        <td>x{{$tampilitem->quantity}}</td>
                        <td><?php echo $ukuran[1]; ?></td>
                        <td>{{$j}}</td>
                    </tr>
                @endforeach
                <tr>
                    <th colspan="3"></th>
                    <th>Total</th>
                    <th><b>{{$tot}}</b></th>
                </tr>
            </table>
        </div><!-- /.col -->
        <div class="col-xs-3">
            <?php
                $a = '';
                $b = '';
                $c = '';
                $d = '';
                $status = $tampilitem->order_status;
                if ($status=='Pending') {
                    # code...
                    $a = 'selected';
                } elseif ($status=='Proses Potong') {
                    # code...
                    $b = 'selected';
                } elseif ($status=='Proses Kirim') {
                    # code...
                    $c = 'selected';
                }  elseif ($status=='Selesai') {
                    # code...
                    $d = 'selected';
                }  else {
                    # code...
                }
                
            ?>
            <form method="post" id="" action="{{route('backend.order.update',$idorder)}}" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="put">
                <div class="box order"  style="display: inline-block; width: 100%">
                    <div class="box-header">
                        <h3 class="box-title">Order actions</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body" style="display: inline-block; width: 100%">
                        <div class="form-group">
                            <select id="form-field-select-3" class="form-control search-select" name="status">
                                <option value="">Pilih Status</option>
                                <option value="Pending" <?php echo $a; ?> >Pending</option>
                                <option value="Proses Potong" <?php echo $b; ?> >Proses Potong</option>
                                <option value="Proses Kirim" <?php echo $c; ?> >Proses Kirim</option>
                                <option value="Selesai" <?php echo $d; ?> >Selesai</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary pull-right">Update</button>
                        </div>  
                    </div>
                </div>
                <div class="box order"  style="display: inline-block; width: 100%">
                    <div class="box-header">
                        <h3 class="box-title">Order Notes</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="form-group">
                            <textarea name="note" class="form-control" disabled="true">{{$tampilitem->comments}}</textarea>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div><!-- /.row -->
</section><!-- /.content -->
@stop