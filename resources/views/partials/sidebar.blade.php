<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="index.html">Saka Resort</a>
        </div>

        <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">Saka</a>
        </div>

        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>

            <li class="{{ Nav::isRoute('dashboard') }}">
                <a class="nav-link" href="{{ route('dashboard') }}"><i class="fas fa-fire"></i> <span>Dashboard</span></a>
            </li>

            <li>
                <a class="nav-link" href="credits.html"><i class="far fa-file-alt"></i> <span>Reservations</span></a>
            </li>

            <li class="menu-header">Manage</li>

            <li>
                <a class="nav-link" href="credits.html"><i class="fas fa-users"></i> <span>Clients</span></a>
            </li>

            <li>
                <a class="nav-link" href="credits.html"><i class="fas fa-home"></i> <span>Cottages</span></a>
            </li>
            
            <li>
                <a class="nav-link" href="credits.html"><i class="fas fa-th"></i> <span>Rooms</span></a>
            </li>
            
            <li>
                <a class="nav-link" href="credits.html"><i class="fas fa-users"></i> <span>Users</span></a>
            </li>
        </ul>

        <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
            <a href="https://getstisla.com/docs" class="btn btn-primary btn-lg btn-block btn-icon-split">
                <i class="fas fa-rocket"></i> Add Visitors
            </a>
        </div>
    </aside>
</div>
