<?php

namespace App\Models\Adverts\Advert;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $table = 'advert_advert_values';

    public $timestamps = false;
    protected $fillable = ['file'];


}
