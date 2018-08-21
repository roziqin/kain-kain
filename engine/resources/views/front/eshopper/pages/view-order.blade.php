@extends('front.eshopper.layouts.index')
@section('slider')
@overwrite
@section('sidebar')
@overwrite
@section('css')
<style>
    .user-details {position: relative; padding: 0;}
    .user-details .user-image {position: relative;  z-index: 1; width: 100%; text-align: center;}
    .user-image img { clear: both; position: relative;}

    .user-details .user-info-block {width: 100%; position: relative; background: rgb(255, 255, 255); z-index: 0; padding-top: 35px;}
    .user-info-block .user-heading {width: 100%; text-align: center; margin: 10px 0 0;}
    .user-info-block .navigation {float: left; width: 100%; margin: 0; padding: 0; list-style: none; border-bottom: 1px solid #f5f5f5; border-top: 1px solid #f5f5f5;}
    .navigation li {float: left; margin: 0; padding: 0;}
    .navigation li a {padding: 20px 30px; float: left;color: black;text-decoration: none}
    .navigation li.active a {color:#fdb45e;}
    .user-info-block .user-body {float: left; width: 100%;}
    .user-body .tab-content > div {float: left; width: 100%;}
    .user-body .tab-content h4 {width: 100%; margin: 10px 0; color: #333;}
</style>
@endsection
@section('js')
<script>
    $(document).ready(function() {
        $.get('{{url("api/ongkir/province")}}', function(e) {
            $('#province').html(e);
        });
        $('#province').on('change', '#prov', function(e) {
            var prov_id = $(this).val();
            $.get('{{url("api/ongkir/kota")}}/' + prov_id, function(e) {
                $('#kota').html(e);
            });
        });
    });
</script>
@endsection
@section('main')
<div class="row">    		
    <div class="col-sm-12">    			   			
        <h2 class="title text-center">Account</h2>    	
        <div class="col-sm-12">
            <div class="col-sm-12 col-md-12 order-details">
                <div class="col-sm-6 col-custom">
                    <h2>Pesanan #{{$tampilorder->order_id}}</h2>
                    <p>Pesanan ditempatkan pada {{$tampilorder->date}} dan saat ini Sedang {{$tampilorder->order_status}}.</p>
                </div>
                <div class="col-sm-6 col-custom">
                    <h3>Detail Pengiriman</h3>
                     <b>Alamat:</b> {{$tampilorder->address}}, {{$tampilorder->name_regencies}}, {{$tampilorder->name_provinces}}
                </div>
                <div class="clear"></div>
                <?php if ($tampilorder->comments!='') {
                    # code...
                ?>
                    <div class="col-sm-6 col-custom">
                        <h3>Catatan Tambahan</h3>
                        {{$tampilorder->comments}}
                    </div>

                <?php
                }
                ?>
                <div class="clear"></div>
                <h3>Detail Pesanan</h3>
                <table class="item">
                    <thead>
                        <tr>
                            <th>Barang</th>
                            <th width="60px">Harga</th>
                            <th width="60px">Jumlah</th>
                            <th width="60px">Ukuran</th>
                            <th width="150px">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                        $subtot = 0;
                        $tot = 0; 
                    ?>
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
                            $subtot+=$j; 
                        ?>
                        <tr>
                            <td>{{$tampilitem->product_name}}</td>
                            <td>{{$harga}}</td>
                            <td>x{{$tampilitem->quantity}}</td>
                            <td><?php echo $ukuran[1]; ?></td>
                            <td>Rp. {{number_format($j,0,',','.')}}</td>
                        </tr>
                    @endforeach
                    <?php 
                        $tot = $subtot + $tampilorder->shipping_fee;
                    ?>
                        <tr>
                            <th colspan="4">Subotal</th>
                            <th><b>Rp. {{number_format($subtot,0,',','.')}}</b></th>
                        </tr>
                        <tr>
                            <th colspan="4">Biaya Pengiriman</th>
                            <th><b>{{$tampilorder->shipping_fee}}</b></th>
                        </tr>
                        <tr>
                            <th colspan="4">Cara Pembayaran</th>
                            <th><b>{{$tampilorder->payment_type}}</b></th>
                        </tr>
                        <tr>
                            <th colspan="4">Total</th>
                            <th><b>Rp. {{number_format($tot,0,',','.')}}</b></th>
                        </tr>
                    </tbody>
                </table>
                <a href="{{url('customer/account')}}" class="simpleCart_checkout btn btn-default check_out pull-right">Kembali</a>
            </div>
        </div>
    </div>			 		
</div> 

@stop

