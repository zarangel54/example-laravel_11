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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60);
            $table->string('last_name', 60);
            $table->string('email', 150)->nullable();
            $table->string('phone', 20)->nullable();
            $table->foreignId('workspace_id')->nullable()->constrained('workspaces')->onDelete('set null');
            $table->jsonb('custom_attributes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
