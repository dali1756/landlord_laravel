/*  存放各頁資料要呈現的內容
    適用：AJAX資料讀取
    索引目錄：
    變數命名            檔名                     對應
    powerNowmeter       power-nowmeter.html    電錶現況
    power_nowmeter_col_heads                   電錶現況用來渲染的語言包
    = [cur_time, power_on, power_off,newest_electricity_degree, degree]

    charge_single       charge-xxx.html        個別房間_查詢_複雜版
    charge_all_alert    charge-xxx.html        個別/全部房間設定=>確認更新的提示彈窗&文字
    charge_single_simple manage-xxx.html       個別房間_查詢_簡易版
*/ 

let powerNowmeter = (data) => {
    $('#now-meter').empty();
    const aoword = power_nowmeter_col_heads
    var cur_time = new Date().toLocaleString('zh-TW',{hour12:false});
    $('h5.text-gray-900').empty();
    $('h5.text-gray-900').append(aoword[0]+':'+cur_time);
    const textcolor =['text-gray','text-lightgreen'];
    const power_status =[aoword[2],aoword[1]];

    if(data == ''){
        Search_alert()
    }else{
        $('#search-data button:eq(0)').removeClass("d-none")
        $.each(data,function(num,v){
            var status = '<i class="fas fa-circle pr-2"></i>';
            var power_degree =`<p>${aoword[3]}：${v.last_degree + aoword[4]}</p>`
            // var water_degree =`<p>${aoword[3]}：${v.newest_water_degree + aoword[4]}+</p>`;
            
            if(v.power_enable){
                var status=`<span class="${textcolor[1]}">${status}${power_status[1]}</span>`
            }else{
                var status=`<span class="${textcolor[0]}">${status}${power_status[0]}</span>`
            }

            $("#now-meter").append(
                `<div class="col-lg-3 col-md-6 col-12 mb-4">
                    <div class="card card-green text-center p-2">
                        <div class="mb-3"><h5>【${v.room_name}】</div>
                        <div class="col-12 text-left">
                            ${status+power_degree}
                        </div> 
                    </div> 
                </div>`                                
            )
        })
    }
}

/* 抓此form_id[0] form:eq(0) 同層的下一個兄弟元素，把data遍歷倒入表中
   適用：管理設定_有個別房間_查詢的頁面
*/ 
// 對應合管家複雜版，包含適用於：費率&固定金額
let charge_single_complex_ver = (data,id)=>{
    // console.log(data)
    if(data){
        // if(data.room_name != ''){
        var form_id = $(id).next()
        // 依序撈遍歷資料，並找出對應n索引名的name，分別放入對應data，故因此re不會被放到name中
        $.each(data,(n,v)=>{
            form_id.find('[name="'+n+'"]').val(v)
        })

            /* 費率&固定金額相關判斷，有對應的欄位才會執行的功能 
                若數據中有啟用固定收費...
                1啟用，checkbox 打勾
                0不啟用，checkbox 不打勾
            */ 
            const check_data = [data.fixed_electricity_bill_enable,data.fixed_water_bill_enable]
            if(check_data){
                $.each(check_data,(n,v)=>{
                    if(v == 1){
                        $(form_id).find(`[type="checkbox"]:eq(${n})`).prop("checked",true)
                    }else{
                        $(form_id).find(`[type="checkbox"]:eq(${n})`).prop("checked",false)
                    }
                })
                // 表單交互畫面顯示/隱藏：用水/電費率、固定水/電費，查出房號時執行
                    // 個別房間:隱藏的欄位值不清空值，且取消必填
                    BillRate(1,0,3,0);
                    BillRate(1,1,4,1);
            }
        form_id.removeClass("d-none");
    }else{
        Manage_alert(data, '無此房號，請重新查詢！')
        // Manage_alert(data.room_name,'無此房號，請重新查詢！')
        form_id.addClass("d-none");
    }
}

