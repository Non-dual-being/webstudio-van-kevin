<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_submissions', function (Blueprint $table) {
            $table->id();
            $table->ulid('reference')->unique();
            $table->string('attempt_hash', 64)->unique();
            $table->string('session_hash', 64);
            $table->string('payload_hash', 64);
            $table->string('name', 120);
            $table->string('email', 254);
            $table->string('service', 32)->nullable();
            $table->text('body');
            $table->timestamps();
        });

        Schema::create('contact_mail_deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_submission_id')->constrained()->restrictOnDelete();
            $table->string('kind', 20);
            $table->string('status', 20)->default('pending');
            $table->unsignedInteger('attempts')->default(0);
            $table->unsignedInteger('attempt_limit')->default(3);
            $table->uuid('dispatch_token')->nullable();
            $table->timestamp('available_at');
            $table->timestamp('queued_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->string('error_category', 40)->nullable();
            $table->timestamps();
            $table->unique(['contact_submission_id', 'kind']);
            $table->index(['status', 'available_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_mail_deliveries');
        Schema::dropIfExists('contact_submissions');
    }
};
