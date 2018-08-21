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
        /*
        $.get('{{url("api/ongkir/province")}}', function(e) {
            $('#province').html(e);
        });
        $('#province').on('change', '#prov', function(e) {
            var prov_id = $(this).val();
            $.get('{{url("api/ongkir/kota")}}/' + prov_id, function(e) {
                $('#kota').html(e);
            });
        });
        */
        $('#prov').change(function(e) {
            var prov_id = $(this).find('option:selected').val();
            $.get('{{url("customer/getkota")}}/' + prov_id, function(ev) {
                $('#city').html(ev);
            });
        });
        setTimeout(function(){ 
            if ($('#prov').val()!='') {
                var prov_id = $('#prov').find('option:selected').val();
                $.get('{{url("customer/getkota")}}/' + prov_id, function(ev) {
                    $('#city').html(ev);
                }); 
            } 
        }, 3000);
        
    });
</script>
@endsection
@section('main')
<div class="row">    		
    <div class="col-sm-12">    			   			
        <h2 class="title text-center">Akun</h2>    	
        <div class="col-sm-12">
            <div class="col-sm-12 col-md-12 user-details">
                <div class="user-image">
                    <img src="{{Auth::user()->avatar}}" alt="{{Auth::user()->first_name}}" title="{{Auth::user()->last_name}}" class="img-circle">
                </div>
                <div class="user-info-block">
                    <div class="user-heading">
                        <h3>{{Auth::user()->first_name.' '.Auth::user()->last_name}}</h3>
                    </div>
                    <ul class="navigation">
                        <li class="active">
                            <a data-toggle="tab" href="#information">
                                <span class="glyphicon glyphicon-user"></span> Profil
                            </a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#orders">
                                <span class="glyphicon glyphicon-envelope"></span> Pesanan
                            </a>
                        </li>
                    </ul>
                    <div class="user-body">
                        <div class="tab-content">
                            <div id="information" class="tab-pane active">
                                <h4>Informasi Akun</h4>
                                <form role="form" method="POST" action="{{route('updateaccount')}}" enctype="multipart/form-data">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="id" value="{{Auth::user()->id}}">
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
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Nama</label>
                                            <input type="text" value="{{Auth::user()->name}}" class="form-control" id="name" placeholder="Input Nama" name='name'>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Email</label>
                                            <input type="email" value="{{Auth::user()->email}}" class="form-control" id="email" placeholder="Input Email" name='email'>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Password</label>
                                            <input type="password" class="form-control" id="password" placeholder="Input Password" name='password'>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">No. Telp</label>
                                            <input type="text" value="{{Auth::user()->phone}}" class="form-control" id="phone" placeholder="Input Telp" name='phone'>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Alamat</label>
                                            <textarea class="form-control" name='address'>{{Auth::user()->address}}</textarea>
                                        </div>
                                        <div class="form-group" id='province'>
                                            <label for="exampleInputEmail1">Provinsi</label>
                                            <select class='form-group' name='province' id="prov">
                                                <option value=''>Pilih Provinsi</option>
                                                @foreach($tampilprovinsi as $tampilprovinsi)
                                                <?php
                                                if ($tampilprovinsi->id_provinces==Auth::user()->province) {
                                                    # code...
                                                    $ket="selected";
                                                } else {
                                                    # code...
                                                    $ket="";
                                                }
                                                ?>
                                                <option value="{{$tampilprovinsi->id_provinces}}" <?php echo $ket; ?> >{{$tampilprovinsi->name_provinces}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group" id='kota'>
                                            <label for="exampleInputEmail1">Kota</label>
                                            <select class='form-group' name='city' id="city"></select>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Kode Pos</label>
                                            <input type="text" value="{{Auth::user()->postal_code}}" class="form-control" id="postal_code" placeholder="Input Kode Pos" name='postal_code'>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div id="orders" class="tab-pane">
                                <h4>History Orders</h4>
                                <table class="table table-bordered table-striped table-responsive">
                                    <thead>
                                        <tr>
                                            <th>Order</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Total</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tampilorder as $tampilorder)
                                            <tr>
                                                <td>#{{$tampilorder->order_id}}</td>
                                                <td>{{$tampilorder->date}}</td>
                                                <td>{{$tampilorder->order_status}}</td>
                                                <td>Rp. {{number_format($tampilorder->total_price,0,',','.')}}</td>
                                                <td>
                                                    <a class="" href="{{url('customer/view-order',$tampilorder->id)}}">view</a> 
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>			 		
</div> 

@stop

