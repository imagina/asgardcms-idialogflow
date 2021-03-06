<?php

namespace Modules\Idialogflow\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\Core\Traits\CanPublishConfiguration;

class IdialogflowServiceProvider extends ServiceProvider
{
  use CanPublishConfiguration;
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishConfig('idialogflow', 'permissions');
        $this->publishConfig('idialogflow', 'config');

        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
      $this->registerBindings();
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('idialogflow.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'idialogflow'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/idialogflow');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/idialogflow';
        }, \Config::get('view.paths')), [$sourcePath]), 'idialogflow');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/idialogflow');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'idialogflow');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'idialogflow');
        }
    }

    /**
     * Register an additional directory of factories.
     * @source https://github.com/sebastiaanluca/laravel-resource-flow/blob/develop/src/Modules/ModuleServiceProvider.php#L66
     */
    public function registerFactories()
    {
        if (! app()->environment('production')) {
            app(Factory::class)->load(__DIR__ . '/../Database/factories');
        }
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

  private function registerBindings()
  {
    $this->app->bind(
      'Modules\Idialogflow\Repositories\BotRepository',
      function () {
        $repository = new \Modules\Idialogflow\Repositories\Eloquent\EloquentBotRepository(new \Modules\Idialogflow\Entities\Bot());
        if (!config('app.cache')) {
          return $repository;
        }
        return new \Modules\Idialogflow\Repositories\Cache\CacheBotDecorator($repository);
      }
    );
  }
}
