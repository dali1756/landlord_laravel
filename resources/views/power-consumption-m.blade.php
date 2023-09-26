@include('header_layout')
@include('nav')
<section id="main" class="wrapper">
	<div class='col-12 btn-back'><a href='manage' ><i class="fas fa-chevron-circle-left fa-3x"></i><label class='previous'></label></a></div>
	<div class="rwd-box"></div><br><br>
	<div class="container" style="text-align: center;">
		<h1 class="jumbotron-heading text-center">電量統計</h1>
    </div>
    @if(!empty($error))
	<div class="row mar-center2 mb-4" style="margin: 0 auto;">
		<div style="margin: 0 auto; text-align: center;" class="alert alert-danger col-lg-9" role="alert">
			<strong>{{ $error }}</strong>
		</div>	
	</div>
	@endif
	<div class="container">
		<ul class='nav nav-tabs nav-border'>
			<li class='nav-item'> 
				<a class='nav-link card card-border' id='tab_day' href="power-consumption-d" role='tab' aria-selected='false'>
					<span class='h5 mb-0 font-weight-bold '>每日用電</span>
				</a>    
			</li>
			<li class='nav-item'>
				<a class='nav-link card card-border active' id='tab_month' href="power-consumption-m" role='button' aria-selected='false'>
					<span class='h5 mb-0 font-weight-bold'>每月用電</span>
				</a>           
			</li>
		</ul>
		<hr class='view'>
	</div>
	<div class="container">
		<form id='mform2' action="power-consumption-m" method="post" class='col-12'>
			@csrf
			<div class="form-group row">
				<label class="col-sm-2 col-form-label label-right">房號</label>	
				<div class="col-sm-9 form-inline">
					<select class="col form-control " title='請選擇-帶入房號'  size="1"  name = 'room_id' id='room_id'>
						<option value='0'>全部</option>
						@foreach($room as $row)
						    @php
						        $selected = ($row->id == $room_id) ? 'selected' : '';
						    @endphp
						    <option value="{{ $row->id }}" {!! $selected !!}>{{ $row->name }}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="form-group row select-mar4" id='select_year' >
				<label class="col-sm-2 col-form-label label-right btn-martop20">年份</label>	
				<div class="col-sm-9 form-inline ">
					@php
                        $currentYear = date('Y');
                        $startYear = 2023; 
                    @endphp
					<select class="col form-control" title='請選擇'  size="1" name='year' id='year' required > 
						<option value="0" >請選擇...</option>
                        @for($selectedYear = $startYear; $selectedYear <= $currentYear; $selectedYear++) 
                         	@php
                            $selected = ($selectedYear == $year) ? 'selected' : ''; // 檢查是否為所選年分
                            @endphp
                            <option value="{{ $selectedYear }}" {!! $selected !!}>{{ $selectedYear }}</option>
                        @endfor
					</select>
				</div>
			</div>
			<div class="form-group row" id='select_month' >
				<label class="col-sm-2 col-form-label label-right btn-martop20">月份</label>	
				<div class="col-sm-9 form-inline">
					<select class="col form-control" title='請選擇'  size="1" name='month' id ='month' data-size="5" required>
						<option value="0" >請選擇...</option>
						@for ($selectedMonth = 1; $selectedMonth <= 12; $selectedMonth++) 
							@php
                            $selected = ($selectedMonth == $month) ? 'selected' : ''; // 檢查是否為所選月份
                            @endphp
                           	<option value="{{ $selectedMonth }}" {!! $selected !!}>{{ $selectedMonth }}</option>
                        @endfor
					</select>
				</div>
			</div>	
			<br>
			<div class='col-12'>
				<button type='submit' id="search-btn" class='btn  btn-loginfont btn-primary2  col-4 offset-4'>查詢</button>
			</div>
		</form>
		@if(!empty($result))
			<h1 class="jumbotron-heading text-center h1-mar">查詢結果</h1>
				<div class=" text-right ">
					<form id='mform1' action="power_consumption_m_excel" method="post" class='col-12'>
					@csrf
						<button type="submit" class="btn btn-loginfont btn-primary2 col-sm-2 offset-sm-10 mb-3" >匯出</button>
						<input type="hidden" id="room_id" name="room_id" value="{{ $room_id }}">
						<input type="hidden" id="year" name="year" value="{{ $year }}">
						<input type="hidden" id="month" name="month" value="{{ $month }}">
					</form>
				</div>
				<h5 class="text-gray-900 font-weight-bold">更新時間:</h5>
				<div id="power-total" class="col-12 alert alert-info text-green">
					總計用電：{{ $monthly_total }} 度
					<P>小計用電(110V)： {{ $monthly_total_amount }} 度
					<P>小計用電(220V)： {{ $monthly_total_amount_220 }} 度
				</div>
			<div class="row">
				@foreach($result as $i => $row)
				<div class='col-lg-6 card-group'>
					<div class='card mb-6 card-green text-green fz-18 h-auto' style='margin-bottom:15px;'>
						<div class='py-2 nowsystem'>
							<ul class='px-1'>
								<li >房號：<span id='dong'>{{ $row->name }}</span></li>
								<li >開始年月：<span id ='st_time'>{{ $startdate }}</span></li>
								<li >結束年月：<span id ='ed_time'>{{ $enddate }}</span> </li>
								<li >用電總計(110V)：<span id ='total'>{{ $row->monthly_amount }}</span> 度</li>
								<li >用電總計(220V)：<span id ='total'>{{ $row->monthly_amount_220 }}</span> 度</li>
							</ul>
						</div>
					</div>
				</div>
				@endforeach
			</div>
		@endif
	</div>
</section>
<script>
	$('#search-btn').click(function() {
		var  year = $('[name="year"]').val();
		var  month = $('[name="month"]').val();
	    if(year !== "" &&  month !== ""){
			$('body').loading({
			stoppable: false,
			message: '資料較龐大加總計算中，請耐心等候勿關閉本頁...',
			theme: 'dark'
			}); 
		}
	});
</script>
<script src="{{ asset('assets/js/Chart.min.js') }}"></script>
@include('footer_layout')