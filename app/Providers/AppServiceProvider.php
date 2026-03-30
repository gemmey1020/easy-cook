<?php

namespace App\Providers;

use App\Models\VisitorLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Laravel\Scout\EngineManager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->environment('testing')) {
            app(EngineManager::class)->extend('algolia', function () {
                return new \Laravel\Scout\Engines\NullEngine;
            });
        }

        if (! app()->runningInConsole() && ! Session::has('visitor_info')) {
            try {
                $ip = Request::ip();

                $response = Http::timeout(10)->get('https://free.freeipapi.com/api/json/');
                $data = $response->json();
                // dd($data);

                Session::put('visitor_info', $data);

                // VisitorLog::create([
                //     'ip' => $ip,
                //     'location' => $data['city'] . ', ' . $data['country_name'],
                //     'page' => Request::fullUrl(),
                //     'user_agent' => Request::userAgent(),
                // ]);
            } catch (\Exception $e) {
                // optional: log or silently ignore
            }
        }
    }
}
