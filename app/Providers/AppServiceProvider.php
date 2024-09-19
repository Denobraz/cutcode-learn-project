<?php

namespace App\Providers;

use Carbon\CarbonInterval;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\Kernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Faker;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton((Faker\Generator::class), function () {
            $faker = Faker\Factory::create();
            $faker->addProvider(new \Tests\Faker\StorageFileProvider($faker));
            return $faker;
        });
    }

    public function boot(): void
    {
        RateLimiter::for('auth', function ($request) {
            return Limit::perMinute(20)->by($request->ip());
        });

        Model::shouldBeStrict(!app()->isProduction());

        if (app()->isProduction()) {
            DB::listen(function ($query) {
                if ($query->time > 100) {
                    logger()
                        ->channel('telegram')
                        ->debug('query longer than 1s: ' . $query->sql, $query->bindings);
                }
            });

            app(Kernel::class)->whenRequestLifecycleIsLongerThan(
                CarbonInterval::seconds(4),
                function () {
                    logger()
                        ->channel('telegram')
                        ->debug('whenRequestLifecycleIsLongerThan: ' . request()->url());
                }
            );
        }
    }
}
