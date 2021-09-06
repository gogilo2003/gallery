@extends('admin::web.layout.main')

@section('content')
    <gallery></gallery>
@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset('vendor/gallery/css/frontend.css') }}">
@endpush