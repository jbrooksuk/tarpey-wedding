<?php

namespace App\Providers;

use App\Nova\Metrics\BlencoweGuests;
use App\Nova\Metrics\Countdown;
use App\Nova\Metrics\EstimatedRecoveredCost;
use App\Nova\Metrics\GuestsBreakdown;
use App\Nova\Metrics\InviteOpens;
use App\Nova\Metrics\TotalChildren;
use App\Nova\Metrics\TotalGuestsAwaitingPayment;
use App\Nova\Metrics\TotalGuestsPaid;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use App\Nova\Metrics\AccommodationBreakdown;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
                ->withAuthenticationRoutes()
                ->withPasswordResetRoutes()
                ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return true;
        });
    }

    /**
     * Get the cards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        return [
            (new GuestsBreakdown())->width('1/2'),
            (new InviteOpens())->width('1/2'),
            new Countdown(),
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
            //
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
