@include('partials.header')
    @include('partials.navbar')

    @include('partials.sidebar')

    <div class="main-content" style="min-height: 874px;">
        @yield('content')
    </div>

    @yield('scripts')
@include('partials.footer')
