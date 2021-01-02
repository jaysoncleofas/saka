@include('partials.header')
<div class="main-wrapper main-wrapper-1">
    @include('partials.navbar')

    @include('partials.sidebar')

    <div class="main-content" style="min-height: 874px;">
        @yield('content')
    </div>

    @yield('scripts')
@include('partials.footer')
