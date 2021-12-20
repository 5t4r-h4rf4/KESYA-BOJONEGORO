@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                <h3>Data Fasilitas Kesehatan Kabupaten Bojonegoro</h3>
                </div>
                <div class="card-body">
                    <div wire:ignore id='map' style='width: 100%; height: 80vh;'></div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
