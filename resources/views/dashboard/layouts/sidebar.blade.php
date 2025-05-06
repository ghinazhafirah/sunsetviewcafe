 <!-- Sidebar Start -->
 <div id="sidebar" class="sidebar pe-4 pb-3"
     style="background-color: orange; min-height: 100vh; transition: width 0.3s;">
     <nav class="navbar bg-ligth navbar-light">

         {{-- Logo & Title --}}
         <div class="navbar-brand mx-3 mb-3">
             <img src="{{ asset('img/' . $image) }}" alt="" width="200">
             <h3 class="text- black primary"></i>Sunset View Cafe</h3>
         </div>

         {{-- NAV ITEM --}}
         <div class="navbar-nav w-100">
             <a href="/dashboard" class="nav-item nav-link {{ Request::is('dashboard') ? 'active' : '' }}"><i
                     class="bi bi-shop-window"></i> Dashboard</a>
             {{-- <div class="nav-item dropdown"> --}}
             <a href="/dashboard/posts"
                 class="nav-item nav-link {{ Request::is('dashboard/posts*') ? 'active' : '' }}"><i
                     class="bi bi-journals"></i> Manajemen Menu</a>
             {{-- <div class="nav-item dropdown"> --}}
             <a href="/dashboard/qr"
                 class="nav-item nav-link {{ Request::is(['dashboard/qr*', 'dashboard/generate-qr']) ? 'active' : '' }}">
                 <i class="bi bi-qr-code"></i> QR Code</a>

         </div>
     </nav>
 </div>

 <!-- Navbar for Mobile -->
 <nav class="navbar navbar-expand-lg navbar-light bg-light d-lg-none fixed-top">
     <div class="container-fluid">
         <!-- Toggle Button -->
         <button class="btn" id="sidebarToggle"><i class="bi bi-list text-warning"
                 style="font-size: 24px;"></i></button>
         <form action="/logout" method="post" class="ms-auto">
             @csrf
             <button type="submit" class="btn btn-light border-0">Logout <i class="bi bi-box-arrow-right"></i></button>
         </form>
     </div>
 </nav>

 <script>
     document.getElementById('sidebarToggle').addEventListener('click', function() {
         var sidebar = document.getElementById('sidebar');
         sidebar.style.width = sidebar.style.width === '0px' ? '18.4%' : '0px';
     });
 </script>
 <!-- Sidebar End -->
