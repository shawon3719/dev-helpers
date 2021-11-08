<?php
namespace App\Scopes;

use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Session;

class SiteScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {

        $today = new Carbon('Asia/Dhaka');

        if(Session::has('source_site')){

            $default_site_id = Session::get('source_site')->id;

        }else if($today->dayOfWeek == Carbon::SUNDAY || $today->dayOfWeek == Carbon::TUESDAY || $today->dayOfWeek == Carbon::THURSDAY){
            
            $setting = Setting::where('site', 'codecanyon.net')->first();
            $default_site_id = !empty($setting) ? $setting->id : 1; 

        }
        else{

            $default_site_id = 1;
        }

        $findOn = $model->getTable().'.source_site';
        $builder->where($findOn, $default_site_id);
    }
}