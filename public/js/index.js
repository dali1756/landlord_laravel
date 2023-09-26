/* 通用JS - index.js */

//回上一頁
function backs()
{
	history.go(-1); 
}

//送出
function del_select_chk(){
    document.getElementsByName('form1')[0].submit();
}

//jquery all全選 
$(".checkAll").change(function checkAll(obj) {
    var checked = $(this).data('checked')
    var checkboxs = document.getElementsByName(checked);
    for (var i = 0; i < checkboxs.length; i++) 
    {
      checkboxs[i].checked = this.checked;
    }
})