// 對應合管家簡易版
let charge_single_simple = (data,id)=>{
    console.log(data)
    console.log('開/關電:',data.power_enable)
    // if(data){
    if(data.room_name != ''){
        var form_id = $(id).next()
        // 依序撈遍歷資料，並找出對應n索引名的name，分別放入對應data，故因此不會被放到name中
        $.each(data,(n,v)=>{
            form_id.find('[name="'+n+'"]').val(v)
        })
        // 個別房間_電力開關
        const power_status = data.power_enable
        if(power_status){
            console.log('Yes')
            console.log(power_status)
            checkboxVal(1,0,power_status,'開電','關電')
            // $.each(power_status,(n,v)=>{
            //     console.log(n,v)
            //     if(v){
            //             console.log('Yes')
            //             checkboxVal(1,0,v)
            //     }else{
            //             checkboxVal(1,0,v)
            //             console.log('NO')
            //     }
            // })
        }else{
            checkboxVal(1,0,power_status,'開電','關電')
            // console.log('NO')
        }
        
        form_id.removeClass("d-none");
    }else{
        Manage_alert(data, '無此房號，請重新查詢！');
        // Manage_alert(data.room_name,'無此房號，請重新查詢！')
        form_id.addClass("d-none");
    }
}

/*適用：管理設定_模式切換、房租設定、延遲繳費，
        單間/全部房間的確認更新送出時觸發，後端回傳result和對應的message
*/ 
let charge_all_alert = (data)=>{
    console.log('data',data);
    if(data.result){
        Manage_alert(data.result,data.message);
    }else{
        Manage_alert(data.result,data.message);
    }
};

// 收費&金額manage-pay.html
    // 渲染資料
    let manage_pay = (data,id)=>{
        // console.log(data)
        if(data){
            // if(data.room_name != ''){
            var form_id = $(id).next();

            // 抓取附加費數據
            const extra_pay = data.room_pay_settings;
            // 取所有key name 存成陣列
            const extra_keys = Object.keys(extra_pay[0]);
            // console.log('extra_keys',extra_keys);
            
            /*  撈網頁的所有附加費name值 跟 keyname比對，
                指定相對應的input屬性 
            */
            let extra_input = () => {
                $('#surcharge').find('input').each(function(n,v){
                    const input_name = $(v).attr('name');
                    if(input_name == extra_keys[0]){
                        $(this).prop({type:'hidden'})
                        // console.log(this);
                    }else if(input_name == extra_keys[1]){
                        $(this).prop({type:'text'})
                        // console.log(this);
                    }else if(input_name == extra_keys[2]){
                        $(this).prop({type:'number',min:0,max:9999999,step:1})
                        // console.log(this);
                    }else{
                        $(this).prop({type:'text'})
                    }
                })
            };


            //結算日、房租、用電費率 賦值
            $.each(data,(i,v)=>{
                form_id.find('[name="'+i+'"]').val(v);
            });
            //結算日、房租、用電費率 input屬性附值
            InputNum(1,0,true,0,31,1);
            InputNum(1,1,true,0,9999999,1);
            InputNum(1,2,true,0,999,0.1);

            // 創建附加費用：依key name數量，產生input並賦值
            $.each(extra_pay,(i)=>{
                $('#surcharge').append(`<div>`);

                for(let j in extra_pay[i]){
                    // console.log('j_keyname',j)
                    // console.log('cc[i][j]_j_key_val',cc[i][j])
                    const val = extra_pay[i][j];
                    $('#surcharge').append(
                        `<input name="${j}" value="${val}" class="form-control">`
                    );

                }
                $('#surcharge').append(`</div><p class="w-100"></p>`);

                /* 附加費input屬性附值：
                    撈網頁所有附加費name值 跟 extra_pay_keys key:name比對，
                */
                extra_input();
            });
            form_id.removeClass("d-none");


            /* 開發過程
                // 取所有value 存成陣列
                // const cc_values = Object.values(cc)
                // console.log('cc_values',cc_values);
                // 取所有key name 、 value 存成陣列
                // const cc_entries = Object.entries(cc).length
                // console.log('cc_entries.length',cc_entries.length);

            
                雛形:欄位有變動時，須添加一併
                // 縱向動態
                $.each(cc,(i,v)=>{
                    // console.log(i,v,n)
                    let n = 0
                    $('#surcharge').append(
                        `
                        <input type="hidden" name="${extra_keys[n]}" value="${v.id}" class="form-control">
                        <input type="text" name="${extra_keys[n+1]}" value="${v.extra_pay_name}" class="form-control">
                        <input type="number" name="${extra_keys[n+2]}" value="${v.extra_pay_value}" class="form-control">
                        <p class="w-100"></p>
                    `)
                })
                // 依序撈遍歷資料，並找出對應n索引名的name，分別放入對應data，故因此re不會被放到name中
                // $.each(data,(n,v)=>{
                //     form_id.find('[name="'+n+'"]').val(v)
                // })
            */

        }else{
            Manage_alert(data, '無此房號，請重新查詢！');
            // Manage_alert(data.room_name,'無此房號，請重新查詢！')
            form_id.addClass("d-none");
        }
    };
    // 送出資料_特殊個案
    let data_json = (id) => {
        var params = $(id).serializeArray();
        var list = $(id).serializeArray();
        var _list = [];
        console.log('params',params);
        console.log('list',list);
        console.log('_list',_list);
        
        $.each(params,function(num,val){
                if(val.name == 'room_pay_setting_id')
                { 
                    var _dic = {}
                    _dic[params[num].name] = params[num].value;
                    _dic[params[num+1].name] = params[num+1].value;
                    _dic[params[num+2].name] = params[num+2].value;
                    list.splice(3,3);
                    _list.push(_dic);
                }
        });
            // list.push({name: 'room_pay_settings', value: _list});
            
            // room_pay_settings轉JSON
            list.push({name: 'room_pay_settings', value: JSON.stringify(_list)});
            console.log('list_fin',list);
            return  list
    }
      
    
