// 表單form_id設置
const form_id = ['form:eq(0)','form:eq(1)'];
// 表格&按鈕填寫id
let input_form = '#input_form';
const btn_id = ['#addbtn','#prevbtn','#postbtn'];
// 預設流程控制_起點值
var currentStep = 0;


// 整合到static\js\model\custom.js
/*  管理設定_顯示 or 隱藏註冊區域、按鈕文字 _ShowTab(n) */ 
/***********************************************************************
     適用功能：會由nextPrev(n)自動調用ShowTab(n)，
            統一用class .tab-area控制顯示隱藏區域(須在對應的HTML添加)
            統一用btn_id[1]，控制顯示的按鈕文字(須在對應的頁面宣告)
    n   =  currentStep，取決於nextPrev(n)
    1.抓.tab-area
    2.用n判斷隱藏、顯示的畫面
    3.各環節按鈕文字顯示：抓tab.length 
                        若不是最後一步，按鈕顯示=>上/下一步
                        若到最後一步驟，按鈕顯示=>上一步、確認送出
************************************************************************/
    function ShowTab(n){
        let tab = $('.tab-area');

        // 顯示 or 隱藏
        if(n==0){
            tab.eq(n).show();
            tab.eq(n+1).hide();
        }else{
            tab.eq(n).show();
            tab.eq(n-1).hide();
        }

        /* 各環節按鈕文字顯示*/
        if(n == (tab.length -1)){
            // 當進入到最後一步填寫環節，...
            $(btn_id[1]).text('上一步');
            $(btn_id[2]).text('確認送出');
        }else{
            // 非最後一步填寫環節，則其餘階段步驟顯示...
            $(btn_id[0]).text('下一步');
        }

        // console.log('tab',tab);
        // console.log('tab.length',tab.length);
        // console.log('n',n);
        // console.log('tab.length-1',tab.length-1);
    }
                        
/* 管理設定_流程控制_nextPrev(n) */ 
/***********************************************************************
    n=1_下一步，n=-1_上一步 
    適用：註冊頁Multi Step，流程控制畫面
    1.currentStep   須在對應的頁面宣告
    2.若n=1，則將currentStep + n 的結果回傳並調用給ShowTab()使用
    3.若n不是1，則將currentStep + n 的結果回傳並調用給ShowTab()使用
************************************************************************/ 
    function nextPrev(n){
        currentStep = currentStep + n;
        if(n == 1){
            ShowTab(currentStep);
        }else{
            ShowTab(currentStep);
        }
        /* 驗證用
            const x = $('.tab-area');
            console.log('x',x);
            console.log('x.len',x.length);
            console.log('x[currentStep]',x[currentStep]);
            
            console.log('next_currentStep',currentStep);
            console.log('pre_currentStep',currentStep);
        */ 
    }


// 整合到static\js\model\content.js
    /* data_flow_func 判斷資料要走哪一個處理流程
        form_name   抓住所有數據，須輸入表單的id or 選擇器
        index       data索引值
        index_num   判斷資料走向依據，且為過濾資料的判斷值，可人為控制流程走向
    */ 
    let data_flow_func = function(form_name,index,index_num){
        let data = $(form_name).serializeArray();
        // console.log('data',data);
        // 數據分類整理
        if(index == index_num){
        let key_name = data[index].name;
        let key_value = data[index].value;
        save_data_func(key_name,key_value,post_data);
        }else{
        let iscurrentVal = currentVal => currentVal.name!=data[index_num].name;
        return data.filter(iscurrentVal);
        }
    };

    /* save_data_func 存入資料到指定位置 
        key_name    存入的key名稱
        key_value   存入的值
        save_location 存入位置
    */ 
    let save_data_func = function(key_name,key_value,save_location){save_location[key_name] = key_value;};

    /* data_cal_func    跑完的資料逐筆整合到return_const(如:ctrl_obj)
        適用：開始往後/回追(遞增/遞減)--找多筆對象資料，並存成1筆對象
        i               data_name 起始值
        n               遞增/減的起始值
        len             執行次數 (每 len 筆執行1次)
        return_const    data_name 資料整理完後放置的地方
        data_name       要處理的資料名稱
    */ 
    let data_cal_func = function(i,n,len,return_const,data_name) {
        for(i,n; n<=len; n++){
        // 往回遞減抓資料，共執行 len 次
        // return_const[data_name[i-n].name] = data_name[i-n].value;
        // 往前遞增抓資料，共執行 len 次
        return_const[data_name[i+n].name] = data_name[i+n].value;
        }
    };

    /* ctrl_data_func   控制表單純撈某一值 or 將值返回到對應名稱的欄位內
        適用：純撈某一值 or 逐筆將值返回到對應名稱
        form_id     填入表單form id
        sop_num     判斷表單資料走向，純撈值/將值返回到對應名稱的欄位內，可人為控制流程走向
        n           要減的數 sop_num - n = 實際抓取的索引值
    */ 
    let ctrl_data_func = function(form_id,sop_num,n){
        let form_data = $(form_id).serializeArray();
        if(sop_num == 0){
            return form_data[sop_num].value;
        }else{
            // form_data[sop_num].value = 1;
            let n_val = form_data[sop_num-n].value;
            return $(`[name=${form_data[sop_num-n].name}]`).val(n_val);
        }
    }

