<?php

namespace App\Providers;

use App\Services\Sms\ArraySender;
use App\Services\Sms\SmsRu;
use App\Services\Sms\SmsSender;
use http\Exception\InvalidArgumentException;
use Illuminate\Support\ServiceProvider;
use PHPUnit\TextUI\Application;

class SmsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SmsSender::class, function (Application $app) {
            $config = $app->make('config')->get('sms');

            switch ($config['driver']) {
                case 'sms.ru':
                    if (!empty($config['url'])) {
                        return new SmsRu($config['app_id'], $config['url']);
                    }
                    return new SmsRu($config['app_id']);
                case 'array':
                    return new ArraySender();
                default:
                    throw new InvalidArgumentException('Undefined SMS driver ' . $config['driver']);
            }

        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
