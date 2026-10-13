<?php

namespace Database\Seeders;

use App\Models\College;
use App\Models\Program;
use Illuminate\Database\Seeder;

/**
 * Sample colleges and programs. Replace with the official ULS list.
 * Safe to re-run: rows are matched by code.
 */
class CollegeProgramSeeder extends Seeder
{
    /**
     * @var array<string, array{name: string, programs: array<string, string>}>
     */
    private const COLLEGES = [
        'CIT' => [
            'name' => 'College of Information Technology',
            'programs' => [
                'BSIT' => 'Bachelor of Science in Information Technology',
                'BSCS' => 'Bachelor of Science in Computer Science',
            ],
        ],
        'CEA' => [
            'name' => 'College of Engineering and Architecture',
            'programs' => [
                'BSCE' => 'Bachelor of Science in Civil Engineering',
                'BSArch' => 'Bachelor of Science in Architecture',
            ],
        ],
        'CBA' => [
            'name' => 'College of Business and Accountancy',
            'programs' => [
                'BSA' => 'Bachelor of Science in Accountancy',
                'BSBA' => 'Bachelor of Science in Business Administration',
            ],
        ],
        'CON' => [
            'name' => 'College of Nursing',
            'programs' => [
                'BSN' => 'Bachelor of Science in Nursing',
            ],
        ],
        'CTE' => [
            'name' => 'College of Teacher Education',
            'programs' => [
                'BEEd' => 'Bachelor of Elementary Education',
                'BSEd' => 'Bachelor of Secondary Education',
            ],
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::COLLEGES as $collegeCode => $college) {
            $collegeModel = College::updateOrCreate(['code' => $collegeCode], ['name' => $college['name']]);

            foreach ($college['programs'] as $programCode => $programName) {
                Program::updateOrCreate(
                    ['code' => $programCode],
                    ['college_id' => $collegeModel->id, 'name' => $programName],
                );
            }
        }
    }
}
