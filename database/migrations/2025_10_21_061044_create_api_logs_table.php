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
        Schema::create('api_logs', function (Blueprint $table) {
            $table->id();
            $table->string('endpoint')->nullable();
            $table->string('job_name')->nullable(); // The name of the job that is being run.
            
            $table->string('status')->default('pending')->index(); // 'success', 'failed', 'partial', 'pending'.
            $table->unsignedInteger('records_processed')->default(0); // Total number of items returned in the array.
            $table->unsignedInteger('records_created')->default(0);  // Number of new product records created.
            $table->unsignedInteger('records_updated')->default(0);  // Number of existing product records updated.

            $table->integer('http_status_code')->nullable(); // The HTTP code from the API response (e.g., 200, 404, 500).
            $table->longText('error_message')->nullable(); // Store details if the fetch fails or is partial.

            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_logs');
    }
};
