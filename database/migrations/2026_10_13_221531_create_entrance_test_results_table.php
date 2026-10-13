<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * SATA entrance test results, encoded by staff. A student can have several (retakes);
 * the latest by exam date is the current one.
 */
return new class extends Migration
{
    private const SUBTESTS = ['vr', 'nvr', 'qr', 'rv', 'rc', 'mc', 'ma', 'wm', 'wc'];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('entrance_test_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->date('exam_date');

            foreach (self::SUBTESTS as $subtest) {
                $table->unsignedTinyInteger("{$subtest}_score");
                $table->string("{$subtest}_descriptor", 30);
            }

            $table->unsignedSmallInteger('quotient');
            $table->string('quotient_descriptor', 30);
            $table->text('remarks')->nullable();
            $table->foreignId('encoded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['student_id', 'exam_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entrance_test_results');
    }
};
