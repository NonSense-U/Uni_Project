<?php

use App\Models\StoreOwner;
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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(StoreOwner::class)->constrained()->cascadeOnDelete();
            $table->string("storeName")->unique();
            $table->string("storePicture")->nullable();
            $table->string("location");
            $table->string("about")->nullable();
            $table->float('stars',1)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
