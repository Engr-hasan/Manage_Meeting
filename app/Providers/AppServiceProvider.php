<?php

namespace App\Providers;

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
        $this->app['validator']->extend('bd_mobile', function ($attribute, $value, $parameters) {
            $first_two_digit = substr($value, 0, 2);
            $first_four_digit = substr($value, 0, 4);
            $first_five_digit = substr($value, 0, 5);
            $first_six_digit = substr($value, 0, 6);

            $length = strlen($value);
            if ($length == 11) {
                if (is_numeric($value) && $first_two_digit == '01') {
                    return true;
                }
            } elseif ($length == 13) {
                if (is_numeric($value) && $first_four_digit == '8801') {
                    return true;
                }
            } elseif ($length == 14) {
                if ($first_five_digit == '+8801') {
                    return true;
                }
            } elseif ($length == 15) {
                if (is_numeric($value) && $first_six_digit == '008801') {
                    return true;
                }
            }
            return false;
        });
        $this->app['validator']->extend('bd_phone', function ($attribute, $value, $parameters) {

//            preg_match_all('/^[+]{0,1}(88-)?[0-9]{4,12}$/', $value,$matches,PREG_SET_ORDER);
//            dd($matches);
            if (!preg_match('/^[+]{0,1}(88-)?[0-9]{4,12}$/', $value)) {
                return false;
            }
            return true;

        });

        $this->app['validator']->extend('image300x80',function($attribute,$value,$parameters){
            $image = getimagesize($value);
            $width = $image[0];
            $height = $image[1];
            if($height == 80 && $width == 300){
                return true;
            }
            return false;
        });
        $this->app['validator']->extend('image300x300',function($attribute,$value,$parameters){
            $image = getimagesize($value);
            $width = $image[0];
            $height = $image[1];
            if($height == 300 && $width == 300){
                return true;
            }
            return false;
        });
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
