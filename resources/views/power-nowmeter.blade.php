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
		<h1 class="jumbotron-heading text-center">用電現況</h1>
    </div>
	<div class="row container-fluid mar-bot50 mar-center2">
	</div>
	<div class="inner">
		<div class="row">
			<form id='mform1' action="power-nowmeter" method="post" class='col-12'>
				@csrf
				<div class='col-12'>
					<section class='panel panel-noshadow'>
             			<div class='panel-body'>
							<div class='form-group row '>
								<label for='exampleFormControlInput1' class='col-sm-2 col-form-label label-right'>房號</label>
								<div class='form-inline col-sm-9'> 
									<select class="col form-control selectpicker show-tick" title='全部'  data-size="5" name="room_id"  id='room_id'>
										<option value='0' selected>全部</option>
										@foreach($room as $row)
										    @php
										        $selected = ($row->id == $room_id) ? 'selected' : '';
										    @endphp
										    <option value="{{ $row->id }}" {!! $selected !!}>{{ $row->name }}</option>
										@endforeach
									</select>
								</div>
					 		</div>
							<br><br>
							<button type='submit'  class='btn btn-primary2 col-4 offset-4'>查詢</button>
             			</div>
             		</section>
             	</div>
			</form>
		</div>
	</div>
@if(!empty($result) && isset($room_id))
	<div class='inner'>
		<div class="col-12">
			<h1 class="jumbotron-heading text-center h1-mar">查詢結果</h1>
			<div class=" text-right ">
				<form id='mform1' action="power_nowmeter_excel" method="post" class='col-12'>
				@csrf
					<button type="submit" class="btn btn-primary2 col-sm-2 offset-sm-10 mb-3">匯出</button>
					<input type="hidden" id="room_id" name="room_id" value="{{ $room_id }}">
				</form>
			</div>
			<br>
		  	<div class="table-responsive">
				<table class="table   table-condensed text-center  font-weight-bold">
				  	<thead class="thead-green">
				  	<tr class="text-center">
					  <th scope="col">房號</th>
					  <th scope="col">目前電表度數(110V)</th>
					  <th scope="col">目前電表度數(220V)</th>
					</tr>
				  	</thead>
				 	<tbody>
				 		@foreach($result as $row)
							<tr>
								<td scope='row'>{{ $row->name }}</td>
								<td scope='row'>{{ $row->amount }}</td> 
								<td scope='row'>{{ $row->amount_220 }}</td> 
							</tr>
						@endforeach
				  	</tbody>
				</table>
			</div>
		</div>
	</div>
@endif
</section>
@include('footer_layout')