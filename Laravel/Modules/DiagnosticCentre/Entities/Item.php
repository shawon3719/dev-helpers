<?php

namespace Modules\DiagnosticCentre\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'price',
        'offer_price',
        'exp_date',
        'description',
        'category_id',
        'created_by',
        'updated_by',
    ];
    
    protected static function newFactory()
    {
        return \Modules\DiagnosticCentre\Database\factories\ItemFactory::new();
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }
}
