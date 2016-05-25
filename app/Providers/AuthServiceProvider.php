<?php

namespace App\Providers;

use Log;
use Validator;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        Validator::extend('recaptcha', function($attribute, $value, $parameters, $validator) {

            $recaptcha = new \ReCaptcha\ReCaptcha(env('RECAPTCHA_SECRET', ''));
            $resp = $recaptcha->verify($value);
            if ($resp->isSuccess()) {
                return true;
            } else {
                $errors = $resp->getErrorCodes();
                Log::info("Recaptcha Error: " . json_encode($errors));
                return false;
            }
        });
    }
}
