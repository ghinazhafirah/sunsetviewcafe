<?php

namespace App\Providers;
use Livewire\Livewire;
use App\Http\Livewire\Posts;
use App\Http\Livewire\Counter;
use App\Http\Livewire\CounterCart; 
use App\Http\Livewire\CategoryFilter;
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
        //
        Livewire::component('counter', Counter::class);
        Livewire::component('counter-cart', CounterCart::class);
        Livewire::component('category-filter', CategoryFilter::class);
        Livewire::component('posts', Posts::class);
    }
}
