<!-- Sidebar Desktop -->
<div id="sidebar" class="sidebar pe-4 pb-3 bg-warning d-none d-lg-block position-fixed top-0 start-0 h-100"
    style="background-color: orange; width: 250px; z-index: 1040;">
    <nav class="navbar navbar-light">
        <div class="navbar-brand mx-3 mb-3">
            <img src="{{ asset('img/' . $image) }}" alt="" width="200">
            <h3 class="text-black">Sunset View Cafe</h3>
        </div>
        <div class="navbar-nav w-100">
            {{-- <a href="/dashboard" class="nav-item nav-link {{ Request::is('dashboard') ? 'active' : '' }}">
                <i class="bi bi-shop-window"></i> Dashboard</a> --}}
            <a href="/dashboard"
                class="nav-item nav-link {{ Request::is('dashboard') || (Request::is('dashboard/*') && !Request::is('dashboard/posts*') && !Request::is('dashboard/qr*') && !Request::is('dashboard/generate-qr*')) ? 'active' : '' }}"><i
                    class="bi bi-shop-window"></i> Dashboard</a>
            <a href="/dashboard/posts" class="nav-item nav-link {{ Request::is('dashboard/posts*') ? 'active' : '' }}">
                <i class="bi bi-journals"></i> Manajemen Menu</a>
            <a href="/dashboard/qr"
                class="nav-item nav-link {{ Request::is(['dashboard/qr*', 'dashboard/generate-qr']) ? 'active' : '' }}">
                <i class="bi bi-qr-code"></i> QR Code</a>
        </div>
    </nav>
</div>

<!-- Sidebar Mobile (Hidden by default) -->
<div id="mobileSidebar" class="mobile-sidebar-style bg-warning position-fixed top-0 start-0 h-100 d-lg-none"
    style="width: 200px; transform: translateX(-100%); transition: transform 0.3s ease; z-index: 1040;">
    <nav class="navbar navbar-light">
        <div class="navbar-brand mx-3 mb-3">
            <img src="{{ asset('img/' . $image) }}" alt="" width="150">
            <h4 class="text-black">Sunset View Cafe</h4>
        </div>
        <div class="navbar-nav w-100">
            {{-- Tempelkan sementara di sidebar --}}
            {{-- <p>{{ Request::path('dashboard') }}</p> --}}

            <a href="/dashboard"
                class="nav-item nav-link {{ Request::is('dashboard') || (Request::is('dashboard/*') && !Request::is('dashboard/posts*') && !Request::is('dashboard/qr*') && !Request::is('dashboard/generate-qr*')) ? 'active' : '' }}"><i
                    class="bi bi-shop-window"></i> Dashboard</a>    
            <a href="/dashboard/posts" class="nav-item nav-link {{ Request::is('dashboard/posts*') ? 'active' : '' }}">
                <i class="bi bi-journals"></i> Manajemen Menu</a>
            <a href="/dashboard/qr"
                class="nav-item nav-link {{ Request::is(['dashboard/qr*', 'dashboard/generate-qr']) ? 'active' : '' }}">
                <i class="bi bi-qr-code"></i> QR Code</a>
        </div>
    </nav>
</div>

<!-- Overlay -->
<div id="overlay" class="position-fixed top-0 start-0 w-100 h-100 d-none"
    style="background-color: rgba(255, 255, 255, 0.05); backdrop-filter: blur(2px); z-index:1030;"></div>
