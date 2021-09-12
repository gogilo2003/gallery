@extends('admin::layout.main')

@section('title')
    Photo Gallery
@endsection

@section('page_title')
    <i class="fa fa-list-alt"></i> Manage your gallery
@endsection

@section('breadcrumbs')
    @parent
    <li class="active"><span><i class="fa fa-list-alt"></i> Photo Gallery</span></li>
@endsection

@section('sidebar')
    @parent
    {{-- @include('admin::some-additional sidebar items') --}}
@endsection

@section('content')
    <dashboard></dashboard>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('vendor/gallery/css/gallery.css') }}">
@endsection

@section('scripts_bottom')
    <script src="{{ asset('vendor/gallery/js/gallery.js') }}"></script>
@endsection

@push('scripts_top')
    <script>
        const api_token = "{{ api_token() }}"
    </script>
@endpush
