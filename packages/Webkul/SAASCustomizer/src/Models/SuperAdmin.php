<?php

namespace Webkul\SAASCustomizer\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SuperAdmin extends Authenticatable
{
    protected $table = 'super_admins';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'first_name',
      'last_name',
      'email',
      'password',
      'status',
      'misc',
      'api_token',
      'role_id',
      'remember_token',
    ];
}
