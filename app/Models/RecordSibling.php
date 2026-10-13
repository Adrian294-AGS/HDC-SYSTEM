<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['name', 'sex', 'age', 'year_course_school', 'occupation', 'position'])]
class RecordSibling extends Model
{
    /**
     * @return BelongsTo<CumulativeRecord, $this>
     */
    public function record(): BelongsTo
    {
        return $this->belongsTo(CumulativeRecord::class, 'cumulative_record_id');
    }
}
