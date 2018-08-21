<label for="exampleInputEmail1">Provinsi</label>
<select class='form-group' name='province' id="prov">
    <option value=''>Pilih Provinsi</option>
    @foreach($province as $prov)
    <?php 
    	if (Auth::user()->province==$prov['province_id']) {
    		# code...
    		$a = 'selected';
    	} else {
    		# code...
    		$a = '';		
    	}
    	
    ?>
    <option value="{{$prov['province_id']}}" <?php echo $a; ?> >{{$prov['province']}}</option>
    @endforeach
</select>

