<?php
namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        App::bindMethod(ProductCreated::class, '@handle', fn ($job) => $job->handle());
        App::bindMethod(ProductUpdated::class, '@handle', fn ($job) => $job->handle());
        App::bindMethod(ProductDeleted::class, '@handle', fn ($job) => $job->handle());
        App::bindMethod(LinkCreated::class, '@handle', fn ($job) => $job->handle());
        App::bindMethod(OrederCompleted::class, '@handle', fn ($job) => $job->handle());
    }
}
