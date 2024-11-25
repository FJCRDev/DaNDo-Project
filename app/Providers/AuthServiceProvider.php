<?php

namespace App\Providers;

use App\Models\CharacterSheet;
use App\Models\Session;
use App\Policies\CharacterSheetPolicy;
use App\Policies\SessionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        CharacterSheet::class => CharacterSheetPolicy::class,
        Session::class => SessionPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
