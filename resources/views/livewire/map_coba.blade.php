<div>
    <style>
        .mapboxgl-popup {
        max-width: 400px;
        font: 12px/20px 'Helvetica Neue', Arial, Helvetica, sans-serif;
        }
    </style>
    <div class="container">
        <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-dark text-white">
                            <h3>Data Fasilitas Kesehatan Kabupaten Bojonegoro<a href="{{route('map.create')}}" class="btn btn-light" style="float: right;"><b>Tambah</b></a></h3>
                        </div>
                        <div class="card-body">
                            <div wire:ignore id='map' style='width: 100%; height: 80vh;'></div>
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

    map.on('load', () => {
            map.addSource('polygon', {
            type: 'geojson',
            // Use a URL for the value for the `data` property.
            data: 'geojson/bojonegoro.geojson'
            });

        map.addLayer({
        'id': 'bjn-layer',
        'type': 'fill',
        'source': 'polygon', // reference the data source
        'layout': {},
        'paint': {
        'fill-color': '#90EE90', // blue color fill
        'fill-opacity': 0.5,
        'fill-outline-color': '#000',
        }
        });
    });

    map.on('load', () => {
        map.addSource('bojonegoro', {
        type: 'geojson',
        // Use a URL for the value for the `data` property.
        data: 'geojson/puskesmas.geojson'
        });

        map.addLayer({
        'id': 'point',
        'type': 'circle',
        'source': 'bojonegoro',
        'paint': {
        'circle-radius': 10,
        'circle-color': '#F84C4C' // red color
        }
        });
    });

     map.on('click', 'point', (e) => {
        var jenis = e.features[0].properties.jenis;
        var faskes = e.features[0].properties.faskes;
    new mapboxgl.Popup()
    .setLngLat(e.lngLat)
    .setHTML("<table>" +
                    "<tr>" +
                        "<td>Jenis</td>" +
                        "<td>:</td>" +
                        "<td>"+jenis+"</td>" +
                    "</tr>" +
                    "<tr>" +
                        "<td>Nama</td>" +
                        "<td>:</td>" +
                        "<td>"+faskes+"</td>" +
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
});

    </script>

@endpush
