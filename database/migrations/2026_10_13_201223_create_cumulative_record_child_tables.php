<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Repeatable parts of Form 5: parents, siblings, schools attended, organizations.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('record_parents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cumulative_record_id')->constrained()->cascadeOnDelete();
            $table->string('relation', 10);
            $table->string('name')->nullable();
            $table->unsignedTinyInteger('age')->nullable();
            $table->string('religion', 100)->nullable();
            $table->string('occupation')->nullable();
            $table->string('monthly_income', 100)->nullable();
            $table->string('educational_attainment')->nullable();
            $table->timestamps();

            $table->unique(['cumulative_record_id', 'relation']);
        });

        Schema::create('record_siblings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cumulative_record_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('sex', 10)->nullable();
            $table->unsignedTinyInteger('age')->nullable();
            $table->string('year_course_school')->nullable();
            $table->string('occupation')->nullable();
            $table->unsignedTinyInteger('position')->default(0);
            $table->timestamps();
        });

        Schema::create('record_schools', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cumulative_record_id')->constrained()->cascadeOnDelete();
            $table->string('level', 20);
            $table->string('school_name');
            $table->string('place')->nullable();
            $table->string('inclusive_years', 50)->nullable();
            $table->string('grade_average', 50)->nullable();
            $table->unsignedTinyInteger('position')->default(0);
            $table->timestamps();
        });

        Schema::create('record_organizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cumulative_record_id')->constrained()->cascadeOnDelete();
            $table->string('organization');
            $table->string('activity')->nullable();
            $table->unsignedTinyInteger('position')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('record_organizations');
        Schema::dropIfExists('record_schools');
        Schema::dropIfExists('record_siblings');
        Schema::dropIfExists('record_parents');
    }
};
