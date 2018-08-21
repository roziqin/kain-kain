@extends('front/eshopper/layouts/index')
@section('main')
<div class="col-sm-12 padding-right">
    @section('slider')
    @if(shopOpt('enable_slideshow') == 1)
    @include('front.eshopper.widget.slider',$slideshow)
    @endif
    @show
    <div class="features_items"><!--features_items-->
        <h2 class="title text-center">Produk Terlaris</h2>
        @foreach($tampilproduct as $pro)
        <div class="col-sm-3">
            <div class="product-image-wrapper">
                <div class="single-products">
                    <a href="{{url('product/'.$pro->slug)}}">
                        <div class="productinfo text-center">
                            @if(count($pro->path_thumb) > 0)
                            <img src="{{asset($pro->path_thumb)}}" alt="" />
                            @endif
                            <h2>Rp. {{number_format($pro->product_price,0,',','.')}}</h2>
                            <p>{{ucwords($pro->product_name)}}</p>
                            <div class="btn btn-default add-to-cart">Detail</div>
                        </div>
                    </a>

                </div>
            </div>
        </div>
        @endforeach
    </div>
    <!--features_items-->
    <div class="features_categories"><!--features_categories-->
        <ul class="row" style="padding-left: 0px; margin: 0px;">
            <?php $a = 1; ?>
            @foreach($categories as $cat)
            <?php 
                if ($a<=3) {
                ?>
                    <li class="col-md-4 text-center">
                        <a href="{{url('product/'.$cat['slug'])}}">
                            <div class="box-content" style="height: 400px ; background-size: cover; background-position: center; background-repeat: no-repeat; background-image: url({{ asset('/images/categories/thumb') }}/{{$cat['image']}});">
                                <div class="overlay"></div>
                                <h2>{{$cat['name']}}</h2>        
                            </div>
                        </a>
                    </li>
                <?php
                }
                $a++;
            ?>
            @endforeach
        </ul>
    </div>
    <!--features_categories-->
    
    <div class="features_items"><!--features_items-->
        <h2 class="title text-center">Produk Terbaru</h2>
        @foreach($tampilproduct1 as $pro)
        <div class="col-sm-3">
            <div class="product-image-wrapper">
                <div class="single-products">
                    <a href="{{url('product/'.$pro->slug)}}">
                        <div class="productinfo text-center">
                            @if(count($pro->path_thumb) > 0)
                            <img src="{{asset($pro->path_thumb)}}" alt="" />
                            @endif
                            <h2>Rp. {{number_format($pro->product_price,0,',','.')}}</h2>
                            <p>{{ucwords($pro->product_name)}}</p>
                            <div class="btn btn-default add-to-cart">Detail</div>
                        </div>
                    </a>

                </div>
            </div>
        </div>
        @endforeach
    </div>
    <?php /*
    <div class="recommended_items">
        <h2 class="title text-center">recommended items</h2>

        <div id="recommended-item-carousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <div class="item active">	
                    <div class="col-sm-4">
                        <div class="product-image-wrapper">
                            <div class="single-products">
                                <div class="productinfo text-center">
                                    <img src="{{asset('front/eshopper/images/home/recommend1.jpg')}}" alt="" />
                                    <h2>$56</h2>
                                    <p>Easy Polo Black Edition</p>
                                    <a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="product-image-wrapper">
                            <div class="single-products">
                                <div class="productinfo text-center">
                                    <img src="{{asset('front/eshopper/images/home/recommend2.jpg')}}" alt="" />
                                    <h2>$56</h2>
                                    <p>Easy Polo Black Edition</p>
                                    <a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="product-image-wrapper">
                            <div class="single-products">
                                <div class="productinfo text-center">
                                    <img src="{{asset('front/eshopper/images/home/recommend3.jpg')}}" alt="" />
                                    <h2>$56</h2>
                                    <p>Easy Polo Black Edition</p>
                                    <a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="item">	
                    <div class="col-sm-4">
                        <div class="product-image-wrapper">
                            <div class="single-products">
                                <div class="productinfo text-center">
                                    <img src="{{asset('front/eshopper/images/home/recommend1.jpg')}}" alt="" />
                                    <h2>$56</h2>
                                    <p>Easy Polo Black Edition</p>
                                    <a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="product-image-wrapper">
                            <div class="single-products">
                                <div class="productinfo text-center">
                                    <img src="{{asset('front/eshopper/images/home/recommend2.jpg')}}" alt="" />
                                    <h2>$56</h2>
                                    <p>Easy Polo Black Edition</p>
                                    <a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="product-image-wrapper">
                            <div class="single-products">
                                <div class="productinfo text-center">
                                    <img src="{{asset('front/eshopper/images/home/recommend3.jpg')}}" alt="" />
                                    <h2>$56</h2>
                                    <p>Easy Polo Black Edition</p>
                                    <a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <a class="left recommended-item-control" href="#recommended-item-carousel" data-slide="prev">
                <i class="fa fa-angle-left"></i>
            </a>
            <a class="right recommended-item-control" href="#recommended-item-carousel" data-slide="next">
                <i class="fa fa-angle-right"></i>
            </a>			
        </div>
    </div>
    */?>
</div>
@stop