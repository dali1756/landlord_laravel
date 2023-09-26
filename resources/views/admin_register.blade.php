<!DOCTYPE html>
<html>
    <head>
        <title>合管家</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="Shortcut icon" href="{{ asset('static/img/favicon.ico') }}" />
        <!--bootstrap-select -->
        <link rel="stylesheet" href="{{ asset('static/bootstrap-select/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('static/bootstrap-select/css/bootstrap-select.css') }}">

        <!-- RWD link -->
        <link href="{{ asset('static/css/main.css') }}" rel="stylesheet">
        <!-- fontawesom -->
        <link href="{{ asset('static/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('static/css/sb-admin-2.min.css') }}" rel="stylesheet">
        <!-- sweetalert -->
        <link rel="stylesheet" href="{{ asset('static/plugins/sweetalert/sweetalert.min.css') }}">  
        <!-- 調整用CSS -->
        <link href="{{ asset('static/css/style.css') }}" rel="stylesheet">

        <script src="{{ asset('static/js/jquery-3.6.1.min.js') }}" crossorigin="anonymous"></script>
    </head>
    <body>    
    <link href="{{ asset('static/css/register.css') }}" rel="stylesheet">
    <header id='header'>
        <a href="{{ url('') }}" class='logo'>
            <img class='school_image' src='{{ asset("assets/image/logo.png") }}' alt='LOGO位置'>
        </a>
    </header>
    <div class="container-fluid">
        <h1 class='my-4 text-center text-primary'>房東註冊</h1>
        <div class="row register">
            <div class="col-12">
                <form id='mform' action="admin_register" method="post">
                @csrf
                    <div class="row justify-content-center">
                        <div class="col-lg-2">
                            <label class="col-form-label label-left">帳號</label>
                            <input required maxlength='10' type="text" class="form-control" name="username" placeholder="">  
                        </div>
                        <div class="col-lg-2">
                            <label class="col-form-label label-left">密碼</label>
                            <input required maxlength='10' type="password" class="form-control" name="password" 
                                placeholder="請輸入4~8碼英數混合" 
                                inputmode="text" pattern="^(?![0-9]+$)(?![A-Za-z]+$)[0-9A-Za-z]{4,8}$"
                            >  
                        </div>
                        <div class="col-lg-3">
                            <label class="col-form-label label-left">姓名</label>
                            <input required type="text" class="form-control" name="cname" placeholder="">  
                        </div>
                        <div class="col-lg-4">
                            <label class="col-form-label label-left">e-mail</label>
                            <input required type="email" class="form-control" name="email" placeholder="請輸入信箱" />
                        </div>
                    </div>
    
                    <div id="add-machine" class="row">
                        <div class="col-11 mt-4">
                            <label class="label-left">裝置註冊</label>
                            <h4 class='text-primary'></h4>
                            <a id="add-item" class="btn-success btn-circle" data-toggle='tooltip' data-placement='bottom' title='添加'>
                                <i class="fas fa-plus"></i>
                            </a>
                        </div>
                            <div class="col-md-6 col-lg-4">
                                <input required type="text" class="form-control" name="machine[]" placeholder="請輸入序號" />
                            </div>
                    </div>
                    <div class="text-center">
                        <button id="post-btn" type="submit" class="btn btn-green2 btn-primary2">確認送出</button>
                    </div>
                </form>
            </div>
            <div class="col-auto ml-auto">
                <img class="w-100" src="{{ asset('static/img/房客資料建立插圖.png') }}" alt="資料建立插圖">
            </div>
        </div>
    </div>
    <!-- JS放置區 -->
    <!-- 側選單特效控制、tooltip -->
    <!-- <script src="./static/js/skel.min.js"></script> -->
    <!-- 影響bootstrap-select JS -->
    <!-- <script src="./static/bootstrap-select/js/bootstrap-select.js"></script>
    <script src="./static/js/bootstrap.min.js"></script>
    <script src="./static/js/public.js"></script> -->
    <script src="{{ asset('static/plugins/sweetalert/sweetalert.min.js') }}"></script>
    <script>
        let btn = ['#add-item', '#post-btn'];
        const form_id = ['form:eq(0)'];
        let showArea = $('#add-machine');
        let content = `
        <div class="col-md-6 col-lg-4">
            <input required type="text" class="form-control" name="machine[]" placeholder="請輸入序號..." />
            <a class="btn-orange btn-circle del-item" onclick="delItem(this)"
            data-toggle='tooltip' data-placement='bottom' title='添加'>
                <i class="fas fa-minus"></i>
            </a>
        </div> `;

        // add-item
        $(btn[0]).on('click', function(){
            showArea.append(content);
        });
        // del-item
        function delItem(element){
            $(element).parent().remove();
        }
    </script>
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <script>swal("錯誤!", "{{ addslashes($error) }}", "error");</script>
        @endforeach
    @endif
@include('footer_layout')