// 控制器註冊manage-ctrl.html
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

/* isIcon
    用於：串接API icon的顯示/隱藏
        isName = 要顯示的字串,
        keyname = API的值,
        keyval = 人為輸入要判斷的值
*/
let isIcon = (isName,keyname,keyval) => (keyname == keyval) ? '': isName;

// WIFI管理
let manage_wifi_render = (data,id)=>{
    // console.log('data,id',data,id);
    $(id + ' tbody').empty();
    // 若資料抓成功
    if(data.result){
        // 資料處理&渲染
        // 資料manage_ctrl
        // const wifi_array = data.manage_ctrl;

        // 過濾出ctrl_status=1啟用的資料_適用於不顯示"已停用"的控制器
        const wifi_array2 = data.manage_ctrl.filter(function(item,index,arr){
            // console.log(item,index);
            return item.ctrl_status == 1;
        });
        
        // 抓要渲染的keyname
        let wifi_key = index => Object.keys(data.manage_ctrl[0])[index]; 

        // 渲染資料
            // $.each(wifi_array,function(i){
            $.each(wifi_array2,function(i){
                    $(id + ' tbody').append(`
                        <tr>
                            <td>
                                ${i+1}
                                <input type="hidden" required name="${wifi_key(0)}" value="${this.ctrl_machine_id}"></td>
                            </td>
                            <td>
                                ${isIcon(`<a href="#" title="停用" onclick="CtrlStop('${wifi_key(0)}',${this.ctrl_machine_id},'${wifi_key(1)}',${this.ctrl_status})">
                                            <i class="fas fa-ban"></i>
                                            </a>`,
                                          this.type,0
                                )}
                            </td>
                            <td>${this.serial_number}</td>
                            <td>${this.serial_pwd}</td>
                            <td><input type="text" required class="form-control col" name="${wifi_key(5)}" pattern="^.{0,32}$" value="${this.wifi_ssid}"></td>
                            <td><input type="text" required class="form-control col" name="${wifi_key(6)}" value="${this.wifi_pwd}"></td>
                        </tr>
                    `);
            });
    }else{
        Manage_alert(data.result,data.message);
    }

}
// post
let manage_wifi_post = (data,id)=>{

}


/*  add_option(now_option)
    作用：添加 option 到html中，並將當前資料所選的選項添加selected
    now_option:當前資料所選的選項value
    arr_obj:陣列中存放的物件，其中物件屬性統一為id、name
*/ 
    const add_option = (now_option,arr_obj)=>{
        // console.log('now_option',now_option);
        let option = '';
        arr_obj.forEach((item,i)=>{
            let id = item.id;
            let name = item.name;
            let selected = (id == now_option) ? 'selected' : '';
            let other_option = `<option value="${id}" ${selected}>${name}</option>`;
            return option += other_option
        });
        return option
};



