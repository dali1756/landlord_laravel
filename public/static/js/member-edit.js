
function reset(sn) {
	var msg = "確認提示\n將還原預設密碼：88888 (請務必變更)\n確定要還原嗎?";
	if(confirm(msg))
	{
		location.replace("model/admin_reset.php?sn="+sn+"&type=pwd");
	}
}

function active(sn) {
	var msg = "確認提示\n啟用後，恢復先前操作本系統之使用權限，\n並還原預設密碼：88888 (請務必變更)\n確定要啟用嗎?";
	if(confirm(msg))
	{
		location.replace("model/admin_reset.php?sn="+sn+"&type=active");
	}
}

function stay(sn) {
	var msg = "確認提示\n將停用帳號\n您確定要停用嗎?";
	if(confirm(msg))
	{
		location.replace("model/admin_reset.php?sn="+sn+"&type=stay");
	}
}

$('#run_search').click(function() {
	var id = $('#id').val();
	var name = $('#name').val();
	var room = $('#room').val();
	var status = $('#status').val();
	var identity = $('#identity').val();
	$.ajax({
		url: '/member-proc/',
		type: 'POST',
		datatype: 'text',
		data: 'mode=search&id=' + id + '&name=' + name + '&room=' + room + '&status=' + status + '&identiry=' + identity,
		success: function(msg){
			$('#data_list').show();
			//alert('msg:' + msg.split('),'));
			var data = msg.split('|');
			//alert(data.length);
			var datas = "";
			for(let i=0;i<data.length;i++) {
				datas += data[i] + "<BR>";
			}
			alert(datas);
			$('#search_data').val(datas);
			//if(msg == 'ok') {
				
				//var suspension_dates= msg.split(',');
				//document.getElementById("st_date").value = suspension_dates[0];
				//document.getElementById("end_date").value = suspension_dates[1];
			//}
		}
	});

});