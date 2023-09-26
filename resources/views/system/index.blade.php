@include('system.header_layout')
<body class="bg-gradient-primary">
	<div class="container">
		 <div class="row justify-content-center">
		    <div class="col-xl-5 col-lg-6 col-md-9">
		        <div class="card o-hidden border-0 shadow-lg my-5">
		        	<div class="card-body p-0">
			            <div class="row">
			            	<div class="col-lg-12">
			                	<div class="p-5">
			                 	<div class="text-center">
			                    <p class=""><img width="200" src="img/logo.png" alt="test"></p>
			                    <h1 class="h4 text-gray-900 mb-4">【合管家】<br>智慧管理後台</h1>
				                </div>
				                <form class="mform" action="{{ url('system/admin_login') }}" method='post'>
				                	@csrf
				                  	<div class="form-group form-inline input-group-lg">
				                    	<label class="col-sm-2 col-form-label label-center"><i class="fas fa-user mr-4 text-green"></i></label>
				                    	<input type="text" class="form-control col-sm-10" name="username" aria-describedby="emailHelp" placeholder="帳號">
				                  	</div>
				                    <div class="form-group form-inline input-group-lg">
				                    	<label class="col-sm-2 col-form-label label-center"><i class="fas fa-unlock-alt mr-4 text-green"></i></label>
				                    <input type="password" class="form-control col-sm-10" name="pwd" placeholder="密碼">
				                 	</div>
									<input type='submit' class='mt-4 btn btn btnfont-30 text-white btn-primary2 btn-user col' value='登入'>
				                </form>
			              		</div>
			            	</div>
			            </div>
		        	</div>
		        </div>
		    </div>
		</div>
	</div>
</body>
@if ($errors->any())
    <script>
        alert('{{ $errors->first('error') }}');
    </script>
@endif