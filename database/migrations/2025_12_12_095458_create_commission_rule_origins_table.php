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
        Schema::create('commission_rule_origins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commission_rule_id')->constrained('commission_rules')->onDelete('cascade');
            $table->string('airport_code', 10);
            $table->timestamps();
            // Index for faster searches
            $table->index('airport_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_rule_origins');
    }
};
