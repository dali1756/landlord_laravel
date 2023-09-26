<header id="header">
    <nav class="left">
        <a href="#menu" class="for_mobile">
            <span class="glyphicon glyphicon-option-horizontal" style="font-size:42px"></span>
        </a> 
    </nav>
    <a style="color: #fff;" href="{{ url('') }}" class="logo">
        <img class="school_image" src="{{ asset('img/logo.png') }}">  
    </a>
    <nav class="right">
        @if (session('admin_user.id'))
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown no-arrow show">
                    <a class="nav-link dropdown-toggle button btn-orange" href="#" id="userDropdown" 
                    role="button"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="padding: 0 .5em;">
                        <div class="mar-nav">
                            <i class="fas fa-user fa-fw mr-2 text-white"></i>
                            <span class="mr-2 d-none d-lg-inline small">管理員設定</span>
                        </div> 
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow" aria-labelledby="userDropdown" style="min-width:160px; line-height:3;  top: 10px;">
                        <a class="dropdown-item text-green" href="{{ url('admin_edit') }}">
                            <i class="fas fa-cog fa-sm fa-fw mr-2"></i>
                            資料變更
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-green" href="{{ url('admin_logout') }}"  data-target="#logoutModal">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2"></i>
                            登出
                        </a>
                    </div>
                </li>
            </ul>
        @else
            <a href="#" onclick="$('#identity').show()" class="button alt btnfont-21">
                <div class="mar-nav">管理員登入</div>
            </a>
        @endif
    </nav>
</header>
<nav id="menu"> 
    <ul class="links">
        <li><a href="{{ url('') }}"><span class="fas fa-home"></span>首頁</a></li>
        <hr class="hr-style">   
        @if (session('admin_user.id'))
            <li><a href="manage"><span class="fas fa-cog"></span>管理中心</a></li>
            <hr class="hr-style">
        @endif
        <ul class="actions vertical">
            @if (session('admin_user.id'))
                <li><a href="{{ url('admin_edit') }}" class="button btn-orange col-12">資料變更</a></li>
                <li><a href="{{ url('admin_logout') }}" class="button btn-orange col-12">登出</a></li>
            @else
                <a href="#" onclick="$('#menu').removeClass('some-class');$('#identity2').hide();$('#identity').show()" class="button alt col-12">管理員登入</a>
            @endif
        </ul>
    </ul>
</nav>