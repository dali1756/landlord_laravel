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
		<h1 class="jumbotron-heading text-center">費率設定</h1>
		<div class="col-12 alert alert-info">
			<h4 class="mb-0">提醒：變更費率,將在下一個小時生效！</h4>
		</div>
	</div>
	<div class="row container-fluid mar-bot50 mar-center2">
	</div>    
	<div class="inner">   
		<div class="row justify-content-center">
			<div class="col-12">
				<form id="mform_s" action="rate" method="post">
					@csrf
					<div class='form-group row'>
						<label class='col-sm-2 col-form-label label-right'>房號</label>
						<div class='form-inline col-sm-9'> 
							<select class="col form-control selectpicker show-tick" title='請選擇'  data-size="5" name="room_id"  id='room_id' required>
								@foreach($room as $row)
									@php
										$selected = ($row->id == $room_id) ? 'selected' : '';
									@endphp
									<option value="{{ $row->id }}" {!! $selected !!}>{{ $row->name }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<button class="btn btn-primary2  col-4 offset-4">查詢</button>
				</form>
			</div>
		</div>
		@if(!empty($result))
		<form id="mform" action="rate_update" method="post" class="row">
			@csrf
			<div class="col-lg-6">
				<div class="card shadow mb-4">
					<div class="card-header py-3">
						<h6 class="m-0 font-weight-bold text-center">
							110V電錶<br>【電燈插座】
						</h6>
					</div>
					<div class="card-body ">					
						<div class="form-group">
							<label class="label-center col btn-marbot20">費率</label>
							<input step="0.1" min="0" max="25.5" type="number" required="required" class="form-control col-8 offset-2" name="price_elec_degree" value="{{ $price_degree }}" required>
							<input  type="hidden"  name="old_price_elec_degree" value="{{ $price_degree }}">
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="card shadow mb-4">
					<div class="card-header py-3">
						<h6 class="m-0 font-weight-bold text-center">
							220V電錶<br>【空調】
						</h6>
					</div>
					<div class="card-body ">					
						<div class="form-group">
							<label class="label-center col btn-marbot20">費率</label>
							<input step="0.1" min="0" max="25.5" type="number" required="required" class="form-control col-8 offset-2" name="price_elec_degree_220" value="{{ $price_degree_220 }}" required>
							<input  type="hidden"  name="old_price_elec_degree_220" value="{{ $price_degree_220 }}">

						</div>
					</div>
				</div>
			</div>
			<input type="hidden" name="room_numbers_hidden" value="{{ $room_id }}">
			<div class="w-100 d-flex justify-content-center mb-4">
				<button type="submit"  onclick="return confirm('確認更新?')"  class="btn  btn-h-auto text-white btnfont-30  btn-martop20 font-weight-bold  btn-primary2 col-sm-6 col-lg-3">確認更新</button> 
			</div>	
		</form>
		@endif
	</div>
</section>
@include('footer_layout')