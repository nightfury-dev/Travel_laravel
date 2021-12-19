<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';
    protected $primaryKey = 'id';

    public static function boot()
    {

        parent::boot();

        static::deleting(function ($obj) {
            $category_id = $obj->id;

            $product = Product::where('category', $category_id)->get();
            if (!empty($product)) {
                $product->each(function ($pro) {
                    $pro->delete();
                });
            }
        });
    }
}
