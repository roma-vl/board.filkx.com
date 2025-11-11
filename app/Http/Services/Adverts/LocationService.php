<?php

namespace App\Http\Services\Adverts;

use App\Models\Adverts\Location;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class LocationService
{
    public const int MIN_SEARCH_LENGTH = 3;

    public function search(string $query): Collection
    {
        if (strlen($query) < self::MIN_SEARCH_LENGTH) {
            return collect();
        }

        return Location::where('name', 'like', "%{$query}%")
            ->orderBy('name')
            ->limit(10)
            ->get(['id', 'name', 'slug']);
    }

    public function getCountries(): Collection
    {
        return Location::whereDepth(0)->get();
    }

    public function getRegions(Location $region): Collection
    {
        return Location::query()
            ->where('parent_id', $region->id)
            ->get(['id', 'name', 'slug']);
    }

    public function getCities(Location $region): Collection
    {
        return $region->descendants()
            ->orderBy('name')
            ->take(300)
            ->get(['name', 'slug', 'id']);
    }

    public function getAreas(Location $region): Collection
    {
        return Location::query()
            ->where('parent_id', $region->id)
            ->get(['id', 'name', 'slug']);
    }

    public function getVillages(Location $area): Collection
    {
        return Location::query()
            ->where('parent_id', $area->id)
            ->get(['id', 'name', 'slug']);
    }

    public function createLocation(array $data): Model
    {
        $parent = Location::findOrFail($data['parent_id']);

        $location = new Location([
            'name' => $data['name'],
            'slug' => \Str::slug($data['name']),
        ]);

        $location->appendToNode($parent)->save();

        return $location;
    }

    public function deleteLocation(int $id): void
    {
        $location = Location::findOrFail($id);
        $location->delete();
    }
}
