<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Api\V1\Repositories\BaseRepository;
use App\Api\V1\Repositories\IBaseRepository;
use App\Api\V1\Services\Auth\OtpService\IOtpService;
use App\Api\V1\Services\Auth\OtpService\OtpService;
use App\Api\V1\Repositories\Auth\OtpRepository\IOtpRepository;
use App\Api\V1\Repositories\Auth\OtpRepository\OtpRepository;

/**
 * Class RepositoryServiceProvider
 *
 * @package App\Providers
 */
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(IBaseRepository::class, BaseRepository::class);
        $this->app->bind(IOtpRepository::class, OtpRepository::class);
        $this->app->bind(IOtpService::class, OtpService::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
