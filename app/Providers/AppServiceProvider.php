<?php

namespace App\Providers;
use Livewire\Livewire;
use App\Http\Livewire\Posts;
use App\Http\Livewire\Counter;
use App\Http\Livewire\CartDelete;
use App\Http\Livewire\OrderTable;
use App\Http\Livewire\CartSummary;
use App\Http\Livewire\CounterCart; 
use App\Http\Livewire\CartIconBadge;
use App\Http\Livewire\CategoryFilter;
use App\Http\Livewire\CheckoutStatus;
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
        Livewire::component('cart-summary', CartSummary::class);
        Livewire::component('cart-delete', CartDelete::class);
        Livewire::component('cart-icon-badge', CartIconBadge::class);
        Livewire::component('category-filter', CategoryFilter::class);
        Livewire::component('posts', Posts::class);
        Livewire::component('order-table', OrderTable::class);
        Livewire::component('checkout-status', CheckoutStatus::class);
    }
}
