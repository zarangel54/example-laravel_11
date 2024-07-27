<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dynamic_attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('type', 50);
            $table->string('default_value')->nullable();
            $table->integer('group_attribute_id')->nullable();
            $table->boolean('required_flag')->default(false);
            $table->boolean('visible_flag')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dynamic_attributes');
    }
};
