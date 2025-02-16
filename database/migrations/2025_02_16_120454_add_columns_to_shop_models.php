<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $cb = function (Blueprint $table) {
            $table->boolean('on_home_page')->default(false);
            $table->integer('sorting')->default(9999);
        };

        Schema::table('products', $cb);
        Schema::table('brands', $cb);
        Schema::table('categories', $cb);
    }
};
