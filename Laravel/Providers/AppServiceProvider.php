<?php

namespace App\Providers;

use App\Adapters\MaatwebsiteExcelAdapter;
use App\Adapters\SMSProviders\ADNAdapter;
use App\Interfaces\ExcelExportInterface;
use App\Interfaces\SMSInterface;
use App\Services\CurlService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ExcelExportInterface::class, function () {
            return new MaatwebsiteExcelAdapter;
        });
        $this->app->singleton(SMSInterface::class, function () {
            return new ADNAdapter(new CurlService);
        });
    }
}
