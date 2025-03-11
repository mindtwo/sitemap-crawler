<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $domain
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Collection<int, Location> $locations
 * @property int|null $locations_count
 *
 * @method static Builder<static>|Domain newModelQuery()
 * @method static Builder<static>|Domain newQuery()
 * @method static Builder<static>|Domain query()
 *
 * @mixin \Eloquent
 */
class Domain extends Model
{
    /** {@inheritDoc} */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * @return HasMany<Location, $this>
     */
    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }
}
