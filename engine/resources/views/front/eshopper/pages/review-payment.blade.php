@extends('front/eshopper/layouts/index')
@section('slider')
@overwrite
@section('sidebar')
@overwrite
@section('js')
<script>
    $(document).ready(function() {
        $("#pay").click(function(e) {
            //var url = $(this).attr('href');
            $.post('{{url("checkout/postorder")}}', {'_token': "{{csrf_token()}}"}, function(o) {
                if(o.success){
                    window.location.replace(url);
                }
            });
            e.preventDefault();
        });
    });
</script>
@endsection
@section('main')
<section id="cart_items">
    <div class="container">
        <div class="breadcrumbs">
            <ol class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li class="active">Check out</li>
            </ol>
        </div><!--/breadcrums-->
    </div><!--/checkout-options-->
    <div class="well col-xs-12 col-sm-12 col-md-12">
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6">
                <h3>Shipping Address</h3>
                <address>
                    Nama: {{$order['user']['name']}}
                    <br>
                    Alamat: {{$order['user']['address']}}
                    <br>
                    @foreach($tampilcity as $tampilcity)
                    {{$tampilcity->name_regencies}}, {{$tampilcity->name_provinces}}
                    @endforeach
                    <br>
                    <abbr title="Phone">Telp:</abbr> {{$order['user']['phone']}} / {{$order['user']['mob_phone']}}
                    <br>
                    Catatan: {{$order['user']['comments']}}
                </address>
            </div>
        </div>
        <div class="row">
            <div class="text-center">
                <h1>Receipt</h1>
            </div>
            </span>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Option</th>
                        <th class="text-right">Price</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order['product'] as $product)
                    <tr>
                        <td class="col-md-8">
                            <em>{{$product['product_name']}}</em></h4>
                        </td>
                        <td class="" style="text-align: center"> {{$product['product_qty']}} </td>
                        <td class=" ">
                            <p>{{$product['product_options']}}</p></td>
                        <td class=" text-right">Rp. {{$product['product_price']}}</td>
                        <td class=" text-right">Rp. {{$product['product_tprice']}}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="3"></td>
                        <td class="text-right">
                            <p>
                                <strong>Subtotal: </strong>
                            </p>
                            <p>
                                <strong>Shipping Fee: </strong>
                            </p></td>
                        <td class="text-right">
                            <p>
                                <strong>Rp. {{$order['sub_total']}}</strong>
                            </p>
                            <p>
                                <strong>Rp. {{$order['shipping']['fee']}}</strong>
                            </p></td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                        <td class="text-right"><h4><strong>Total: </strong></h4></td>
                        <td class="text-right text-danger"><h4><strong>Rp. {{$order['total']}}</strong></h4></td>
                    </tr>
                </tbody>
            </table>
            <form class="form-login" method="post" action="{{route('postorder')}}" novalidate>
                <input type="hidden" name="_token" value="{{ csrf_token()}}">
                <button type="submit" class="btn btn-success btn-lg btn-block">Pay Now <span class="glyphicon glyphicon-chevron-right"></button>
            </form>
        </div>
    </div>
</section> <!--/#cart_items-->
@stop