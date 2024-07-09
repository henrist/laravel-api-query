<?php

namespace Henrist\LaravelApiQuery;

use Henrist\LaravelApiQuery\Processors\Fields;
use Henrist\LaravelApiQuery\Processors\Filter;
use Henrist\LaravelApiQuery\Processors\LimitOffset;
use Henrist\LaravelApiQuery\Processors\Order;
use Henrist\LaravelApiQuery\Processors\With;
use Illuminate\Support\ServiceProvider;

class LaravelApiQueryServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     */
    protected bool $defer = false;

    /**
     * Bootstrap the application events.
     */
    public function boot(): void {}

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->singleton('ApiQuery', function () {
            $aq = new ApiQuery;

            $aq->addDefaultProcessor(new Filter);
            $aq->addDefaultProcessor(new LimitOffset);
            $aq->addDefaultProcessor(new Order);
            $aq->addDefaultProcessor(new With);
            $aq->addDefaultProcessor(new Fields);

            return $aq;
        });
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [];
    }
}
