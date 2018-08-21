<?php

namespace App\Models\Page;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model {

    //
    protected $table = 'contact_us';
    protected $fillable = ['contact_us_name','contact_us_email','contact_us_subject','contact_us_message'];


}
