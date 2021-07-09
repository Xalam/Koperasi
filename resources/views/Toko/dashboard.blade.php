@extends('toko.layout')

@section('main')
<div class="m-6">
    <div class="d-flex flex-row flex-wrap mb-4">
        <div class="card col-md-3 mb-2" style="height: 96px;">
            <div class="card-body d-flex flex-row">

            </div>
        </div>
        <div class="card col-md-4 mb-2 offset-md-1" style="height: 96px;">
            <div class="card-body d-flex flex-row">

            </div>
        </div>
        <div class="card col-md-3 mb-2 offset-md-1" style="height: 96px;">
            <div class="card-body d-flex flex-row">

            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body d-flex flex-row flex-wrap">
            <div class="col-md-6">
                <h6 style="text-align: center;">Penjualan Tahun {{$year}}</h6>
                <canvas id="chartPenjualan"></canvas>
            </div>
            <div class="col-md-6">
                <h6 style="text-align: center;">Laba Tahun {{$year}}</h6>
                <canvas id="chartLaba"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
var ctxPenjualan = document.getElementById('chartPenjualan').getContext('2d');
var chartPenjualan = new Chart(ctxPenjualan, {
    type: 'bar',
    data: {
        labels: {!!json_encode($chartPenjualan->labels) !!},
        datasets: [{
            label: 'Penjualan',
            data: {!!json_encode($chartPenjualan->dataset) !!},
            backgroundColor: {!!json_encode($chartPenjualan->colours) !!},
            borderColor: {!!json_encode($chartPenjualan->borders) !!},
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});

var ctxLaba = document.getElementById('chartLaba').getContext('2d');
var chartLaba = new Chart(ctxLaba, {
    type: 'bar',
    data: {
        labels: {!!json_encode($chartLaba->labels) !!},
        datasets: [{
            label: 'Laba',
            data: {!!json_encode($chartLaba->dataset) !!},
            backgroundColor: {!!json_encode($chartLaba->colours) !!},
            borderColor: {!!json_encode($chartLaba->borders) !!},
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});
</script>
@endsection