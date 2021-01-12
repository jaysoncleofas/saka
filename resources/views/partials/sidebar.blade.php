<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="index.html">{{ config('yourconfig.resort')->name }}</a>
        </div>

        <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">Saka</a>
        </div>

        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>

            <li class="{{ Nav::isRoute('dashboard.index') }}">
                <a class="nav-link" href="{{ route('dashboard.index') }}"><i class="fas fa-fire"></i> <span>Dashboard</span></a>
            </li>

            <li class="{{ Nav::isRoute('reservation.index') }}">
                <a class="nav-link" href="{{ route('reservation.index') }}"><i class="far fa-file-alt"></i> <span>Reservations</span></a>
            </li>

            @if (auth()->user()->role_id == 1)
            <li class="{{ Nav::isRoute('report.index') }}">
                <a class="nav-link" href="{{ route('report.index') }}"><i class="fas fa-file-invoice-dollar"></i> <span>Reports</span></a>
            </li>
            @endif

            <li class="menu-header">Manage</li>
            <li class="{{ Nav::hasSegment('transactions') . Nav::hasSegment('transaction') }}">
                <a class="nav-link" href="{{ route('transaction.index') }}"><i class="fas fa-list"></i> <span>Transactions</span></a>
            </li>
            
            <li class="{{ Nav::hasSegment('guests') . Nav::hasSegment('guest') }}">
                <a class="nav-link" href="{{ route('guest.index') }}"><i class="fas fa-users"></i> <span>Guests</span></a>
            </li>
            
            @if (auth()->user()->role_id == 1)
            <li class="{{ Nav::hasSegment('all-cottages') . Nav::hasSegment('cottage') }}">
                <a class="nav-link" href="{{ route('cottage.index') }}"><i class="fas fa-home"></i> <span>Cottages</span></a>
            </li>
            
            <li class="{{ Nav::hasSegment('all-rooms') . Nav::hasSegment('room') }}">
                <a class="nav-link" href="{{ route('room.index') }}"><i class="fas fa-th"></i> <span>Rooms</span></a>
            </li>
            
            <li class="{{ Nav::hasSegment('users') . Nav::hasSegment('user') }}">
                <a class="nav-link" href="{{ route('user.index') }}"><i class="fas fa-users"></i> <span>Users</span></a>
            </li>

            <li class="{{ Nav::hasSegment('settings') }}">
                <a class="nav-link" href="{{ route('setting.index') }}"><i class="fas fa-cogs"></i> <span>Settings</span></a>
            </li>
            @endif

        </ul>

        <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
            <a href="{{ route('transaction.create') }}" class="btn btn-primary btn-lg btn-block btn-icon-split">
                <i class="fas fa-rocket"></i> Add Transaction
            </a>
        </div>
    </aside>
</div>
