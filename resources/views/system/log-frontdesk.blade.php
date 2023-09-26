@include('system.header_layout')
@include('system.nav')
<div class="container-fluid">
    <div class="row container-fluid mar-bot50">
    </div>  
    <h1 class="mb-2 font-weight-bold">Web Log</h1>
	<form id='mform1'></form>
    <div class="my-4 text-right">
        <button type="button" onclick='dispose_list()' class="btn btn btnfont-30 text-white btn-primary2 col-lg-2">
          <i class='fas fa-trash-alt'></i>
          <span>清空</span>
		</button>
		&nbsp;
		<button type="button" onclick='export_file()' class="btn btn btnfont-30 text-white btn-primary2 col-lg-2">
          <i class="fas fa-arrow-circle-down"></i>
          <span>匯出</span>
        </button>
	</div>
    <div class="table-responsive">
      	<table class="table  text-center font-weight-bold">
        <thead class="thead-green">
          <tr class="text-center">
            <th scope="col">#</th>
            <th scope="col">建立日期</th>
            <th scope="col">修改訊息</th>
            <th scope="col">修改類別</th>
          </tr>
        </thead>
        <tbody class='log'>
          	<tr>
	            <td>1</td>
	            <td>2</td>
	            <td>3</td>
	            <td>4</td>
          	</tr>
        </tbody>
      	</table>
	</div>
	<div class="row ">
		<div class="container-fluid">
			<div class="text-center" id="dataTable_paginate">
			</div>
		</div>
	</div>		
</div>
<script>
function dispose_list() {
	if(confirm("確認提示\n您確定要清空嗎?")) {
		$('#mform1').prop('action', 'model/log_list_dispose.php');
		$('#mform1').prop('method', 'get');
		$('#mform1').submit();
	}
}
function export_file() {
	$('#mform1').prop('action', 'model/log_list_download.php');
	$('#mform1').prop('method', 'get');
	$('#mform1').submit();
}
</script>

@include('system.footer_layout')