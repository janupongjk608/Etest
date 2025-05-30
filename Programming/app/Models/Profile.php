<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use SoftDeletes;

    protected $table = 'profiles';

    protected $fillable = [
        'title',
        'name',
        'last_name',
        'birth_date',
        'path_profile',
    ];

    protected $dates = [
        'deleted_at',
    ];
}