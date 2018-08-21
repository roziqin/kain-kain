@extends('front/eshopper/layouts/index')
@section('main')
<div class="col-sm-3">
    <div class="left-sidebar">
        <h2>Category</h2>
        <div class="panel-group category-products" id="accordian"><!--category-productsr-->
            @foreach($categories as $cat)
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        @if(count($cat['children']) > 0)
                        <a data-toggle="collapse" data-parent="#accordian" href="#{{$cat['slug']}}">

                            <span class="badge pull-right"><i class="fa fa-plus"></i></span>
                            {{$cat['name']}} 
                            @if(shopOpt('category_product_count') == 1)
                            <span class="label label-success pull-right">{{count($cat['children'])}}</span>
                            @endif
                        </a>
                        @else
                        <h4 class="panel-title">
                            <a href="{{url('product/'.$cat['slug'])}}">
                                
                                <!--<img src="{{ asset('/images/categories/thumb') }}/{{$cat['image']}}" alt="" />-->
                             
                                {{$cat['name']}}
                            </a>
                        </h4>
                        @endif
                    </h4>
                </div>
                @if(count($cat['children']) > 0)
                <div id="{{$cat['slug']}}" class="panel-collapse collapse">
                    <div class="panel-body">
                        <ul>
                            @foreach($cat['children'] as $child)
                            <li><a href="{{url('product/'.$child['slug'])}}">{{$child['name']}} </a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif
            </div>
            @endforeach
        </div>
        <!--/category-products-->
    </div>
</div>

<div class="col-sm-9 padding-right">
    <div class="features_items"><!--features_items-->
        <h2 class="title text-center custom">&nbsp;</h2>
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
        <div class="box-pagination">          
            <?php echo $tampilproduct->render(); ?>
        </div>
    </div>
</div>
@stop