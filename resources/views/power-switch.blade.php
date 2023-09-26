@include('header_layout')
@include('nav')
<section id="main" class="wrapper">
	<div class='col-12 btn-back'><a href='manage' ><i class="fas fa-chevron-circle-left fa-3x"></i><label class='previous'></label></a></div>
	<div class="rwd-box"></div><br><br>
	<div class="container">
		<h1 class="jumbotron-heading text-center">開關電設定</h1>
	</div>
	@if(!empty($error))
	<div class="row container-fluid mar-bot50 mar-center2">
		<div style="margin: 0 auto; text-align: center; width: 600px;" class="alert alert-danger" role="alert">
			<strong>{{ $error }}</strong>
		</div>					
	</div> 
	@endif   
	<div class="inner">   
		<div class="row justify-content-center">
			<div class="col-12">
				<form id="mform_s" action="power-switch" method="post">
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
					<button class="btn btn-primary2 col-4 offset-4">查詢</button>
				</form>
			</div>
			<br>
			@if(!empty($result))
			<div class="col-lg-5" >
				<div class="card shadow mb-4">
					<div class="card-header py-3">
						<h6 class="m-0 font-weight-bold text-center">開/關電設定</h6>
					</div>
					<div class="card-body">
						@if($mode == 4 || $mode == 3 )
						<div id="result" class="text-center mx-auto">
							<a  onclick="postPower({{ $roomid }},1,'確認開電?')"  class="btn btn-warning btn-circle btn-lg">
								<i class="fas fa-power-off fa-2x"></i><br>
							</a>
							<h3>開電</h3>
						</div>
						@elseif($mode == 1)
						<div id="result" class="text-center mx-auto">
							<a  onclick="postPower({{ $roomid }},4,'確認關電?')"  class="btn bg-gray-500 btn-circle btn-lg">
								<i class="fas fa-power-off fa-2x"></i><br>
							</a>
							<h3>關電</h3>
						</div>
						@endif
					</div>
				</div>
			</div>
			@endif
		</div>
	</div>
</section>
@include('footer_layout')
@if(!empty($result))
<script>
	function postPower(room_id,switch_power,msg_text){
        $.ajax({
            url: 'power-switch-update',
            type: 'POST',
            data: {
                _token: $('input[name="_token"]').val(),
                room_id: room_id,
                switch_power: switch_power
            },
            dataType: 'json', // 指定回傳數據類型為 JSON
            success: function(response) {
                // 成功回調函數，response 是伺服器回傳的數據
                var mode = response.mode;
	            // 根據 mode 的值顯示相應的按鈕和內容
	            if (mode == 1) {
	                $('#result').html('<div id="result" class="text-center mx-auto"><a onclick="postPower({{ $roomid }},4,\'確認關電?\')"  class="btn bg-gray-500 btn-circle btn-lg"><i class="fas fa-power-off fa-2x"></i><br></a><h3>關電</h3></div>');
        		} else if (mode == 4) {
	            	$('#result').html('<div id="result" class="text-center mx-auto"><a onclick="postPower({{ $roomid }},1,\'確認開電?\')"  class="btn btn-warning btn-circle btn-lg"><i class="fas fa-power-off fa-2x"></i><br></a><h3>開電</h3></div>');
	            }
            },
            error: function (xhr, status, error) {
                // Handle errors
                console.log(error);
            }
        });
	}
</script>
@endif