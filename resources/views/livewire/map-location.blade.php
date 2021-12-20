<div>
    <style>
        .mapboxgl-popup {
        max-width: 400px;
        font: 12px/20px 'Helvetica Neue', Arial, Helvetica, sans-serif;
        }
    </style>
    <div class="container">
        <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-dark text-white">
                            MAPBOX
                        </div>
                        <div class="card-body">
                            <div wire:ignore id='map' style='width: 100%; height: 80vh;'></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-dark text-white">
                            FORM
                        </div>
                        <div class="card-body">
                            <form wire:submit.prevent="saveLocation">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Longtitude</label>
                                            <input wire:model="long" type="text" class="form-control">
                                            @error('long') <small class="text-danger">{{$message}}</small>@enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Lattitude</label>
                                            <input wire:model="lat" type="text" class="form-control">
                                            @error('lat') <small class="text-danger">{{$message}}</small>@enderror
                                        </div>
                                    </div>
                                        <div class="form-group">
                                            <label>Title</label>
                                            <input wire:model="title" type="text" class="form-control">
                                            @error('title') <small class="text-danger">{{$message}}</small>@enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea wire:model="description" class="form-control"></textarea>
                                            @error('description') <small class="text-danger">{{$message}}</small>@enderror
                                        </div>
                                        <div class="form-group mt-3">
                                            <button type="submit" class="btn btn-dark btn-block">Submit Location</button>
                                        </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
        </div>
    </div>


</div>

@push('scripts')

<script>
   document.addEventListener('livewire:load', () => {
    const defaultLocation = [111.81076691473959, -7.243110156823164]
    mapboxgl.accessToken = '{{env("MAPBOX_KEY")}}';
    const map = new mapboxgl.Map({
        container: 'map',
        center: defaultLocation,
        zoom: 9.50,
        style: 'mapbox://styles/mapbox/dark-v10'
    });

    const loadLocations = (geoJson) => {
        geoJson.features.forEach((location) => {
            const {geometry, properties} = location
            const {iconSize, locationId, title, image, description} = properties

            let markerElement = document.createElement('div')
            markerElement.className = 'marker' + locationId
            markerElement.id = locationId
            markerElement.style.backgroundImage = 'url(https://www.iconpacks.net/icons/1/free-hospital-icon-1066-thumb.png)'
            markerElement.style.backgroundSize = 'cover'
            markerElement.style.width = '40px'
            markerElement.style.height = '40px'

            const content = `
            <div style="overflow-y, auto;max-height:400px,width:100%">
                <table>
                    <tbody>
                        <tr>
                            <td>ID</td>
                            <td>:</td>
                            <td>${locationId}</td>
                        </tr>
                        <tr>
                            <td>Title</td>
                            <td>:</td>
                            <td>${title}</td>
                        </tr>
                        <tr>
                            <td>Description</tD>
                            <td>:</td>
                            <td>${description}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            `

            markerElement.addEventListener('click', (e) => {
                const locationId = e.toElement.id
                @this.findLocationById(locationId)
                }
            )

            const popUp = new mapboxgl.Popup({
                offset:25
            }).setHTML(content).setMaxWidth("400px")

            new mapboxgl.Marker(markerElement)
            .setLngLat(geometry.coordinates)
            .setPopup(popUp)
            .addTo(map)
        })
    }



    loadLocations({!! $geoJson !!})
    window.addEventListener('locationAdded', (e) => {
        loadLocations(JSON.pare(e.detail))
    })

    map.on('load', () => {
        map.addSource('bojonegoro', {
        type: 'geojson',
        // Use a URL for the value for the `data` property.
        data: 'geojson/bojonegoro.geojson'
        });

    map.addLayer({
    'id': 'bjn-layer',
    'type': 'fill',
    'source': 'bojonegoro', // reference the data source
    'layout': {},
    'paint': {
    'fill-color': '#90EE90', // blue color fill
    'fill-opacity': 0.5,
    }
    });

    map.addLayer({
    'id': 'bjn-outline',
    'type': 'line',
    'source': 'bojonegoro',
    'layout': {},
    'paint': {
    'line-color': '#000',
    'line-width': 3
    }
    });


    map.on('click', 'bjn-layer', (e) => {
        var kecamatan = e.features[0].properties.kecamatan;
        var populasi = e.features[0].properties.populasi;
        var area = e.features[0].properties.area;
    new mapboxgl.Popup()
    .setLngLat(e.lngLat)
    .setHTML("<table>" +
                    "<tr>" +
                        "<td>Kecamatan</td>" +
                        "<td>:</td>" +
                        "<td>"+kecamatan+"</td>" +
                    "</tr>" +
                    "<tr>" +
                        "<td>Populasi</td>" +
                        "<td>:</td>" +
                        "<td>"+populasi+"</td>" +
                    "</tr>" +
                    "<tr>" +
                        "<td>area</td>" +
                        "<td>:</td>" +
                        "<td>"+area+"</td>" +
                    "</tr>" +
                    "</table>"
                )
    .addTo(map);
    });



        map.on('mouseenter', 'bjn-layer', () => {
        map.getCanvas().style.cursor = 'pointer';
        });

        map.on('mouseleave', 'bjn-layer', () => {
        map.getCanvas().style.cursor = '';
        });

        map.addControl(new mapboxgl.NavigationControl());
        map.on('click', (e) =>{
            const longtitude = e.lngLat.lng
            const lattitude = e.lngLat.lat

            @this.long = longtitude
            @this.lat = lattitude

            console.log({longtitude, lattitude})
        });
    });
});

    </script>

@endpush
