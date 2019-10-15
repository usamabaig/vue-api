<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAdAccount extends Model
{
    protected $table = 'user_ad_account';
    protected $fillable = ['ad_account_name' , 'ad_account_id' , 'ad_account_custom_name' , 'is_show' , 'user_id'];
}
