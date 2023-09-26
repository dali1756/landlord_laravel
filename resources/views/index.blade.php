@include('header_layout')
@include('nav')
	<section id='banner' class='col-lg-12 '>
		<div>
			<h1 class="title">
				房東智慧計電系統
			</h1>
		</div>
		<div id='identity' class='col-lg-12 col-md-9' >
			<form id='adminlogin' action='admin_login' method='post' class='col-lg-12'>
				@csrf
				<h1 class='h4 mb-4'>管理員登入</h1>
				<div class='login form'>
					<div class='div_column'>
						<span class='user col-lg-12'></span>
					</div>
					<div class='div_column' >帳號&nbsp;</div>
					<div class='div_column' style='width:300px;'>
						<input class='form-control' type='text' name='username' placeholder='帳號' id='example-text-input'>
					</div>
				</div>
				<div class='login form'>
					<div class='div_column'>
						<span class='lock col-lg-12'></span>
					</div>
					<div class='div_column'>密碼&nbsp;</div>
					<div class='div_column' style='width:300px;'>
						<input class='form-control' type='password' name='pwd' placeholder='密碼' id='example-search-input'>
					</div>
					<div style="margin: -15px 25px 0 25px; display: flex; justify-content: space-between;">
					    <a href="admin_register">註冊</a>
					    <a href="admin_forget">忘記密碼</a>
					</div>
				</div>
				<div class='login form  col-lg-12'>
					<button type ='submit' class='btn btn-loginfont btn-primary2 btn-user col-lg-12'>登入</button>
				</div>
			</form>
		</div>
	</section>
@include('footer_layout')
<script>
	$(document).ready(function() {
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
@if ($errors->any())
    <script>
        alert(' {{ $errors->first('error') }}');
        $('#banner').css("background-image", "url(img/bk2.jpg)");
		$('h1.title').css("display", "none");
        document.getElementById('identity').style.display = 'block';
    </script>
@endif