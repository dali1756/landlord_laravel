@include('header_layout')
@include('nav')
<div id="banner" class="container-fuild">
    <div id="login" class="row justify-content-center">
        <form id="login-form" action="admin_forget" method="post">
            @csrf
            <h1 class="mb-4">忘記密碼</h1>
            @if ($errors->any())
                <div class="col-12 row container-fluid mar-bot50 mar-center2">
                    <div style="margin: 0 auto; text-align: center;  width: 600px;" class="alert alert-success" role="alert">
                        @foreach ($errors->all() as $error)
                            <strong>{{ $error }}<br></strong> 
                        @endforeach
                    </div>
                </div>
            @endif
            <div class="form-group row">
                <label for="user" class="col-md-2">
                    <i class="fas fa-user"></i>帳號
                </label>
                <div class="col-md-10">
                    <input class="form-control" type="text" name="user" placeholder="請輸入帳號" required>
                </div>
                <label for="user" class="col-md-2">
                    <i class="fas fa-envelope"></i>Email
                </label>
                <div class="col-md-10">
                    <input class="form-control" type="text" name="mail" placeholder="請輸入email" required>
                </div>
            </div>
            <div class="col-lg-12">
                <button type="submit" id="btn_adminlogin" class="btn btn-loginfont btn-primary2 btn-user col-lg-12">確認送出 </button>
            </div>
        </form>
    </div>
</div>
@include('footer_layout')
<script>
    $(document).ready(function() {
        $('#banner').css("background-image", "url(img/bk2.jpg)");
        // 底圖 -- 20200227
        $('.button.alt').click(function() {
            if($('#identity').css('display') == 'block') {
                $('#banner').css("background-image", "url(img/bk2.jpg)");
                $('h1.title').css("display", "none");
            } else {
                $('#banner').css("background-image", "url(img/bk.jpg)");
            }
        });
        $('.for_mobile').click(function() {
            $('#identity').hide();
        });
    });
</script>