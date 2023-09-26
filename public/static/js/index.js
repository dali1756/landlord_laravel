
var counter = 0;
var chk_msg = "{{message}}";
var login_type = "{{login_type}}";
var id = "{{back_html}}";
if(chk_msg != '') {
    if(login_type =='admin') {
        $('#identity2').hide();
        $('#identity').show();
    } else {
        $('#identity').hide();
        $('#identity2').show();
    }
}  

$('#btn_adminlogin').click(function() {
    var id = $('#id').val();
    var pwd = $('#pwd').val();
    if(id.length == 0 || pwd.length == 0)  {
        if(id.length == 0 && pwd.length > 0) $('#DialogBoxContent').text("帳號不能空白");
        else if(id.length > 0 && pwd.length == 0) $('#DialogBoxContent').text("密碼不能空白");
        else $('#DialogBoxContent').text("帳號及密碼不能空白");
        msgbox(1);
    } else {
        $('#adminlogin').submit();
        /*
        $.ajax({
            url: '/admin_login',
            type: 'POST',
            datatype: 'text',
            data: 'id=' + id + '&pwd=' + pwd,
            success: function(msg){
                if(msg == 'ok') {
                    
                    var suspension_dates= msg.split(',');
                    document.getElementById("st_date").value = suspension_dates[0];
                    document.getElementById("end_date").value = suspension_dates[1];
                }
            }
        });
        */
    }
});

$('#btn_userlogin').click(function() {
    var id = $('#id2').val();
    var pwd = $('#pwd2').val();
    if(id.length > 0 && pwd.length > 0)  {
        $('#userlogin').submit();
    } else {        
        if(id.length == 0 && pwd.length > 0) $('#DialogBoxContent').text("帳號不能空白");
        else if(id.length > 0 && pwd.length == 0) $('#DialogBoxContent').text("密碼不能空白");
        else $('#DialogBoxContent').text("帳號及密碼不能空白");
        msgbox(1);
    }
});

function msgbox(method) { // 遮罩彈窗
    if(method == 1) $("#dialogButton2").hide();
    $("#DialogBox").modal('show');
    $("#banner").css("z-index","1030");
}
$(document).ready(function() {

    $('.button.alt').click(function() {
        $('#identity2').hide();
        if ($('#identity').css('display') == 'block') {
            $('#banner').css("background-image", "url(/static/img/bk2.jpg)");
        } else {
            $('#banner').css("background-image", "url(/static/img/bk.jpg)");
        }
    });
    $('.button.alt2').click(function() {
        $('#identity').hide();
        if ($('#identity2').css('display') == 'block') {
            $('#banner').css("background-image", "url(/static/img/bk2.jpg)");
        } else {
            $('#banner').css("background-image", "url(/static/img/bk.jpg)");
        }
    });
		
    $('.for_mobile').click(function() {
        $('#identity').hide();
        $('#identity2').hide();
    });

});
