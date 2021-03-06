<nav class="navbar fixed-top navbar-expand-lg navbar-light bg-light" style="border-bottom: 1px solid rgb(232, 232, 225);">
    <div class="container">
        <a class="navbar-brand" href="{{ route('landing.index') }}">
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
                    <a class="nav-link" href="{{ route('landing.about') }}">About</a>
                </li>

                <li class="nav-item {{ Nav::isRoute('landing.rooms') }}{{ Nav::isRoute('room.show') }}">
                    <a class="nav-link" href="{{ route('landing.rooms') }}">Rooms</a>
                </li>

                <li class="nav-item {{ Nav::isRoute('landing.cottages') }}{{ Nav::isRoute('cottage.show') }}">
                    <a class="nav-link" href="{{ route('landing.cottages') }}">Cottages</a>
                </li>

                <li class="nav-item {{ Nav::isRoute('landing.contact') }}">
                    <a class="nav-link" href="{{ route('landing.contact') }}">Contact</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('landing.exclusive_rental') }}" class="nav-link btn btn-dark btn-sm radius-zero text-white">Exclusive Rental</a>
                </li>
            </ul>
        </div>
    </div>
</nav>