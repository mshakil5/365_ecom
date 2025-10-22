<div class="user-sidebar card shadow-sm mb-4">
    <div class="card-body user-sidebar-header text-center">
        <h5 class="user-name">{{ Auth::user()->name ?? 'John Doe' }}</h5>
        <small class="user-email d-block">{{ Auth::user()->email ?? 'johndoe@email.com' }}</small>
    </div>

    <ul class="list-group list-group-flush user-sidebar-menu">
        <li class="list-group-item">
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>
        <li class="list-group-item">
            <a href="{{ route('logout') }}" class="d-flex align-items-center sidebar-link text-danger"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li>
    </ul>
</div>