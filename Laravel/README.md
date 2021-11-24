## Storage Put

```
if ($request->hasFile('system_logo')) {
        $image      = $request->file('system_logo');
        $fileName   = 'settings_file/'.time() . '.' . $image->getClientOriginalExtension();

        Storage::disk('public')->put($fileName,File::get($image));

        $system_logo = $fileName;

    }
```

## Storage Get

```
{{ Storage::url($pathology_report->report) }}
```

## View Share to all

```
View::composer('*', function($view)
{
    SettingsService::reloadSettingsCache();
    $data['settings'] = SettingsService::all();
    $view->with($data);
});
```

## View Share to specific

```
View::composer('admin.partials.header', function($view)
{
    SettingsService::reloadSettingsCache();
    $data['settings'] = SettingsService::all();
    $view->with($data);
});


What is difference between?

  View::composer('layouts.sidebar',function ($view){
            $view->with('newestPosts',
                Post::latest()->take(5)->get()
            );
}
and:

View::share('newestPosts',Post::latest()->take(5)->get());
```

## Get data after / in laravel
```
Str::of($auditModel)->afterLast('\\')
```

## Detect Device OS or Browser

https://github.com/jenssegers/agent
