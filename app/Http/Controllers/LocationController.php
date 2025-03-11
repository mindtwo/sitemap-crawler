<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\LocationResource;
use App\Models\Location;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;

class LocationController extends Controller
{
    /**
     * List all locations for a domain.
     */
    public function index(int|string $domain): AnonymousResourceCollection
    {
        $locations = Location::query()
            ->where('domain_id', '=', (int) $domain)
            ->orderByDesc('last_modified_at')
            ->paginate();

        return LocationResource::collection($locations);
    }
}
