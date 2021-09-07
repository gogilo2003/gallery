@extends('admin::web.layout.main')

@section('content')
    <div class="container">
        <gallery wrapper=".container" album-wrapper=".row>.col-md-12>.nav.nav-tabs.content-tabs"></gallery>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('vendor/gallery/css/front.css') }}">
@endpush

@push('scripts_bottom')
    <script src="{{ asset('vendor/gallery/js/front.js') }}"></script>
@endpush
