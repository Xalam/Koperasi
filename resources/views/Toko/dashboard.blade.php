@extends('toko.layout')

@section('style')
<style>
.summary-icon {
    border-radius: 50%;
    border: 0px;
}
</style>
@endsection

@section('main')
<div class="m-6">
    <div class="d-flex flex-row flex-wrap mb-4">
        <div class="card mb-2 vw-100">
            <div class="bg-white card-header" style="height: 36px;">
                <p><b>SUMMARY</b></p>
            </div>
            <div class="bg-white card-body d-flex flex-row flex-wrap">
                <div class="col-lg-4 align-self-center d-flex flex-row flex-wrap mb-2 mt-2">
                    <div class="align-self-center">
                        <i class="btn-success text-white fab fa-sellsy fa-2x summary-icon"></i>
                    </div>
                    <div class="ms-3 align">
                        <b class="text-gray-600">Penjualan</b>
                        @if (isset($total_penjualan->total))
                        <h5 class="fw-bolder">{{$total_penjualan->total}}</h5>
                        @else
                        <h5 class="fw-bolder">0</h5>
                        @endif
                    </div>
                </div>
                <div class="col-lg-4 align-self-center d-flex flex-row flex-wrap mb-2 mt-2">
                    <div class="align-self-center">
                        <i class="btn-warning text-white fas fa-shopping-cart fa-2x summary-icon"></i>
                    </div>
                    <div class="ms-3">
                        <b class="text-gray-600">Pembelian</b>
                        @if (isset($total_pembelian->total))
                        <h5 class="fw-bolder">{{$total_pembelian->total}}</h5>
                        @else
                        <h5 class="fw-bolder">0</h3>
                        @endif
                    </div>
                </div>
                <div class="col-lg-4 align-self-center d-flex flex-row flex-wrap mb-2 mt-2">
                    <div class="align-self-center">
                        <i class="btn-info fas fa-coins text-white fa-2x summary-icon"></i>
                    </div>
                    <div class="ms-3">
                        <b class="text-gray-600">Laba</b>
                        @if (isset($total_pendapatan))
                        @php
                        $pendapatan = number_format($total_pendapatan, 2, ',', '.');
                        @endphp
                        <h5 class="fw-bolder">Rp. {{$pendapatan}}</h3>
                        @else
                        <h5 class="fw-bolder">Rp. 0,-</h5>
                        @endif
                    </div>
                </div>
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