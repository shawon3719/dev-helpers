<?php

namespace Modules\DiagnosticCentre\Entities;

use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class PathologyBilling extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'code',
        'patient_id',
        'referrer_id',
        'bill_date',
        'delivery_date', 
        'delivery_time', 
        'remarks',
        'sub_total',
        'tax', 
        'discount',
        'total',
        'created_by'
    ];
    
    protected static function newFactory()
    {
        return \Modules\DiagnosticCentre\Database\factories\PathologyBillingFactory::new();
    }

    public function patient(){
        return $this->belongsTo(Patient::class);
    }

    public function details(){
        return $this->hasMany(PathologyBillingDetails::class);
    }

    public function referrer(){
        return $this->belongsTo(Doctor::class);
    }

    public function payment(){
        return $this->belongsTo(Payment::class,'id','payment_reference_id');
    }
}
