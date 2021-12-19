<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountType extends Model
{
    use SoftDeletes;

    protected $table = 'account_type';
    protected $primaryKey = 'id';

    public static function boot()
    {

        parent::boot();

        static::deleting(function ($obj) {
            $type_id = $obj->id;

            $user = User::where('account_type', $type_id)->get();
            if (!empty($user)) {
                $user->each(function ($acc) {
                    $acc->delete();
                });
            }
        });
    }
}
