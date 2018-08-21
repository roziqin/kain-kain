@extends('front.eshopper.layouts.index')
@section('slider')
@overwrite
@section('sidebar')
@overwrite
@section('css')
<style>
    #map {
        height: 400px;  /* The height is 400 pixels */
        width: 100%;  /* The width is the width of the web page */
    }
</style>
@endsection
@section('main')
<div class="row">    		
    <div class="col-sm-12" style="padding-bottom: 80px;">    			   			
        <h2 class="title text-center">Hubungi Kami</h2>    	
        <div class="col-sm-6">
        	<form role="form" method="POST" action="{{route('postContact')}}" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group">
                    <label for="exampleInputEmail1">Nama</label>
                    <input type="text" class="form-control" id="name" placeholder="Input Nama" name='contact_us_name'>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Email</label>
                    <input type="email" class="form-control" id="email" placeholder="Input Email" name='contact_us_email'>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Subject</label>
                    <input type="text" class="form-control" id="subject" placeholder="Input Subject" name='contact_us_subject'>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Pesan</label>
                    <textarea class="form-control" name='contact_us_message'></textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
        <div class="col-sm-6">
        	<div id="map"></div>
        </div>
    </div>			 		
</div> 
<script>
    // Initialize and add the map
    function initMap() {
        // The location of Uluru
        var uluru = {
            lat: -7.9468374,
            lng: 112.6181262
        };
        // The map, centered at Uluru
        var map = new google.maps.Map(
            document.getElementById('map'), {
                zoom: 15,
                center: uluru
            });
        // The marker, positioned at Uluru
        var marker = new google.maps.Marker({
            position: uluru,
            map: map,
            title: 'Kain Kain'
        });
    }
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDgAXPJhHju4z5ua3uRTq-HybFXLQlnGbg&callback=initMap">
</script>
@stop

