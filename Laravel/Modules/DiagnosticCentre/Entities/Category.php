<?php

namespace Modules\DiagnosticCentre\Entities;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'type',
        'description',
        'parent_id',
        'created_by',
        'created_by'
    ];
    
    protected static function newFactory()
    {
        return CategoryFactory::new();
    }

    public function parent(){
        return $this->belongsTo(Category::class,'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
