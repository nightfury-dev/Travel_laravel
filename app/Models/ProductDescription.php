<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductDescription extends Model
{
    use SoftDeletes;
    
    protected $table = 'product_description';
    protected $primaryKey = 'id';

    protected $fillable = ['id', 'product_id', 'language', 'description'];

    function getLang() {
        return $this->hasOne('App\Models\Language','title','language');
    }
}
