<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryTag extends Model
{
    use SoftDeletes;
    
    protected $table = 'category_tag';
    protected $primaryKey = 'id';

    public static function boot()
    {

        parent::boot();

        static::deleting(function ($obj) {
            $category_tag_id = $obj->id;

            $product_pricing = ProductPricing::where('tag', $category_tag_id)->get();
            if (!empty($product_pricing)) {
                $product_pricing->each(function ($con) {
                    $con->delete();
                });
            }
        });
    }
}
