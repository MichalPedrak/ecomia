<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductUser extends Model
{
    use HasFactory;
    protected $table = 'product_user';

    protected $fillable = [
        'user_id',
        'product_id',
    ];

}
