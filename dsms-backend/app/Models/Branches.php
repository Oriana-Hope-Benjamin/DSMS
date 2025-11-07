<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branches extends Model
{
    protected $table = 'branches';
    protected $primaryKey = 'id';
    protected $fillable = [
        'branch_name',
        'address',
        'phone_number',
        'email',
        'status_id'
    ];

}
