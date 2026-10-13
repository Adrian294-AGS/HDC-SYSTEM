<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Access logs can now point at a SATA result (action "view_sata") as well as a Form 5 record.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('access_logs', function (Blueprint $table) {
            $table->foreignId('cumulative_record_id')->nullable()->change();
            $table->foreignId('entrance_test_result_id')->nullable()->after('cumulative_record_id')
                ->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('access_logs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('entrance_test_result_id');
        });
    }
};
