<?php

namespace App\Providers;

use App\Models\Area;
use App\Models\Bank;
use App\Models\Country;
use App\Models\Distributor;
use App\Models\Region;
use App\Models\Territory;
use App\Models\Zone;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Form;

class MacroServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Form::macro('selectLast12Months', function ($name, $selected = null, $options = []) {

            $monthList = [];

            $monthList[''] = "-- Select Month --";

            $i = 0;
            while($i < 12){
                $month = Carbon::now()->subMonth($i);
                $monthList[$month->format('m-Y')] = $month->format('F-Y');
                $i++;
            }

            return Form::select($name, $monthList, $selected, $options);
        });

        Form::macro('selectWeekends', function ($name, $selected = null, $options = []) {

            $dowMap = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');

            return Form::select($name, $dowMap, $selected, $options);
        });

        Form::macro('selectDistributors', function ($name, $selected = null, $options = []) {

            $distributors = Distributor::orderBy('name_of_firm','asc')->pluck('name_of_firm','id')->toArray();

            return Form::select($name, $distributors, $selected, $options);
        });

        Form::macro('selectCountry', function ($name, $selected = null, $options = []) {

            $countries = Country::whereCountryCode('BD')->pluck('name','id');

            return Form::select($name, $countries, $selected, $options);
        });

        Form::macro('selectZone', function ($name, $selected = null, $options = []) {

            $zone = Zone::pluck('name','id');

            return Form::select($name, $zone, $selected, $options);
        });

        Form::macro('selectRegion', function ($name, $selected = null, $options = []) {

            $region = Region::pluck('name','id');

            return Form::select($name, $region, $selected, $options);
        });

        Form::macro('selectArea', function ($name, $selected = null, $options = []) {

            $area = Area::pluck('name','id');

            return Form::select($name, $area, $selected, $options);
        });

        Form::macro('selectTerritory', function ($name, $selected = null, $options = []) {

            $territory = Territory::pluck('name','id');

            return Form::select($name, $territory, $selected, $options);
        });

        Form::macro('selectBank', function ($name, $selected = null, $options = []) {

            $bank = Bank::pluck('bank_name','id');

            return Form::select($name, $bank, $selected, $options);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
