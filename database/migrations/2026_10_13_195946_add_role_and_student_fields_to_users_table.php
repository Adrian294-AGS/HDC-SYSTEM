<?php

use App\Enums\Role;
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
        Schema::table('users', function (Blueprint $table) {
            $table->string('last_name')->nullable()->after('name');
            $table->string('first_name')->nullable()->after('last_name');
            $table->string('middle_name')->nullable()->after('first_name');
            $table->enum('role', Role::values())->default(Role::Student->value)->after('middle_name')->index();
            $table->string('student_number', 30)->nullable()->unique()->after('role');
            $table->foreignId('program_id')->nullable()->after('student_number')->constrained()->restrictOnDelete();
            $table->unsignedTinyInteger('year_level')->nullable()->after('program_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('program_id');
            $table->dropUnique(['student_number']);
            $table->dropIndex(['role']);
            $table->dropColumn(['last_name', 'first_name', 'middle_name', 'role', 'student_number', 'year_level']);
        });
    }
};
