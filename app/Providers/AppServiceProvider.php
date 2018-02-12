<?php

namespace App\Providers;

use App\KelolaKas;
use App\Observers\KelolaKasObserver;
use App\Observers\TokoObserver;
use App\Toko;
use Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // baut rule custom(passcheck) untuk chek password lama saat ubah password
        Validator::extend('passcheck', function ($attribute, $value, $parameters) {
            return Hash::check($value, $parameters[0]);
        });
        Schema::defaultStringLength(191);
        Validator::extend('image64', function ($attribute, $value, $parameters, $validator) {
            $type = explode('/', explode(':', substr($value, 0, strpos($value, ';')))[1])[1];

            if (in_array($type, $parameters)) {
                return true;
            }
            return false;
        });

        Validator::replacer('image64', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':values', join(",", $parameters), $message);
        });

        KelolaKas::observe(KelolaKasObserver::class);
        Toko::observe(TokoObserver::class);
        // untuk menyesuaikan waktu berdasarkan tempat
        \Carbon\Carbon::setlocale('id');
        // jika ingin menesuaikan dengan locale di laravel
        // \Carbon\Carbon::setLocale(config('app.locale'));
    }

/**
 * Register any application services.
 *
 * @return void
 */
    public function register()
    {
        //
    }
}
