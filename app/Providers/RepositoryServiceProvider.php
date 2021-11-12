<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Api\V1\Repositories\BaseRepository;
use App\Api\V1\Repositories\IBaseRepository;

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
