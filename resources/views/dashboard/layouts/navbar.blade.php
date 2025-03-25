<!-- Content Start -->
<div class="content">
    <!-- Navbar Start -->
    <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
        <div class="navbar-nav align-items-center ms-auto">
            <form action="/logout" method="post">
                @csrf
                <button type="submit" class="nav-item bg-light border-0"> Logout <i
                        class="bi bi-box-arrow-right"></i></button>
            </form>
        </div>
    </nav>
