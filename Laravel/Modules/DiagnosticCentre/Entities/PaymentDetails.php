<?php

namespace Modules\DiagnosticCentre\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentDetails extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'payment_id',
        'pay_via',
        'amount',
        'created_by',
    ];

    
    protected static function newFactory()
    {
        return \Modules\DiagnosticCentre\Database\factories\PaymentDetailsFactory::new();
    }
    
}
