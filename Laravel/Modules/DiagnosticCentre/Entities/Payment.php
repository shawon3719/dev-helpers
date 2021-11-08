<?php

namespace Modules\DiagnosticCentre\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'payment_reference',
        'payment_reference_id',
        'net_payable',
        'received_amount',
        'due_amount',
        'created_by',
    ];
    
    protected static function newFactory()
    {
        return \Modules\DiagnosticCentre\Database\factories\PaymentFactory::new();
    }

    public function details(){
        return $this->hasMany(PaymentDetails::class);
    }

    public function bill(){
        return $this->belongsTo(PathologyBilling::class, 'payment_reference_id', 'id');
    }

    public function collect_by(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
