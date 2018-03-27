<?php

namespace App\ShareMeshi\ServiceProviders;

use Illuminate\Support\ServiceProvider;

class BackendServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

        $interfacePath = 'App\ShareMeshi\Repositories';
        $repoPath = 'App\ShareMeshi\Repositories';

        $repositories = [

            'User',

        ];

        foreach($repositories as $repo){

            $interface = $interfacePath.'\\'.$repo.'Interface';
            $repository = $repoPath.'\\'.$repo.'Repository';

            $this->app->bind($interface, $repository);
        }

    }
}
