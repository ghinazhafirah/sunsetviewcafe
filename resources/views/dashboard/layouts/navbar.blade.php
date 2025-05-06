{{-- <!-- Content Start -->
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
    {{-- </nav> --}}

<!-- Content Start -->
<div class="content">

    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top px-4 py-0">
        <div class="container-fluid">
            <!-- Sidebar Toggle Button (Visible on Mobile) -->
            <button class="btn d-lg-none" id="sidebarToggle">
                <i class="bi bi-list text-warning" style="font-size: 24px;"></i>
            </button>

            <!-- Logout Button -->
            <div class="ms-auto">
                <form action="/logout" method="post">
                    @csrf
                    <button type="submit" class="btn btn-light border-0">
                        Logout <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </nav>



    <!-- Navbar Start -->
    {{-- <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top px-4 py-0">
        <div class="container-fluid">
            <!-- Toggle Button for Mobile -->
            <button class="btn d-lg-none" id="sidebarToggle">
                <i class="bi bi-list text-warning" style="font-size: 24px;"></i>
            </button>
            <!-- Logout Button (Align Right) -->
            <div class="ms-auto">
                <form action="/logout" method="post">
                    @csrf
                    <button type="submit" class="btn btn-light border-0">
                        Logout <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </nav> --}}





    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('sidebarToggle');
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    // toggle sidebar logic here
                });
            }
        });
    </script> --}}
