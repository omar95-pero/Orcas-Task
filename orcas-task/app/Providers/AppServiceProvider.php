<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Routing\ResponseFactory;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        ResponseFactory::macro('sendError', function ($message, $data = null, $code = 421) {

            $body["status"] = "error";
            $body["message"] = $message;
            $body["data"] = $data;
            $body["code"] = $code;
            return Response()->json($body, 200);
        });
        ResponseFactory::macro('sendSuccess', function ($message, $data = null, $code = 200) {

            $body["status"] = "success";
            $body["message"] = $message;
            $body["data"] = $data;
            $body["code"] = $code;
            return Response()->json($body, 200);
        });
      Schema::defaultStringLength(191);
    }
}
