@extends('toko.layout')

@section('main')
<img id="barcode" src="" alt="{{$kode}}">
@endsection

@section('script')
<script>
    JsBarcode('#barcode', '{{$kode}}');
</script>
@endsection