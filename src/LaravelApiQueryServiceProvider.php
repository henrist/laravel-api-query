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
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot() {}

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
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
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