// 開發測試用
let charge_single_rate = (data,id)=>{
    if(data){
        var form_id = $(id).next()
        $.each(data,(n,v)=>{
            form_id.find('[name="'+n+'"]').attr("value",v)
        })

        /***費率&固定金額相關判斷，有對應的欄位才會執行的功能**/ 
        // 若數據中有啟用固定收費，且為1啟用則checkbox打勾，否0不啟用則不打勾
        const check_data = [data.fixed_electricity_bill_enable,data.fixed_water_bill_enable]
        if(check_data){
            $.each(check_data,(n,v)=>{
                if(v == 1){
                    $(form_id).find(`[type="checkbox"]:eq(${n})`).prop("checked",true)
                }else{
                    $(form_id).find(`[type="checkbox"]:eq(${n})`).prop("checked",false)
                }
            })
            // const rate_data = [data.electricity_rate,data.water_rate]
            // const fixed_data = [data.fixed_electricity_bill,data.fixed_water_bill]
            // 初始化賦值+觸發click顯示對應的畫面，且畫面切換不清空值
            BillRate3(1,0,3,0);
            BillRate3(1,1,4,1);
            // BillRate3(1,0,3,0,rate_data,fixed_data);
            // BillRate3(1,1,4,1,rate_data,fixed_data);
        }
        form_id.removeClass("d-none");
    }else{
        Manage_alert(data, '無此房號，請重新查詢！')
        // Manage_alert(data.room_name,'無此房號，請重新查詢！')
        form_id.addClass("d-none");
    }
}

/* 缺陷:每次都要重新加載HTML 
    let charge_lateday_test = (data)=>{
        console.log(data)
        $('form:eq(1)').empty();
        $('form:eq(1)').append(
            `
                <label class="label-center col">延遲天數</label>  
                <input  min="1" max="10" type="number" class="form-control col-8 offset-2" required name="price" value="`+ data.late_day +`"  placeholder="Ex:10"  onkeyup="value=value.replace(/^|[^\d]+/g,'')">
                <input type='hidden' name='id'  value='`+ data.id +`'>
                <input type='hidden' name='name'  value='`+ data.name +`'>
                <input type='hidden' name='room_id'  value='`+ data.room_id +`'>
                <input type='hidden' name='room_name' value='`+ data.room_name +`'>
                <button class="btn btn-green2 btn-primary2 col-7 mt-4">確認更新</button>
            `                                
        )
    }
*/


/* 存放原先寫法
    let powerNowmeter_old = (data) => {
        // 預設初始化，清空查詢結果、更新時間
        $('#now-meter,h5.text-gray-900').empty();
        $('#search-data button:eq(0)').removeClass("d-none")
        // 當前更新時間
        var cur_time = '更新時間:'+ new Date().toLocaleString('zh-TW',{hour12:false});
        $('h5.text-gray-900').append(cur_time);
        
        // 呈現對應資料到指定的HTML(HTML中的覆用性高的詞彙可用)
        const textcolor =['text-gray','text-lightgreen'];
        $.each(data,function(num,val){
                var status = `<i class="fas fa-circle pr-2"></i>`
                                + val.powerstatus +
                            `</span>`;
                if(val.powerstatus =='開電'){
                    var status=`<span class="`+textcolor[1]+`">`+ status
                }else{
                    var status =`<span class="`+textcolor[0]+` ">`+ status;
                }
                $("#now-meter").append(
                    `<div class="col-lg-3 col-md-6 col-12 mb-4">
                        <div class="card card-green text-center p-2">
                            <div class="mb-3"><h5>【`+val.room_name+`】</div>
                            <div class="col-12 text-left">
                                <span class="d-inline-block w-50">
                                    <i class="fas fa-cog pr-2"></i>`+val.mode+`
                                </span>`
                                +status+
                                `<p>電錶：`+val.newest_electricity_degree+`度</p>
                                <p>水錶：`+val.newest_water_degree+`度</p>
                            </div> 
                        </div> 
                    </div>`                                
                )
        })

        // // 匯出按鈕點擊後，下載CSV
        // $('#search-data button').on('click',()=>{
        //     createCsvFile('電錶現況.csv',data)
        // });
    }
*/