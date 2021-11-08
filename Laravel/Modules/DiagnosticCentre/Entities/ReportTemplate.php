<?php

namespace Modules\DiagnosticCentre\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportTemplate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'template',
        'created_by',
        'updated_by',
    ];
    
    
    protected static function newFactory()
    {
        return \Modules\DiagnosticCentre\Database\factories\ReportTemplateFactory::new();
    }
}
