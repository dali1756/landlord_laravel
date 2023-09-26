    <ul class="navbar-nav bg-gradient-darkblue sidebar sidebar-dark accordion toggled" id="accordionSidebar">
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('system/manage') }}">
            <div class="sidebar-brand-icon">
                <img src='img/logo2.png' class='h-auto w-100'>
            </div>
        </a>
        <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
        <li class="nav-item">
        <a class="nav-link" href="{{ url('system/manage') }}">
            <i class="fas fa-fw fa-home"></i>
            <span>總覽</span>
        </a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                <i class="fas fa-fw fa-user"></i>
                <span>名單群組管理</span>
            </a>
            <div id="collapseOne" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="new-member.php">單筆新增</a>
                    <a class="collapse-item" href="editmember.php">修改名單資料</a>
                </div>
            </div>
        </li>

        <hr class="sidebar-divider d-none d-md-block">

        <div class="sidebar-heading">
            後台功能
        </div>

        <li class="nav-item">
            <a class="nav-link" href="{{ url('system/log-frontdesk') }}">
                <i class="fas fa-fw fa-clipboard-list"></i>
                <span>Log紀錄</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ url('system/system-hardwarelist') }}">
                <i class="fas fa-fw fa-clipboard-list"></i>
                <span>硬體系統檢測</span>
            </a>
        </li>
        
        <li class="nav-item d-none">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFive" aria-expanded="true" aria-controls="collapseFive">
                <i class="fas fa-fw fa-database"></i>
                <span>系統現況管理</span>
            </a>
            <div id="collapseFive" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="system-hardwarelist.php">硬體系統檢測</a>
                </div>
            </div>
        </li>
    </ul>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
        <nav class="navbar navbar-expand navbar-light bg-gray topbar mb-4 static-top">
        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
        </button>
            <ul class="navbar-nav ml-auto">
              <div class="topbar-divider d-none d-sm-block"></div>
              <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">AO 管理員</span>
                    <img class="img-profile rounded-circle" src="img/logo.png">
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="{{ url('system/admin_logout') }}" data-target="#logoutModal">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-700"></i>
                        登出
                    </a>
                </div>
              </li>
            </ul>
        </nav>
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>
