<?php

namespace App\Models;

use Database\Factories\EntranceTestResultFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * SATA entrance test result. `student_id`, `encoded_by` and `updated_by` are set explicitly in code.
 */
#[Fillable([
    'exam_date',
    'vr_score', 'vr_descriptor', 'nvr_score', 'nvr_descriptor', 'qr_score', 'qr_descriptor',
    'rv_score', 'rv_descriptor', 'rc_score', 'rc_descriptor', 'mc_score', 'mc_descriptor',
    'ma_score', 'ma_descriptor', 'wm_score', 'wm_descriptor', 'wc_score', 'wc_descriptor',
    'quotient', 'quotient_descriptor', 'remarks',
])]
class EntranceTestResult extends Model
{
    /** @use HasFactory<EntranceTestResultFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        $casts = [
            'exam_date' => 'date',
            'quotient' => 'integer',
        ];

        foreach (array_keys(self::subtests()) as $key) {
            $casts["{$key}_score"] = 'integer';
        }

        return $casts;
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function encoder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'encoded_by');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Subtests from config/sata.php: key => ['code' => 'VR', 'name' => 'Verbal Reasoning'].
     *
     * @return array<string, array{code: string, name: string}>
     */
    public static function subtests(): array
    {
        return config('sata.subtests');
    }

    /**
     * Suggested descriptor for a score, using the ranges in config/sata.php.
     *
     * @param  'subtest'|'quotient'  $kind
     */
    public static function suggestDescriptor(?int $score, string $kind = 'subtest'): ?string
    {
        if ($score === null) {
            return null;
        }

        foreach (config("sata.ranges.{$kind}", []) as $range) {
            if (($range['min'] === null || $score >= $range['min']) && ($range['max'] === null || $score <= $range['max'])) {
                return $range['descriptor'];
            }
        }

        return null;
    }
}
