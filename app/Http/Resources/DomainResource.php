<?php declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Domain;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Domain
 */
class DomainResource extends JsonResource
{
    /**
     * {@inheritDoc}
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'domain' => $this->domain,
            'locations' => $this->locations_count,
        ];
    }
}
