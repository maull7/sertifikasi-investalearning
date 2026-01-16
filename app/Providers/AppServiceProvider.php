<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Contracts\BankQuestionRepositoryInterface::class,
            \App\Repositories\BankQuestionRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\ExamRepositoryInterface::class,
            \App\Repositories\ExamRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
