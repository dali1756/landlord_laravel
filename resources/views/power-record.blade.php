@php
	use App\Models\Power;
@endphp
@include('header_layout')
@include('nav')
<section id="main" class="wrapper">
	<div class='col-12 btn-back'><a href='manage' ><i class="fas fa-chevron-circle-left fa-3x"></i><label class='previous'></label></a></div>
	<div class="rwd-box"></div><br><br>
	@if(!empty($error))
	<div class="row mar-center2 mb-4" style="margin: 0 auto;">
		<div style="margin: 0 auto; text-align: center;" class="alert alert-danger col-lg-9" role="alert">
			<strong>{{ $error }}</strong>
		</div>	
	</div>
	@endif
	<div class="container" style="text-align: center;">
		<h1 class="jumbotron-heading text-center">電力使用紀錄</h1>
    </div>
	<div class="row container-fluid mar-bot50 mar-center2">
	</div>
	<div class="inner">
	<div class="row">
		<form id='mform1' action="power-record" method="post" class='col-12'>
			@csrf
			<div class='col-12'>
				<section class='panel panel-noshadow'>
					<div class='panel-body'>
						<div class='form-group row '>
							<label for='exampleFormControlInput1' class='col-sm-2 col-form-label label-right'>
								房號
							</label>
							<div class='col-sm-9 form-inline'>
								<select  title='全部' class="col form-control selectpicker show-tick" data-size="5" name="room_id" id='room_id'>
									<option value="0" selected>全部</option> 
									@foreach($room as $row)
										@php
											$selected = ($row->id == $room_id) ? 'selected' : '';
										@endphp
										<option value="{{ $row->id }}" {!! $selected !!}>{{ $row->name }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class='form-group row select-mar3'>
							<label for='exampleFormControlInput1'
								class='col-sm-2 col-form-label label-right'>開始時間</label>
							<div class='col-sm-9'>
								<input id='start_date' type='date' class='form-control date-pd' name='start_date' value="{{ $start_date }}">
							</div>
						</div>
						<div class='form-group row'>
							<label for='exampleFormControlInput1'
								class='col-sm-2 col-form-label label-right'>結束時間</label>
							<div class='col-sm-9'>
								<input id='end_date' type='date' class='form-control date-pd' name='end_date' value="{{ $end_date }}">
							</div>
						</div>
					</div>
				</section>
			</div>
			<button  type="submit" class='btn  btn-loginfont btn-primary2  col-4 offset-4'>查詢</button>
		</form>
	</div>
</div>
@if(!empty($result) && isset($room_id))
	<div class='inner3'>
	<h1 class="jumbotron-heading text-center h1-mar">查詢結果</h1>
		<div class="col-12">
			<div class="col-12 text-right">
				<form id='mform1' action="power_record_excel" method="post" class='col-12'>
				@csrf
					<button type="submit"  class="btn btn-loginfont btn-primary2 col-sm-2 offset-sm-10 mb-3">匯出</button>
					<input type="hidden" id="room_id" name="room_id" value="{{ $room_id }}">
					<input type="hidden" id="start_date" name="start_date" value="{{ $start_date }}">
					<input type="hidden" id="end_date" name="end_date" value="{{ $end_date }}">
				</form>
			</div>
			<br>
			<div class="col-12 alert alert-info d-inline-block">
				<h4 class="mb-0">提醒：變更費率,將在下一個小時生效！</h4>
			</div>
		  	<div id="nested-table-custom" class="col">
				<table class="table text-center font-weight-bold" data-toggle="table">
				  	<thead class="thead-green">
					    <tr class="text-center">
							<th scope="col">#</th>
							<th scope="col">日期</th>
							<th scope="col">房號</th>
						    <th scope="col">用電度數(110V)</th> 
							<th scope="col">費率(110V)</th>
							<th scope="col">金額(110V)</th>
							<th scope="col">用電度數(220V)</th>							
						    <th scope="col">費率(220V)</th>
						    <th scope="col">金額(220V)</th> 
						    <th scope="col">詳細資訊</th> 
					    </tr>
				  	</thead>
				  	<tbody>
				  		@foreach($result as $i => $row)
						<tr>
							<td scope='col'>{{ $i+1 }}</td>
							<td scope='col'>{{ $row->add_date }}</td> {{-- 日期 --}}
							<td scope='col'>{{ $row->name }}</td> {{-- 房號 --}}
							<td scope='col'>{{ $row->daily_total_amount }}</td> {{-- 用電度數(110V) --}}
							<td scope='col'>{{ $row->price_degree_avg }}</td> {{-- 費率(110V) --}}
							<td scope='col'>{{ $row->total110_money }}</td> {{-- 金額(110V) --}}
							<td scope='col'>{{ $row->daily_total_amount_220 }}</td> {{-- 用電度數(220V) --}}
							<td scope='col'>{{ $row->price_degree_avg_220 }}</td> {{-- 費率(220V) --}}
							<td scope='col'>{{ $row->total220_money }}</td> {{-- 金額(220V) --}}
							<td>
								@php
									if($i<7 && $result->currentPage() == 1){
								@endphp	
								<a class="showmore text-primary">
									<i class="fas fa-plus-square"></i>  
									More
								</a>
								@php
									}
								@endphp	
							</td>
						</tr>
					    <tr class="detail">
							<td colspan="10" class="p-0">
								<div class="history">
									<table class="table ">
										<thead class="thead-green d-none">
											<tr class="text-center">
												<th scope="col">#</th>
												<th scope="col">時間</th>
												<th scope="col">房號</th>
												<th scope="col">用電度數(110V)</th> 
												<th scope="col">費率(110V)</th>
												<th scope="col">金額(110V)</th>
												<th scope="col">用電度數(220V)</th>							
												<th scope="col">費率(220V)</th>
												<th scope="col">金額(220V)</th> 
											</tr>
										</thead>
										<tbody class="custom-detail">
											@php
												$end_date =  date('Y-m-d', strtotime($row->add_date." +1 day"));
					                            $resultlist = Power::power_record_list($row->add_date, $end_date, $row->room_id);
					                        @endphp
					                        @foreach($resultlist as $i => $detailRow)
											<tr>
												<td scope='col'>{{ $i+1 }}</td> 
												<td scope='col'>{{ $detailRow->add_date }}</td> {{-- 日期 --}}
												<td scope='col'>{{ $detailRow->name }}</td> {{-- 房號 --}}
												<td scope='col'>{{ $detailRow->amount }}</td> {{-- 用電度數(110V) --}}
												<td scope='col'>{{ $detailRow->price_degree }}</td> {{-- 費率(110V) --}}
												<td scope='col'>{{ $detailRow->total110_money }}</td> {{-- 金額(110V) --}}
												<td scope='col'>{{ $detailRow->amount_220 }}</td> {{-- 用電度數(220V) --}}
												<td scope='col'>{{ $detailRow->price_degree_220 }}</td> {{-- 費率(220V) --}}
												<td scope='col'>{{ $detailRow->total220_money }}</td> {{-- 金額(220V) --}}
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</td>
					  	</tr>
					  	@endforeach
				  	</tbody>
				</table>
			</div>
			@include('page', ['paginator' => $result])
        </div>
	</div>
@endif
</section>
</body>
<script>
// 詳細資訊-展開伸縮功能
$(document).on('click', 'a.showmore', function(e) {
    e.preventDefault();
    var targetrow = $(this).closest('tr').next('.detail');
    targetrow.find('div').slideToggle('fast', function() {
        if (!$(this).is(':visible')) {
            $(this).hide();
        } else {
            $(this).show();
        }
    });
});

//動態變更選擇日期
$('#start_date').on('change', function(e) {
    const endDateInput = document.getElementById('end_date');
	// 取得選擇的日期
    const start_date = document.getElementById('start_date').value;
	// 設定 min 屬性值為選擇的日期
	endDateInput.setAttribute('min', start_date);
});
$('#end_date').on('change', function(e) {
    const startDateInput = document.getElementById('start_date');
	// 取得選擇的日期
    const end_date = document.getElementById('end_date').value;
	// 設定 max 屬性值為選擇的日期
	startDateInput.setAttribute('max', end_date);
});

</script>
<script src="{{ asset('assets/js/Chart.min.js') }}"></script>
@include('footer_layout')