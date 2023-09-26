@include('system.header_layout')
@include('system.nav')
<? //include_once('model/hardwarelist_utility.php');?>
<div class="container-fluid">
        <h1 class="font-weight-bold text-center">【合管家】</h1>
        <h1 class="mb-2 font-weight-bold text-center">總覽</h1>
    <div class="row">
        <div class="col-xl-6 col-md-6 mb-4">
          	<div class="card border-right-danger shadow h-100 py-2">
                <div class="card-body">
                  	<div class="row no-gutters align-items-center">
	                    <div class="col mr-2">
	                      	<div class="mb-1 font-weight-bold text-gray-800 text-center">
	                        <h5 class='text-green'>宿舍硬體系統檢測</h5>
	                        <hr>
	                        <div class="alert alert-danger mb-0">
	                            <h4 class="mb-0 p-3 font-weight-bold"> 個NG</h4>
	                            <h4 class="mb-0 p-3 font-weight-bold"> 個Meter NG</h4>
	                            <h4 class="mb-0 p-3 font-weight-bold"> 個Device NG</h4>
	                        </div>
	                      	</div>
                    </div>
                  	</div>
                </div>
          	</div>
        </div>
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-right-info shadow h-100 py-2">
                <div class="card-body">
	                <div class="row no-gutters align-items-center">
	                    <div class="col mr-2">
	                      	<div class="mb-1 font-weight-bold text-gray-800 text-center">
	                        	<h5 class='text-green'>房間 & Log</h5>
	                        	<hr>
		                        <div class="alert alert-info mb-0">
		                            <h4 class="mb-0 p-3 font-weight-bold">房間總數</h4>
		                            <h4 class="mb-0 p-3 font-weight-bold">間</h4>
		                            <hr>
		                            <h4 class="mb-0 p-3 font-weight-bold">前台 Log</h4>
		                            <h4 class="mb-0 p-3 font-weight-bold">筆</h4>
		                        </div>
	                      	</div>
	                    </div>
	                </div>
                </div>
            </div>
        </div>
        <div class="col-xl-12 col-md-6 mb-4">
            <div class="card border-bottom-success border-top-success shadow h-100 py-2">
                <div class="card-body">
                  	<div class="no-gutters align-items-center">
	                    <div class="col mr-2">
	                      	<div class="mb-1 font-weight-bold text-gray-800 text-center">
		                        <h5 class='text-green'>名單資料</h5>
		                        <hr>
		                        <div class="row alert alert-success justify-content-center">
		                            <div class="col-xl-4 col-md-12">
		                                <h4 class="mb-0 p-3 font-weight-bold"> 位</h4>
		                                <h4 class="mb-0 p-3 font-weight-bold">AO管理員</h4>
		                                <hr class="d-xl-none">
		                            </div>
		                            <div class="col-xl-4 col-md-12">
		                                <h4 class="mb-0 p-3 font-weight-bold"> 位</h4>
		                                <h4 class="mb-0 p-3 font-weight-bold">客戶管理員</h4>
		                                <hr class="d-xl-none">
		                            </div>
		                        </div>
	                      	</div>
	                    </div>
                  	</div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('system.footer_layout')