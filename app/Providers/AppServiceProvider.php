<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use App\Models\clients\User;
use Illuminate\Support\Facades\DB;


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
    public function boot()
{
    /* View::composer('*', function ($view) {
        if (Session::has('userId')) {
            $user = User::where('userId', Session::get('userId'))->first();
            $view->with('user', $user);
        } else {
            $view->with('user', null);
        }

        if (Session::has('userId')) {
            $user = DB::table('tbl_users')->where('userId', Session::get('userId'))->first();
            $view->with('user', $user);
        } else {
            $view->with('user', null);
        }
    }); */
}
}
