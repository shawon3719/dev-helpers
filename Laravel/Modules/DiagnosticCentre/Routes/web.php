<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Services\SettingsService;

Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('diagnostic-centre.home')->with('status', session('status'));
    }

    return redirect()->route('diagnostic-centre.home');
});

// Patient Report
Route::get("my-reports", "GuestReportController@index")->name("my-reports.index");
Route::post("my-reports/login", "GuestReportController@login")->name("my-reports.login");
Route::get("my-reports/list", "GuestReportController@show")->name("my-reports.show");
Route::get("my-reports/view/{id}", "GuestReportController@view")->name("my-reports.view");
Route::get('my-reports/generate-pdf/{id}', 'GuestReportController@generatePDF')->name('my-reports.pdf');
Route::get('my-reports/print/{id}', 'GuestReportController@print')->name('my-reports.print');
Route::get('logout', 'GuestReportController@logout')->name('my-reports.logout');

View::composer('*', function($view)
{
    SettingsService::reloadSettingsCache();
    $data['settings'] = SettingsService::all();
    $view->with($data);
});

Route::group(['prefix' => 'diagnostic-centre', 'as' => 'diagnostic-centre.', 'middleware' => ['auth']], function () {
    Route::get('/', 'DiagnosticCentreController@index')->name('home');

    // Categories
    Route::delete('categories/destroy', 'CategoriesController@massDestroy')->name('categories.massDestroy');
    Route::resource('categories', 'CategoriesController');
    Route::get("import-categories", "CategoriesController@import")->name("categories.import");
    Route::post("import-categories", "CategoriesController@storeImport")->name("categories.import-store");

    // Items
    Route::delete('items/destroy', 'ItemsController@massDestroy')->name('items.massDestroy');
    Route::resource('items', 'ItemsController');
    Route::get("import-items", "ItemsController@import")->name("items.import");
    Route::post("import-items", "ItemsController@storeImport")->name("items.import-store");


    // Report Templates
    Route::delete('report-templates/destroy', 'ReportTemplatesController@massDestroy')->name('report-templates.massDestroy');
    Route::resource('report-templates', 'ReportTemplatesController');

    // Pathology reports
    Route::delete('pathology-reports/destroy', 'PathologyReportsController@massDestroy')->name('pathology-reports.massDestroy');
    Route::resource('pathology-reports', 'PathologyReportsController');
    Route::get('pathology-reports/create/report/{id}', 'PathologyReportsController@createReport')->name('pathology-reports.create.report');
    Route::get('pathology-reports/generate-pdf/{id}', 'PathologyReportsController@generatePDF')->name('pathology-reports.pdf');
    Route::get('pathology-reports/print/{id}', 'PathologyReportsController@print')->name('pathology-reports.print');
    

    // Pathology Billing
    Route::delete('pathology-bills/destroy', 'PathologyBillingController@massDestroy')->name('pathology-bills.massDestroy');
    Route::resource('pathology-bills', 'PathologyBillingController');
    Route::get('pathology-bills/invoice/{bill_id}', 'PathologyBillingController@invoice')->name('pathology-bills.invoice');
    Route::get('pathology-bills/generate-pdf/{bill_id}', 'PathologyBillingController@generatePDF')->name('pathology-bills.pdf');
    Route::get('pathology-bills/print/{bill_id}', 'PathologyBillingController@print')->name('pathology-bills.print');
    Route::get("search", "PathologyBillingController@search")->name('pathology-bills.search');

    // Due Reports
    Route::get('pathology-due-reports', 'ReportsController@pathologyDue')->name('reports.pathology-due');
    Route::get('pathology-paid-reports', 'ReportsController@pathologyPaid')->name('reports.pathology-paid');
});
