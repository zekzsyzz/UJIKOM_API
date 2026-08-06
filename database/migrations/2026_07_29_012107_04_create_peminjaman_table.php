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
        Schema::create('peminjamen', function (Blueprint $table) {
            $table->id();
            $table->string('tgl_pinjam');
            $table->string('tgl_kembali_plan');
            $table->enum('status', ['diajukan', 'dipinjam', 'dikembalikan', 'telat'])->default('diajukan');
            $table->timestamps();
            
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
