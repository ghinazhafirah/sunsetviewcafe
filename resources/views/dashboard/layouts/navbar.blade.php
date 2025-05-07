<div class="content">
<!-- Navbar -->
<nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
    <!-- Mobile Toggle Sidebar Button -->
    <button class="btn d-lg-none me-2" id="sidebarToggle">
        <i class="bi bi-list text-warning" style="font-size: 24px;"></i>
    </button>

    <!-- Right Side Logout -->
    <div class="navbar-nav align-items-center ms-auto">
        <form action="/logout" method="post">
            @csrf
            <button type="submit" class="nav-item bg-light border-0"> Logout <i
                    class="bi bi-box-arrow-right"></i></button>
        </form>
    </div>
</nav>