// 在manage-ctrl.html，寫入並執行以下內容
    const post_data = {};
    // manage_ctrl要存的資料位置
    var ctrl_array;

    // 執行
    $(document).ready(function(){
        // 預設:輸入數量區顯示、表格隱藏
        ShowTab(currentStep);

        // 下一步：輸入控制器數量_數據創建渲染、所有欄位，必填
        $(form_id[0]).on('submit',function(e){
            e.preventDefault();
            let ctrl_num = ctrl_data_func(form_id[0],0);
            if(ctrl_num != ''){
                    nextPrev(1);
                    for(let i = 1; i<=ctrl_num; i++){
                        const row = `
                            <tr>

                                <td>${i}</td>
                                <td><input type="text" class="form-control col" name="serial_number"></td>
                                <td><input type="text" class="form-control col" name="serial_pwd"></td>
                                <td><input type="text" class="form-control col" name="wifi_ssid" pattern="^.{0,32}$"></td>
                                <td><input type="text" class="form-control col" name="wifi_pwd"></td>
                            </tr>`;
                        $(input_form).append(row);
                    }
                    // 所有欄位，必填
                    $(input_form+' input').prop('required',true);
            }else{
                swal('Error', '不可填空！', 'error');
            }
            /*驗證用：抓當前所在區域 & 下個區域畫面
            var current_area = $(this).closest('.tab-area')
            var next_area = current_area.next();
            console.log('current_area',current_area);
            console.log('next_area',next_area);
            */
        });

        // 上一步：返回上一次key的值，清空表格資料，畫面回到預設
        $(btn_id[1]).on('click',function(e){
            e.preventDefault();
            // 返回上一次key的值&清空表格資料
            let ctrl_num = ctrl_data_func(form_id[0],1,1);
            $(input_form).empty();
            // 執行上一步功能:畫面回到預設
            nextPrev(-1);
            /* 彈性調整code
                // 檢查當前currentStep值
                console.log('before_change_currentStep',currentStep);
                
                console.log(form_data);
                console.log('ctrl_num',ctrl_num);
                console.log('返回上一次key的值',ctrl_num);
                數量預設回1
                form_data[0].value = 1;
                $('#f1 input').val(form_data[0].value);
            */
        });
    
        /* 數據傳遞API串接
            1.各別處理member_id、manage_ctrl，最後存在post_data傳給後端
            
            有關manage_ctrl......
            2.抓第1筆資料(索引=0)，並依序用等差每4筆{}遍歷遞增資料
            3.其中manage_ctrl相關變數有：ctrl_array初始清空所有對象、
                                        ctrl_obj 每4筆創建1對象
            4.開始往 後/回 追找對象資料，並存成1對象存入中ctrl_array

            5.發送請求&資料轉JSON後送出，並清空表格資料&回到初始控制器註冊頁(預設值為方才填寫的值)。
        */ 
        $(form_id[1]).on('submit',function(e){
            e.preventDefault();
            // 抓member_id & 存入post_data
            data_flow_func(this,0,0);

            // 過濾manage_ctrl用的資料
            let manage_ctrl_data = data_flow_func(this,1,0);
            // 初始清空ctrl_array中所有對象
            ctrl_array = [];
            // 抓第1筆資料(索引=0)，並依序用等差每4筆{}遍歷遞增資料
                for(i=0, n=4 , len=manage_ctrl_data.length; i<len; i=i+n){
                    // 每4筆創建1對象
                    const ctrl_obj = {};
                    // 開始往後/回追找--找資料，並存入對象中
                    data_cal_func(i,0,3,ctrl_obj,manage_ctrl_data);
                    // 將1對象的結果，存入ctrl_array
                    ctrl_array.push(ctrl_obj);
                }
                save_data_func('manage_ctrl',ctrl_array,post_data);
            
            /* 不轉JSON的作法 & 驗證用
                console.log('manage_ctrl_data',manage_ctrl_data.length);
                // console.log('ctrl_array',ctrl_array);
                // console.log('post_data',post_data);
                // PostData2(this,'./api/charge-lateday_all.json','POST','json',charge_all_alert,post_data,true);
            */
        
            //發送請求&資料轉JSON後送出
            PostData2(this,'./api/charge-lateday_all.json','POST','json',charge_all_alert,JSON.stringify(post_data),true);
            // 清空表格資料
            $(input_form).empty();
            //回到初始控制器註冊頁
            nextPrev(-1);
        });
    });
