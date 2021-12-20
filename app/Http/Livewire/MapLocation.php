<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Location;

class MapLocation extends Component
{
    public $locationId, $long, $lat, $title, $description;
    public $geoJson;

    private function loadLocations()
    {
        $locations = Location::orderBy('created_at', 'desc')->get();

        $customLocations = [];

        foreach ($locations as $location) {
            $customLocations[] = [
                'type' => 'Feature',
                'geometry' => [
                    'coordinates' => [$location->long, $location->lat],
                    'type' => 'Point'
                ],
                'properties' => [
                    'locationId' => $location->id,
                    'title' => $location->title,
                    'description' => $location->description
                ]

            ];
        }

        $geoLocation = [
            'type' => 'Feature Collection',
            'features' => $customLocations
        ];

        $geoJson = collect($geoLocation)->toJson();
        $this->geoJson = $geoJson;
    }

    public function saveLocation()
    {
        $this->validate([
            'long' => 'required',
            'lat' => 'required',
            'title' => 'required',
            'description' => 'required'
        ]);

        Location::create([
            'long' => $this->long,
            'lat' => $this->lat,
            'title' => $this->title,
            'description' => $this->description
        ]);

        $this->clearForm();
        $this->loadLocations();
        $this->dispatchBrowserEvent('locationAdded', $this->geoJson);
    }

    public function findLocationById($id)
    {
        $location = Location::findOrFail($id);

        $this->locationId = $id;
        $this->long = $location->long;
        $this->lat = $location->lat;
        $this->title = $location->title;
        $this->description = $location->description;
    }

    private function clearForm()
    {
        $this->long = '';
        $this->lat = '';
        $this->title = '';
        $this->description = '';
    }

    public function render()
    {
        $this->loadLocations();
        return view('livewire.map-location');
    }

    public function coba()
    {
        return view('livewire.map_coba');
    }
}
