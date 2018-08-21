<label for="exampleInputEmail1">Kota</label>
<select class='form-group' name='city'>
    <option value=''>Pilih Kota</option>
    @foreach($city as $kota)
    <?php
    	if (Auth::user()->city==$kota['city_id']) {
    		# code...
    		$a = 'selected';
    	} else {
    		# code...
    		$a = '';		
    	}
    ?>
    <option value="{{$kota['city_id']}}" <?php echo $a; ?> >{{$kota['city_name']}}</option>
    @endforeach
</select>

