<?php

namespace Modules\DiagnosticCentre\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class PathologyReport extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'pathology_billing_id',
        'reporting_date',
        'report',
        'created_by'
    ];
    
    protected static function newFactory()
    {
        return \Modules\DiagnosticCentre\Database\factories\PathologyReportFactory::new();
    }

    public function bill(){
        return $this->belongsTo(PathologyBilling::class, 'pathology_billing_id', 'id');
    }
}
