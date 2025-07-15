<?php

use App\Models\Project;
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
        Schema::create('fingerprints', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Project::class)->constrained()->cascadeOnDelete();
            $table->string('visitor_hash', 128);
            $table->string('local_id', 128)->nullable();
            $table->string('ip', 64)->nullable();
            $table->string('user_agent', 512)->nullable();
            $table->string('language', 32)->nullable();
            $table->string('platform', 128)->nullable();
            $table->string('screen', 32)->nullable();
            $table->integer('color_depth')->nullable();
            $table->float('pixel_ratio')->nullable();
            $table->string('timezone', 64)->nullable();
            $table->string('referrer', 512)->nullable();
            $table->string('connection_type', 32)->nullable();
            $table->integer('memory')->nullable();
            $table->integer('cores')->nullable();
            $table->boolean('webdriver')->default(false);
            $table->unsignedInteger('time_to_submit')->nullable();
            $table->timestamps();

            // Индексация
            $table->index(['visitor_hash', 'created_at']);
            $table->index(['user_agent', 'created_at']);
            $table->index(['project_id', 'ip']);
            $table->index(['project_id', 'local_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fingerprints');
    }
};
