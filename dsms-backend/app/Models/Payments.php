<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    protected $fillable = [
        'enrollment_id',
        'amount_paid',
        'payment_method_id',
        'transaction_reference',
        'payment_date',
    ];
}
