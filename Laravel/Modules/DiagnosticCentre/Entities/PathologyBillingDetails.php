<?php

namespace Modules\DiagnosticCentre\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class PathologyBillingDetails extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'pathology_billing_id',
        'item_id', 
        'category_id',
        'price',
        'quantity',
        'discount_percentage',
        'discount_amount',
        'total', 
        'created_by',
    ];
    
    protected static function newFactory()
    {
        return \Modules\DiagnosticCentre\Database\factories\PathologyBillingDetailsFactory::new();
    }

    public function item(){
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }
}
