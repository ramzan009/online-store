<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class NetWork extends Model
{
    protected $table = 'user_network';
    protected $fillable = ['network', 'identity'];

    public $timestamps = false;
}
