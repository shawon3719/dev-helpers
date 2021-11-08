## This is a Global Scode
## Should be called from model that will work on every query automatically

```
 <?php

namespace App\Models;

use App\Scopes\SiteScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name', 
    'path', 
    'data',
    'unique_items_count',
    'last_7_days_sale',
    'last_30_days_sale',
    'created_by',
    'source_site', 
    'updated_by'
    ];

    public function items(){
        return $this->hasMany(Item::class, 'classification', 'path');
    }

    public function unique_items(){
        return $this->hasMany(Item::class, 'classification', 'path')->distinct('item_id');
    }

    public function preferredCategories(){
        return $this->hasMany(PreferredCategories::class);
    }

    public function setting(){
        return $this->hasOne(Setting::class, 'id', 'source_site');
    }

    protected static function booted()
    {
        static::addGlobalScope(new SiteScope);
    }
}
```
