<?php declare(strict_types=1);

namespace App\Models;

use App\Enums\ChangeFrequency;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $checksum
 * @property string $location
 * @property CarbonImmutable|null $last_modified_at
 * @property ChangeFrequency|null $change_frequency
 * @property float|null $priority
 * @property string $status
 * @property int $domain_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property Domain $domain
 *
 * @method static Builder<static>|Location newModelQuery()
 * @method static Builder<static>|Location newQuery()
 * @method static Builder<static>|Location onlyTrashed()
 * @method static Builder<static>|Location query()
 * @method static Builder<static>|Location withTrashed()
 * @method static Builder<static>|Location withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Location extends Model
{
    use SoftDeletes;

    /** {@inheritDoc} */
    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    /** {@inheritDoc} */
    protected $casts = [
        'change_frequency' => ChangeFrequency::class,
        'checksum' => 'integer',
        'priority' => 'float',
        'last_modified_at' => 'immutable_datetime',
    ];

    /**
     * @return BelongsTo<Domain, $this>
     */
    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class);
    }
}
