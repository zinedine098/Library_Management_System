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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('author');
            $table->string('publisher');
            $table->string('isbn')->nullable();
            $table->year('published_year')->nullable();
            $table->integer('stock_total')->default(1);
            $table->integer('stock_available')->default(1);
            $table->string('cover_image')->nullable();
            $table->timestamps();

            // Indexes for search performance
            $table->index('title');
            $table->index('author');
            $table->index('isbn');
            $table->index('category_id');
            $table->index('stock_available');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
