<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string("title");
            $table->enum('status', ['pending', 'draft', 'published', 'reported', 'banned'])->default('pending');
            $table->tinyInteger('approved_x_times')->default(0);
            $table->text("description")->nullable();
            $table->string("file_path");
            $table->string("file_type");
            $table->timestamps();
        });

        Schema::create('post_upvotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->unique(['user_id', 'post_id']);
        });

        Schema::create('post_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->unique(['user_id', 'post_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_reports');
        Schema::dropIfExists('post_upvotes');
        Schema::dropIfExists('posts');
    }
};
