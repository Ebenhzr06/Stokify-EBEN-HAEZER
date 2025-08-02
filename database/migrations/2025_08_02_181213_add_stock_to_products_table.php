<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStockToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    // ...
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Ganti 'price' dengan 'selling_price' atau 'purchase_price'
            $table->integer('stock')->default(0)->after('selling_price');
        });
    }
    // ...

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('stock'); // Hapus kolom 'stock' jika migrasi di-rollback
        });
    }
}
