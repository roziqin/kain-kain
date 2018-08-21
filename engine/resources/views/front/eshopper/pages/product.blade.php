@extends('front/eshopper/layouts/index')
@section('slider')
@overwrite
@section('js')
@endsection
@section('main')

<div class="col-sm-12 padding-right">
    <div class="product-details"><!--product-details-->
        <div class="col-sm-5">
            <ul id="etalage">
                @if(count($product->image) > 0)
                @foreach($product->image as $key => $image)
                <li>
                    <a href="#">
                        <img class="etalage_thumb_image" src="{{asset($image->path_full)}}" />
                        <img class="etalage_source_image" src="{{asset($image->path_full)}}" />
                    </a>
                </li>
                @endforeach
                @else
                <li>
                    <a href="#">
                        <img class="etalage_thumb_image" src="http://placehold.it/350x150" />
                        <img class="etalage_source_image" src="http://placehold.it/350x150" />
                    </a>
                </li>
                @endif
            </ul>

        </div>
        <div class="col-sm-7 simpleCart_shelfItem anotherCart_shelfItem">
            <div class="product-information"><!--/product-information-->
                <i class="item_thumb" style="display:none">{{$product->image->count() ? asset($product->image->first()->path_thumb ):'http://placehold.it/100x100'}}</i>
                <i class="item_productid" style="display:none">{{$product->id}}</i>
                <i id="meter" class="" style="display:none">{{$product->product_price}}</i>
                <i id="roll" class="" style="display:none">{{$product->product_price_roll}}</i>
                <!--<img src="{{asset('front/eshopper/images/product-details/new.jpg')}}" class="newarrival" alt="" />-->
                <h2 class="item_name">{{$product->product_name}}</h2>
                <p>SKU: {{$product->product_sku}}</p>
                <p>
                    <span class="price">
                        <span>Rp. {{number_format($product->product_price,0,',','.')}}</span><span class="custom">/m</span>
                    </span>
                    <span class="price">
                        <span>Rp. {{number_format($product->product_price_roll,0,',','.')}}</span><span class="custom">/Roll</span>
                    </span>
                </p>
                <p>Detail: </p>
                <?php
                if ($product->product_cm!=0) {
                    echo "<p>1 Roll: ".$product->product_cm."m";
                }
                

                ?>
                <p>Lebar Kain: {{$product->product_lebar}} m</p>
                <p>Berat Kain: {{$product->product_berat}} kg/m</p>
                <p>Jenis Kain: {{$showcat->name}} </p>
                @if($product->attribute->count())
                {!!setAttr($product->attribute)!!}
                <script type="text/javascript">

                    setTimeout(function(){
                        var ukuran = $(".item_Ukuran option:selected").text();
                        if (ukuran=='Meter') {
                            $('span.displaysatuan').empty();
                            $('span.displaysatuan').append('m');
                            $(".item_quantity").attr('step','0.10');
                            $(".item_quantity").val('1.00');
                            $('#roll').removeClass("item_price");
                            $('#meter').addClass("item_price");

                        } else {
                            $('span.displaysatuan').empty();
                            $('span.displaysatuan').append('Roll');
                            $(".item_quantity").attr('step','1');
                            $(".item_quantity").val('1');
                            $('#meter').removeClass("item_price");
                            $('#roll').addClass("item_price");
                        }
                        $( ".item_Ukuran" ).change(function() {
                            var ukuran = $(".item_Ukuran option:selected").text();
                            if (ukuran=='Meter') {
                                $('span.displaysatuan').empty();
                                $('span.displaysatuan').append('m');
                                $(".item_quantity").attr('step','0.10');
                                $(".item_quantity").val('1.00');
                                $('#roll').removeClass("item_price");
                                $('#meter').addClass("item_price");
                            } else {
                                $('span.displaysatuan').empty();
                                $('span.displaysatuan').append('Roll');
                                $(".item_quantity").attr('step','1');
                                $(".item_quantity").val('1');
                                $('#meter').removeClass("item_price");
                                $('#roll').addClass("item_price");
                            }
                        });

                        
                    }, 1000);
                </script>
                @endif
                @if(shopOpt('display_mode') == 0)
                <br>
                <span>
                    <label>Panjang:</label>
                    <input type="number" class="item_quantity" >
                </span>
                <span class="displaysatuan"></span>
                <button class="item_add btn btn-fefault cart" value="add to cart">Add to Cart </button>
                @endif
<!--                <p><b>Availability:</b> In Stock</p>
            <p><b>Condition:</b> New</p>
            <p><b>Brand:</b> E-SHOPPER</p>-->
                <a href=""><img src="{{asset('front/eshopper/images/product-details/share.png')}}" class="share img-responsive"  alt="" /></a>
            </div><!--/product-information-->
        </div>
    </div><!--/product-details-->

    <div class="category-tab shop-details-tab"><!--category-tab-->
        <div class="col-sm-12">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#reviews" data-toggle="tab">Deskripsi</a></li>
            </ul>
        </div>
        <div class="tab-content">
            <div class="tab-pane fade active in" id="reviews" >
                <div class="col-sm-12">
                    <!--<a href="javascript:;" class="simpleCart_empty">empty cart</a><br /> -->
                    <p>{!!$product->product_description!!}</p>
                </div>
            </div>

        </div>
    </div><!--/category-tab-->

    <div class="recommended_items"><!--recommended_items-->
        <h2 class="title text-center">recommended items</h2>

        <div id="recommended-item-carousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <div class="item active">	
                    @foreach($related_product as $related)
                    <div class="col-sm-3">
                        <div class="product-image-wrapper">
                            <div class="single-products">
                                <a href="{{url('product/'.$related->slug)}}">
                                    <div class="productinfo text-center">
                                        @if(count($related->path_thumb) > 0)
                                        <img src="{{asset($related->path_thumb)}}" alt="" />
                                        @endif
                                         <h2>Rp. {{number_format($related->product_price,0,',','.')}}</h2>
                                        <p>{{ucwords($related->product_name)}}</p>
                                        <div class="btn btn-default add-to-cart">Detail</div>
                                    </div>
                                </a>

                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <a class="left recommended-item-control" href="#recommended-item-carousel" data-slide="prev">
                <i class="fa fa-angle-left"></i>
            </a>
            <a class="right recommended-item-control" href="#recommended-item-carousel" data-slide="next">
                <i class="fa fa-angle-right"></i>
            </a>			
        </div>
    </div><!--/recommended_items-->

</div>
@stop