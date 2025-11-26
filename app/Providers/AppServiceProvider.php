<?php

namespace App\Providers;

use App\Models\Organization;
use App\Policies\OrganizationPolicy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Organization::class, OrganizationPolicy::class);

        View::composer('layouts.app', function ($view) {
            static $sharedData = null;

            if ($sharedData === null) {
                $organizations = collect();
                $currentOrganization = null;

                if ($user = Auth::user()) {
                    $organizations = $user->organizations()->with('members')->get();
                    $currentOrganizationId = session('current_organization_id');

                    if (!$currentOrganizationId && $organizations->isNotEmpty()) {
                        $currentOrganizationId = $organizations->first()->id;
                        session(['current_organization_id' => $currentOrganizationId]);
                    }

                    $currentOrganization = $organizations->firstWhere('id', $currentOrganizationId) ?? $organizations->first();
                }

                $sharedData = [
                    'sharedOrganizations' => $organizations,
                    'sharedCurrentOrganization' => $currentOrganization,
                ];
            }

            $view->with($sharedData);
        });
    }
}
