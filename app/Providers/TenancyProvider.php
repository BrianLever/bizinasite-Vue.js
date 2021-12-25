<?php

namespace App\Providers;

use App\Models\Website as Tenant;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\ServiceProvider;

class TenancyProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRequests();

        $this->configureQueue();
    }

    public function configureRequests()
    {
        if (!$this->app->runningInConsole()) {

            $tenant = Tenant::where("domain", request()->getHttpHost())
                ->where("status", "active")
                ->first();


            if ($tenant == null) {
                \Log::info("404-" . request()->getHttpHost());
                abort(404);
            }

            $tenant->configure()->use();

            $tenant->pages = $tenant->pages;

            if ($tenant->status_by_owner == 'maintenance') {
                abort(503);
            } elseif ($tenant->status_by_owner != 'active') {
                abort(404);
            }
        }
    }

    public function configureQueue()
    {
        $this->app['queue']->createPayloadUsing(function () {
            return $this->app['tenant'] ? ['tenant_id' => $this->app['tenant']->id] : [];
        });

        $this->app['events']->listen(JobProcessing::class, function ($event) {
            if (isset($event->job->payload()['tenant_id'])) {
                Tenant::find($event->job->payload()['tenant_id'])->configure()->use();
            }
        });
    }
}
