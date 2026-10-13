<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['organization', 'activity', 'position'])]
class RecordOrganization extends Model
{
    /**
     * @return BelongsTo<CumulativeRecord, $this>
     */
    public function record(): BelongsTo
    {
        return $this->belongsTo(CumulativeRecord::class, 'cumulative_record_id');
    }
}
