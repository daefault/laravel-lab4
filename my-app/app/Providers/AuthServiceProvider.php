<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Character;
use App\Models\Comment;
use Laravel\Passport\Passport; 

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        Gate::define('update-character', function (User $user, Character $character) {
            return $user->id === $character->user_id || $user->is_admin;
        });

        // Gate: может ли пользователь удалять персонажа?
        Gate::define('delete-character', function (User $user, Character $character) {
            return $user->id === $character->user_id || $user->is_admin;
        });


        Gate::define('restore-character', function (User $user) {
            return $user->is_admin;
        });


        Gate::define('force-delete-character', function (User $user) {
            return $user->is_admin;
        });


        Gate::define('view-trash', function (User $user) {
            return $user->is_admin;
        });

        Gate::define('view-character', function (User $user, Character $character) {
            return true;
        });
        Gate::define('update-comment', function (User $user, Comment $comment) {
            return $user->id === $comment->user_id || $user->is_admin;
        });

        Gate::define('delete-comment', function (User $user, Comment $comment) {
            return $user->id === $comment->user_id || $user->is_admin;
        });

        Gate::define('restore-comment', function (User $user) {
            return $user->is_admin;
        });

        Gate::define('force-delete-comment', function (User $user) {
            return $user->is_admin;
        });
        
    }
}