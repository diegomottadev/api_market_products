<?php

use App\Category;
use App\Product;
use App\Transaction;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $tableNames = Schema::getConnection()->getDoctrineSchemaManager()->listTableNames();

        Schema::disableForeignKeyConstraints();
        foreach ($tableNames as $name) {
            //if you don't want to truncate migrations
            if ($name == 'migrations') {
                continue;
            }
            DB::table($name)->truncate();
        }
        // DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        // DB::table('categories')->truncante();
        // DB::table('products')->truncante();
        // DB::table('transactions')->truncante();
        // DB::table('users')->truncante();
        // DB::table('category_product')->truncante();

        $cantidadUsuarios = 1000;
        $cantidadCategorias = 30;
        $cantidadProductos = 1000;
        $cantidadTransacciones = 1000;

        factory(User::class,$cantidadUsuarios)->create();
        factory(Category::class,$cantidadCategorias)->create();
        factory(Product::class,$cantidadProductos)->create()->each(
            function ($product){
                $categorias = Category::all()->random(mt_rand(1,5))->pluck('id');
                $product->categories()->attach($categorias);

            }
        );
        factory(Transaction::class,$cantidadTransacciones)->create();
        Schema::enableForeignKeyConstraints();

    }
}
