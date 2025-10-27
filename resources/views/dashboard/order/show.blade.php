@extends('dashboard.layouts.main')

@section('container')
    <h1>Detail Pesanan</h1>
    <p>{{ $order->customer_name }}</p>
@endsection
