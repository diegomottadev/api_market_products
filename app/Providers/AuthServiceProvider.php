<?php

namespace App\Providers;

use App\Buyer;
use App\Policies\BuyerPolicy;
use App\Policies\ProductPolicy;
use App\Policies\SellerPolicy;
use App\Policies\TransactionPolicy;
use App\Policies\UserPolicy;
use App\Seller;
use App\Transaction;
use App\User;

use Carbon\Carbon;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Buyer::class => BuyerPolicy::class,
        Seller::class => SellerPolicy::class,
        User::class => UserPolicy::class,
        Transaction::class => TransactionPolicy::class,
        Product::class => ProductPolicy::class,

    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('admin-action',function ($user){
                return $user->esAdministrador();
        });
        //
        Passport::routes();
        Passport::tokensExpireIn(Carbon::now()->addMinutes(90));
        //refresca el token luego de haberse expirado luego de 30 diuas
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));

        Passport::tokensCan([
            'purchase-product' => 'Crear transacciones para comprar producto determinados',
            'manage-products' => 'Crear,ver, actualizar y eliminar productos',
            'manage-account' => 'Obtener la información de la cuenta, nombre, email, estado (sin contraseña), modificar datos como email, nombre y contraseña. No puede eliminar la cuenta',
            'read-general' => 'Obtener información general, categorias donde se compra y se vende, productos vendidos o comprados, transacciones, compras y ventas',
        ]);
    }
}
