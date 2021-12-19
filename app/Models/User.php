<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable,HasApiTokens, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', "first_name", "last_name", "category", "account_type", "avatar_path", "main_country", "main_city", "main_region_state", "main_postal_code", "main_street_number", "main_street_address", "main_office_phone", "main_email", "billing_country", "billing_city", "billing_region_state", "billing_postal_code", "billing_company_name", "billing_street_number", "billing_street_address", "billing_office_phone", "billing_email"
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function get_account_type() {
        return $this->hasOne('App\Models\AccountType', 'id', 'account_type');
    }
}
