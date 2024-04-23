<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id');
            $table->foreignId('subcategory_id');
            $table->string('product_name');
            $table->string('url')->nullable();
            $table->string('color');
            $table->string('style')->nullable();
            $table->string('size');
            $table->string('regular_price')->nullable();
            $table->string('interior_condition')->nullable();
            $table->string('sale_price');
            $table->string('brand_name');
            $table->string('product_rating_avg')->nullable();
            $table->string('total_review')->nullable();
            $table->string('width')->nullable();
            $table->string('height')->nullable();
            $table->string('strap_drop')->nullable();
            $table->string('depth')->nullable();
            $table->string('exterior_condition')->nullable();
            $table->string('material_condition')->nullable();
            $table->string('material')->nullable();
            $table->string('manufactured_by')->nullable();
            $table->string('design_name')->nullable();
            $table->string('interior_color')->nullable();
            $table->string('product_code')->nullable();
            $table->boolean('our_picks')->default(false);
            $table->longText('details');
            $table->string('target_audience')->nullable();
            $table->string('status')->default('published');
            $table->string('view_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
