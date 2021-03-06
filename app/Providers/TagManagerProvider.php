<?php

namespace App\Providers;

use App\Classes\RedisTagging;
use App\Classes\Search\Tag\AuthorTagManagerViaApi;
use App\Classes\Search\Tag\ContentsetTagManagerViaApi;
use App\Classes\Search\Tag\ContentTagManagerViaApi;
use App\Classes\Search\Tag\ProductTagManagerViaApi;
use App\Classes\Search\TaggingInterface;
use App\Classes\TagSplitter;
use App\Console\Commands\AuthorTagCommand;
use App\Console\Commands\ContentTagCommand;
use App\Console\Commands\ProductTagCommand;
use App\Console\Commands\SetTagCommand;
use App\Observers\ContentObserver;
use App\Observers\ProductObserver;
use App\Observers\SetObserver;
use Illuminate\Support\ServiceProvider;

class TagManagerProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        $this->app->when(ContentObserver::class)
            ->needs(TaggingInterface::class)
            ->give(function () {
                return (new ContentTagManagerViaApi());
            });

        $this->app->when(SetObserver::class)
            ->needs(TaggingInterface::class)
            ->give(function () {
                return (new ContentsetTagManagerViaApi());
            });

        $this->app->when(ProductObserver::class)
            ->needs(TaggingInterface::class)
            ->give(function () {
                return (new ProductTagManagerViaApi());
            });

        $this->app->when(ContentTagCommand::class)
            ->needs(TaggingInterface::class)
            ->give(function () {
                return (new ContentTagManagerViaApi());
            });

        $this->app->when(ProductTagCommand::class)
            ->needs(TaggingInterface::class)
            ->give(function () {
                return (new ProductTagManagerViaApi());
            });

        $this->app->when(SetTagCommand::class)
            ->needs(TaggingInterface::class)
            ->give(function () {
                return (new ContentsetTagManagerViaApi());
            });

        $this->app->when(AuthorTagCommand::class)
            ->needs(TaggingInterface::class)
            ->give(function () {
                return (new AuthorTagManagerViaApi());
            });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(TagSplitter::class, function ($app) {
            return new TagSplitter();
        });

        $this->app->singleton(RedisTagging::class, function ($app) {
            return new RedisTagging();
        });
    }
}
