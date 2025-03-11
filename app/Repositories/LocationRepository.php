<?php declare(strict_types=1);

namespace App\Repositories;

use App\Enums\LocationStatus;
use App\Models\Domain;
use App\Models\Location;
use Chiiya\Common\Repositories\AbstractRepository;
use Chiiya\Common\Repositories\HasBulkInserts;
use Illuminate\Database\Eloquent\Builder;

/**
 * @extends AbstractRepository<Location>
 */
class LocationRepository extends AbstractRepository
{
    use HasBulkInserts;
    protected string $model = Location::class;

    public function resetLocationsForDomain(Domain $domain): void
    {
        $this->newQuery()
            ->where('domain_id', '=', $domain->id)
            ->update(['status' => LocationStatus::Pending]);
    }

    public function deletePendingLocations(Domain $domain): void
    {
        $this->newQuery()
            ->where('domain_id', '=', $domain->id)
            ->where('status', '=', LocationStatus::Pending)
            ->update([
                'status' => LocationStatus::Inactive,
                'deleted_at' => now(),
            ]);
    }

    /**
     * {@inheritDoc}
     */
    protected function applyFilters(Builder $builder, array $parameters): Builder
    {
        return $builder;
    }

    protected function bulkInsertColumns(): array
    {
        return [
            'domain_id',
            'checksum',
            'location',
            'last_modified_at',
            'change_frequency',
            'priority',
            'status',
            'created_at',
            'updated_at',
            'deleted_at',
        ];
    }

    protected function bulkInsertUpdatedColumns(): array
    {
        return ['last_modified_at', 'change_frequency', 'priority', 'status', 'updated_at', 'deleted_at'];
    }
}
