<?php

use App\Models\Customer;
use App\Models\Store;
use App\Models\StoreInventory;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Customer::class)->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(StoreInventory::class);
            $table->decimal('total_cost');
            $table->enum('status',['Pending','Canceled','Completed'])->nullable()->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
