<?php
    include('header_layout.php');
    include('nav.php');  
?>
<section class="wrapper">
    <div class="col-12 btn-back"><a href="member.php" ><i class="fas fa-chevron-circle-left fa-3x"></i><span class='back-font'><?php echo $lang->line("btn_back");?></span></a></div>

    <div class="row container-fluid mar-bot50 mar-center2">
        <?php if ($_GET['success']) { ?>
            <div style="margin: 0 auto; text-align: center;" class="alert alert-success col-lg-9" role="alert">
               <strong>系統已成功設定!!</strong>
            </div>
        <?php } elseif ($_GET['error'] == 1) { ?>
            <div style="margin: 0 auto; text-align: center;" class="alert alert-success col-lg-9" role="alert">
                <strong>您的"舊密碼"沒填或輸入錯誤!!</strong>
            </div>
        <?php } elseif ($_GET['error'] == 2) { ?>
            <div style="margin: 0 auto; text-align: center;" class="alert alert-success col-lg-9" role="alert">
                <strong>您的"新密碼"沒填!!</strong>
            </div>
        <?php } elseif ($_GET['error'] == 3) { ?>
            <div style="margin: 0 auto; text-align: center;" class="alert alert-success col-lg-9" role="alert">
                <strong>您的"確認密碼"沒填!!</strong>
            </div>
         <?php } ?>
    </div>

    
	<!-- 製作說明 -->
	<div class="container d-none">
		<div class="row justify-content-center">
			<div id="memo" class="alert alert-orange col-10">
				<p>製作說明</p>
				<p>【Table:tenant】</p>
				<span>A.【表單欄位說明】</span>
				<ul>
					<li>帳號~e-mail：從資料庫抓值顯示，其中帳號禁止編輯</li>
					<li>密碼變更：勾選了，跳出隱藏欄位</li>
				</ul><hr>
				<span>B.【表單防呆設計】</span>
				<ul>
					<li>帳號~e-mail：一律要有值，必填</li>
					<li>密碼變更：勾選=轉為必填送出後驗證完成，密碼修改成功；不勾選=表單送出時，即不變更密碼</li>
                    <li>密碼規則：4~8碼英數混合</li>
				</ul><hr>
				<span>C.【點確認修改】</span>
				<ul>
					<li>驗證必填選項是否未填寫，若未填寫，則不送出表單</li>
					<li>通過驗證後，跳出彈窗頁顯示確認是否修改</li>
					<li>彈窗頁中點「確定」，送出表單資料；點「取消」，彈窗消失回到本頁面</li>
					<li>Log：需紀錄修改前後的值</li>
				</ul>
			</div> 	
		</div> 	
	</div> 	

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h1 class="mb-4 text-center">資料變更</h1>
                <form action="model/admin_pw_upd.php" method="post">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label label-right">帳號</label>
                        <div class="col-sm-9">
                            <input required type="text" class="form-control" name="username" value="純顯示自己的帳號" disabled>  
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label label-right"><?php echo $lang->line("member_name");?></label>
                        <div class="col-sm-9">
                            <input required type="text" class="form-control" name="cname" placeholder="">  
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label label-right"><?php echo $lang->line("mail");?></label>
                        <div class="col-sm-9">
                            <input required type="email" class="form-control" name="email" placeholder="請輸入信箱..." />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 label-right">密碼設定</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="Chk1" value="">
                            <label class="form-check-label" for="Chk1">密碼變更</label>
                        </div>
                    </div>
                    <div id="pwd_area" class="d-none">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label label-right">輸入舊密碼</label>
                            <div class="col-sm-9">
                                <i class="fas fa-eye checkEye"></i>
                                <input type="password" class="form-control col" name="old_pwd" 
                                    minlength="4" maxlength="8"
                                    pattern="^(?![0-9]+$)(?![A-Za-z]+$)[0-9A-Za-z]{4,8}$">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label label-right">輸入新密碼</label>
                            <div class="col-sm-9">
                                <i class="fas fa-eye checkEye"></i>
                                <input type="password" class="form-control" name="new_pwd" 
                                    placeholder="請輸入4~8碼英數混合" inputmode="text"  
                                    minlength="4" maxlength="8" pattern="^(?![0-9]+$)(?![A-Za-z]+$)[0-9A-Za-z]{4,8}$">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label label-right">確認新密碼</label>
                            <div class="col-sm-9">
                                <i class="fas fa-eye checkEye"></i>
                                <input type="password" class="form-control" name="new_pwd_check" 
                                    placeholder="請輸入4~8碼英數混合" inputmode="text"  
                                    minlength="4" maxlength="8" pattern="^(?![0-9]+$)(?![A-Za-z]+$)[0-9A-Za-z]{4,8}$">
                            </div>
                        </div>
                    </div>
                    <br><br> 
                    <button type="submit" class="btn btn-loginfont btn-primary2 col-sm-4 offset-sm-4" onclick="return confirm('你確定要修改嗎?');">確認修改</button>
                </form>
            </div>
        </div>
    </div>
</section>
<?php include('footer_layout.php'); ?>
<script src="./assets/js/password_func.js"></script>
<script>
	$(document).ready(function(){
		$("#Chk1").on("click",function() {
				if($(this).prop("checked")){
					$("#pwd_area").removeClass("d-none");
					$('#pwd_area input').prop('required',true);
				}else{
					$("#pwd_area").addClass("d-none");
					$('#pwd_area input').prop('required',false);
				}
		});
		$('#mform').submit(function(event) {
			if($('#new_pwd').val() != $('#new_pwd_check').val()) {
				alert("新密碼與確認新密碼不一致");
				return false;
			};
		});
	});
</script>