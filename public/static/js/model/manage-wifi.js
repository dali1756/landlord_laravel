/* 規劃WIFI編輯頁
	>進入到該頁後，自動執行請求get
	>資料抓回來，處理&渲染
		=>生成欄位&
		=>物件keyname欄位名、再賦予值
			(同時撈到keyname、val)
		=>最後必填


	>確認送出>資料傳送的型態
		(紀錄修改前後)

	>操作_停用的判斷	
	>停用功能製作>點選後執行並自動刷新請求頁面

    // 純讀值判斷
    // "ctrl_machine_id":1,
    // "serial_number":"a1",
    // "serial_pwd":"1",
    // "ctrl_status":"啟用",
    // "type":0,

    // 要能修改的
    // "type":0,
    //"wifi_ssid":"222222222222222222222",
    // "wifi_pwd":"22"


*/
// WIFI管理
let manage_wifi = (data,id)=>{
    console.log('data,id',data,id);
    // 若資料抓成功
    if(data.result){
        // 資料處理&渲染
        // 資料manage_ctrl
        const wifi_array = data.manage_ctrl;
        const wifi_keyname = Object.keys(data.manage_ctrl[0]);
        console.log('wifi_keyname',wifi_keyname);


        // 單純顯示資料
        let show_data = $.each(wifi_array,(i,n)=>{
            // console.log('i,n',i,n);
            let wifi_obj = wifi_array[i];
            console.log('wifi_obj',wifi_obj);
            var content = `<tr>`;
            for(let key in wifi_obj){
                console.log('key_name,key_val:',key,wifi_obj[key]);
                // console.log('key:',key);
                if(key != 'type'){
                    content += `
                        <td>${wifi_obj[key]}</td>
                    `;
                }
            }
            content += `</tr>`;

            // 可編輯的資料
            let edit_data='' 

            // 渲染資料結果
            // console.log('content',content);
            $(id+' tbody').append(content);
            
        });
        // console.log('show_data',show_data);

    }else{
        Manage_alert(data.result,data.message);
    }
}