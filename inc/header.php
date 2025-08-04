 <header class="navbar navbar-expand-lg bg-light border-bottom shadow-sm" style="height: 8vh;">
     <div class="container-fluid px-4">
         <a class="navbar-brand d-flex align-items-center" href="./">
             <img src="./assets/favicon.png" alt="Logo" width="32" height="32" class="me-2 rounded">
             <span class="fs-5 fw-bold text-dark">Attendance Management</span>
         </a>

         <div class="dropdown ps-4 h-100">
             <a href="#" class="text-decoration-none d-flex flex-row gap-2 h-100 align-items-center" id="settingsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                 <img src="https://picz.dev/profile" class="rounded-circle border border-2 img-fluid" style="max-width: 32px; max-height: 32px;">
                 <div class="d-flex flex-column fs-6">
                     <span class="text-dark fw-bold text-uppercase" style="font-size: 0.7rem; max-width:120px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                         Jhon Doe</span>
                     <span class="text-secondary lh-1" style="font-size: 0.7rem;">Admin</span>
                 </div>
                 <i class="fa fa-chevron-down text-secondary ps-3" style="font-size: 0.7rem;"></i>
             </a>
             <ul class="dropdown-menu dropdown-menu-end text-small" aria-labelledby="settingsDropdown">
                 <li><a class="dropdown-item" href="#">Profile</a></li>
                 <li><a class="dropdown-item" href="#">Settings</a></li>
                 <li>
                     <hr class="dropdown-divider">
                 </li>
                 <li><a class="dropdown-item" href="#">Sign out</a></li>
             </ul>
         </div>
     </div>
 </header>
