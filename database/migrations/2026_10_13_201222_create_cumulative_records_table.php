<?php

use App\Enums\RecordStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * HDC Form 5: Student Cumulative Record. One row per student per school year + semester.
 * Option fields store the keys defined in App\Support\Form5.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cumulative_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // A0. Entry information
            $table->string('school_year', 9)->nullable();
            $table->string('semester', 10)->nullable();
            $table->unsignedTinyInteger('month_of_entry')->nullable();
            $table->string('student_type', 20)->nullable();
            $table->string('photo_path')->nullable();

            // A1. Personal data
            $table->string('last_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->foreignId('program_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedTinyInteger('year_level')->nullable();
            $table->string('major')->nullable();
            $table->string('home_address', 500)->nullable();
            $table->string('sex', 10)->nullable();
            $table->string('civil_status', 10)->nullable();
            $table->string('contact_number', 50)->nullable();
            $table->string('email')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('nationality', 100)->nullable();
            $table->string('religion', 100)->nullable();
            $table->string('ethnicity', 100)->nullable();
            $table->string('boarding_address', 500)->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_address', 500)->nullable();
            $table->string('emergency_contact_number', 50)->nullable();
            $table->string('spouse_name')->nullable();
            $table->unsignedTinyInteger('number_of_children')->nullable();

            // A2. Family background (parents and siblings live in child tables)
            $table->json('parents_status')->nullable();
            $table->text('family_view')->nullable(); // encrypted, highly sensitive
            $table->text('family_concern')->nullable(); // encrypted, highly sensitive
            $table->string('home_language')->nullable();

            // A3. Health
            $table->string('height', 30)->nullable();
            $table->string('weight', 30)->nullable();
            $table->string('handicap')->nullable();
            $table->boolean('normal_eyesight')->nullable();
            $table->boolean('normal_hearing')->nullable();
            $table->string('persistent_illness')->nullable();
            $table->boolean('hospitalized')->nullable();
            $table->string('hospitalization_reason')->nullable();
            $table->boolean('had_surgery')->nullable();
            $table->string('surgery_details')->nullable();
            $table->boolean('has_allergies')->nullable();
            $table->string('allergy_details')->nullable();

            // A4. Education (schools live in a child table)
            $table->string('scholarships_honors', 500)->nullable();
            $table->string('schooling_nature', 20)->nullable();
            $table->text('interruption_reason')->nullable();
            $table->string('scholarship_to_apply')->nullable();
            $table->string('difficult_subjects')->nullable();
            $table->string('best_liked_subjects')->nullable();
            $table->json('home_facilities')->nullable();
            $table->string('recent_book')->nullable();

            // A5. Vocational
            $table->json('course_reasons')->nullable();
            $table->string('course_reason_other')->nullable();
            $table->string('second_course')->nullable();
            $table->text('considered_occupation')->nullable();
            $table->boolean('has_work_experience')->nullable();
            $table->string('work_experience_when')->nullable();
            $table->string('work_experience_details')->nullable();
            $table->text('work_experience_likes')->nullable();

            // A6. Social functions (organizations live in a child table)
            $table->string('hobbies', 500)->nullable();

            // A7. Self-description
            $table->json('self_description')->nullable();

            // A8-A9. Skills and counseling willingness
            $table->json('skills_to_develop')->nullable();
            $table->string('skills_other')->nullable();
            $table->boolean('willing_to_see_counselor')->nullable();
            $table->string('counselor_willingness_reason', 500)->nullable();

            // Wizard progress and workflow
            $table->json('completed_steps')->nullable();
            $table->enum('status', RecordStatus::values())->default(RecordStatus::Draft->value)->index();
            $table->timestamp('submitted_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();

            // A10. Data privacy consent
            $table->boolean('consent_given')->default(false);
            $table->timestamp('consented_at')->nullable();
            $table->string('consent_name')->nullable();

            $table->timestamps();

            $table->unique(['user_id', 'school_year', 'semester']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cumulative_records');
    }
};
