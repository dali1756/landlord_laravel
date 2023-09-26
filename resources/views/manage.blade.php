@include('header_layout')
@include('nav')
<section id="main" class="wrapper">
	<div class="rwd-box"></div><br><br><br>
	<div class="container" style="text-align: center;">
	<h1 class=" jumbotron-heading">管理中心</h1>
	</div>
	<div class="container-fluid">       
		<div class="row justify-content-between">
			<div class="col-lg-2 col-sm-6 p-4 text-center"> 
				<a  href="power-record">
				<img class="mb-3" src="img/電力使用紀錄.png">
				<h4>電力使用紀錄</h4> </a> 
			</div>
			<div class="col-lg-2 col-sm-6 col-xs-12 p-4 text-center">
				<a  href="power-nowmeter">
				<img class="mb-3" src="img/用電現況.png">
				<h4>用電現況</h4> </a> 
			</div>
			<div class="col-lg-2 col-sm-6 col-xs-12 p-4 text-center">
				<a  href="power-consumption-d">
				<img class="mb-3" src="img/耗電查詢.png">
				<h4>電量統計</h4> </a> 
			</div>
			<div class="col-lg-2 col-sm-6 p-4 text-center">
				<a href="rate">
				<img class="mb-3" src="img/費率設定.png">
				<h4>費率設定</h4> </a>
			</div>
			<div class="col-lg-2 col-sm-6 p-4 text-center">
				<a href="power-switch">
				<img class="mb-3" src="img/開關電設定.png">
				<h4>開關電設定</h4> </a>
			</div>
			<div class="col-lg-2 col-sm-6 p-4 text-center">
				<a href="device">
				<img class="mb-3" src="img/裝置設定.png">
				<h4>裝置設定</h4> </a>
			</div>
		</div>
	</div>
</section>
@include('footer_layout')