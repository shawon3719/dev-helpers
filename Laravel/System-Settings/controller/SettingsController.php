<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSettingsRequest;
use App\Services\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class SettingsController extends Controller
{
    protected $settingsService;

    public function __construct()
    {
        $this->settingsService = new SettingsService(); 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->settingsService->reloadSettingsCache();
        $data['settings'] = $this->settingsService->all();
        return view('admin.settings.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.settings.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSettingsRequest $request)
    {

    $unexpected = [
        "_token"
    ];

    $system_logo = isset($settings['system_logo']) ? $settings['system_logo'] : "";
    $favicon = isset($settings['favicon']) ? $settings['favicon'] : "";

    if ($request->hasFile('system_logo')) {
        $image      = $request->file('system_logo');
        $fileName   = 'settings_file/'.time() . '.' . $image->getClientOriginalExtension();

        Storage::disk('public')->put($fileName,File::get($image));

        $system_logo = $fileName;

    }
    
    if($request->hasFile('favicon')){

        if ($request->hasFile('favicon')) {
            $image      = $request->file('favicon');
            $fileName   = 'favicon/'.time() . '.' . $image->getClientOriginalExtension();
    
            Storage::disk('public')->put($fileName,File::get($image));
    
            $favicon = $fileName;
        }
    }

    foreach ($request->all() as $key => $value) {
        
        if(in_array($key, $unexpected) === false){
            $this->settingsService->updateOrCreate($key, $value, $system_logo,$favicon);
        }

    }

    // To get new updated/created data into settings cache, we need to reload cache settings
    $this->settingsService->reloadSettingsCache();

    session()->flash("success", "Settings has been updated successfully.");

    return redirect()->back();

    }

}
