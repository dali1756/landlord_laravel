@include('header_layout')
@include('nav')
<section id="main" class="wrapper">
<div class="col-12 btn-back"><a href="manage" ><i class="fas fa-chevron-circle-left fa-3x"></i><label class='previous'></label></a></div>
	@if ($errors->any())
		<div class="col-12 row container-fluid mar-bot50 mar-center2">
		    <div style="margin: 0 auto; text-align: center;  width: 600px;" class="alert alert-success" role="alert">
	            @foreach ($errors->all() as $error)
	                <strong>{{ $error }}<br></strong> 
	            @endforeach
		    </div>
		</div>
	@elseif (session('success'))
		<div class="col-12 row container-fluid mar-bot50 mar-center2">
		    <div style="margin: 0 auto; text-align: center;  width: 600px;" class="alert alert-success" role="alert">
	            {{ session('success') }}
		    </div>
		</div>
	@endif
	<div class="inner inner2">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<h1 class="jumbotron-heading text-center">密碼變更</h1>
					<form id='mform' action="admin_edit" method="post">
						@csrf
						<div class="form-group row">
							<label for="exampleFormControlInput1" class="col-sm-2 col-form-label label-right">帳號</label>
    						<div class="col-sm-9"> 
								<input  type="text" class="form-control col" name="account" id="account" value="{{ session('admin_user.username') }}" readonly>
    						</div>
						</div>
						<div class="form-group row">
							<label for="exampleFormControlInput1" class="col-sm-2 col-form-label label-right">您的舊密碼</label>
    						<div class="col-sm-9"> 
								<input  type="password"  class="form-control  col" name="old_pwd" id="old_pwd" placeholder="" >
    						</div>
						</div>

  						<div class="form-group row">
							<label for="exampleFormControlInput1" class="col-sm-2 col-form-label label-right">您的新密碼</label>
    						<div class="col-sm-9">
								  <input type="password"  class="form-control" name="new_pwd" id="new_pwd" title="請輸入4~8碼"  placeholder="請輸入4~8碼" inputmode="numeric"  >  
    						</div>
						</div>

						<div class="form-group row">
							<label for="exampleFormControlInput1" class="col-sm-2 col-form-label label-right">確認新密碼</label>
    						<div class="col-sm-9">
								  <input type="password"  class="form-control" name="new_pwd_check" id="new_pwd_check"  title="請輸入4~8碼"  placeholder="請輸入4~8碼" inputmode="numeric"  >  
    						</div>
						</div><br>
					    <button type="submit" onclick="return confirm('確認修改?')" class="btn  btn-loginfont btn-primary2  col-sm-4 offset-sm-4 mb-4">確認修改</button>
					</form>

				</div>
			</div>
		</div>
	</div>
</section>
@include('footer_layout')
<style>
.table1>tbody>tr>td{
	text-align: right;
    vertical-align: middle;
}
</style>
<script>
$(document).ready(function(){
	$('#mform').submit(function(event) {
		if($('#new_pwd').val() != $('#new_pwd_check').val()) {
			alert("新密碼與確認新密碼不一致");
			return false;
		};
	});
});
</script>

<script>	
	if($('#main').height() > 446) {
		$('#footer').css({'position' : 'fixed'});
		$('#footer').css({'height' : 'auto'});
		$('#footer').css({'padding' : '10px 0'});
	}
</script>
