<?php declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Location
 */
class LocationResource extends JsonResource
{
    /**
     * {@inheritDoc}
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'checksum' => $this->checksum,
            'location' => $this->location,
            'last_modified_at' => $this->last_modified_at?->toIso8601String(),
            'change_frequency' => $this->change_frequency?->value,
            'priority' => $this->priority,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
