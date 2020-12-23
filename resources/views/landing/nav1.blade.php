<nav class="navbar fixed-top navbar-expand-lg navbar-light scrolling-navbar nav1">
    <div class="container">
        <a class="navbar-brand" href="#">
            <img src="{{ asset('/pics/A.png') }}" width="50" height="50" class="d-inline-block align-top mr-2" alt="">
            {{ config('yourconfig.resort')->name }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item {{ Nav::isRoute('landing.index') }}">
                    <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
                </li>

                <li class="nav-item {{ Nav::isRoute('landing.about') }}">
                    <a class="nav-link" href="about">About</a>
                </li>

                <li class="nav-item {{ Nav::isRoute('landing.rooms') }}">
                    <a class="nav-link" href="rooms">Rooms</a>
                </li>

                <li class="nav-item {{ Nav::isRoute('landing.cottages') }}">
                    <a class="nav-link" href="{{ route('landing.cottages') }}">Cottages</a>
                </li>

                <li class="nav-item {{ Nav::isRoute('landing.contact') }}">
                    <a class="nav-link" href="contact">Contact</a>
                </li>
            </ul>
        </div>

    </div>
</nav>