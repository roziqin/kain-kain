<?php

namespace App\Models\Barcode;

use Illuminate\Database\Eloquent\Model;
	
class Barcode extends Model {

    //
    protected $table = 'product_barcode';
    protected $primaryKey = 'barcode_id';
    protected $fillable = ['barcode_id','barcode_number', 'barcode_status'];

}
