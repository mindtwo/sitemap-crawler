<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\DomainResource;
use App\Models\Domain;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;

class DomainController extends Controller
{
    /**
     * List all domains.
     */
    public function index(): AnonymousResourceCollection
    {
        $domains = Domain::query()->withCount('locations')->paginate();

        return DomainResource::collection($domains);
    }
}
