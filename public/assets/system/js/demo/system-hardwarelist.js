function GetData(url,success_function){
    var params = ''
    $.ajax({
        url:url,
        type:'get',
        data:params,
        dataType:'json',
    }).done(success_function)
    .fail((err)=>{ajax_func.error_func(err)})
}
// DEMO ver
let render_page = ()=>{
            let ng_id = $('#ng_data_div');

            let room_name = 'A001';
            let amount_110V = '110';
            let amount_220V = '220';

            let return_ng = ['【通訊狀態超時】',''];
            let return_text = ['NG','OK'];
            let return_class = ['text_ng',''];

            // 時間差計算
                let update_date = '2023-05-23 18:00:08';
                let now_time = new Date();
                let chk_time_sec = 900;
                let update_time = new Date(update_date);
                let timeDiff = (now_time -  update_time) / 1000;

                // (timeDiff > chk_time_sec)?
                //   console.log('【通訊狀態超時】',timeDiff,chk_time_sec)
                //   :
                // console.log('時間未超過15min',timeDiff);
                // console.log('now_time當前時間',now_time);
                // console.log('update_date資料上傳時間',update_time);
                // console.log('time秒數差_now_time',timeDiff);

            // 判斷呈現資料&樣式
              let is_update_date_ng = (timeDiff > chk_time_sec) ? return_ng[0]:return_ng[1];
              let is_110V_ng = (amount_110V == 0) ? return_text[0]:return_text[1];
              let is_220V_ng = (amount_220V == 0) ? return_text[0]:return_text[1] ;

              let is_upd_class = (timeDiff > chk_time_sec) ? return_class[0]:return_class[1];
              let is_110V_class = (amount_110V == 0) ? return_class[0]:return_class[1];
              let is_220V_class = (amount_220V == 0) ? return_class[0]:return_class[1];


              // 渲染
              let ng_html = `
                    <div class="col-12 col-lg-4 mb-4">
                        <div class="card mb-4 card-green text-green text-center h-100">
                            <div class="py-3">
                              <h4 class="m-0 font-weight-bold">${room_name}</h4>
                            </div>
                            <div class="px-3">
                              <p>狀態</p>
                              <div class="${is_upd_class} text-left">
                                <p><i class="fas fa-clock"></i>${is_update_date_ng}${update_date}</p>
                                <p class="${is_110V_class}"><i class="fas fa-tachometer-alt"></i>110V： ${is_110V_ng}</p>
                                <p class="${is_220V_class}"><i class="fas fa-tachometer-alt"></i>220V： ${is_220V_ng}</p>
                              </div>
                            </div>
                        </div>
                    </div>
                `;
              ng_id.append(ng_html);

}
// API link ver
const ajax_func = {
    render_page:(res)=>{
        let ng_id = $('#ng_data_div');
              const data = res.data;
              let status_text = ['conncet_normal','connect_timeout'];
              let return_ng = ['','【通訊狀態超時】'];
              let return_text = ['OK','NG'];
              let return_class = ['','text_ng'];

              for( v of data){
                    let room_name = v.name;
                    let conncet_status = v.conncet_status;
                    let update_date = v.update_date;
                    let amount_110V = v['110'];
                    let amount_220V = v['220'];

                    // 判斷呈現資料&樣式
                    let is_update_date_ng = (conncet_status == status_text[0]) ? return_ng[0]:return_ng[1];
                    let is_upd_class = (conncet_status == "conncet_normal") ? return_class[0]:return_class[1];
                    let is_110V_class = (amount_110V == return_text[0]) ? return_class[0]:return_class[1];
                    let is_220V_class = (amount_220V == return_text[0]) ? return_class[0]:return_class[1];

                    let ng_html = `
                        <div class="col-12 col-lg-4 mb-4">
                            <div class="card mb-4 card-green text-green text-center h-100">
                                <div class="py-3">
                                  <h4 class="m-0 font-weight-bold">${room_name}</h4>
                                </div>
                                <div class="px-3">
                                  <p>狀態</p>
                                  <div class="${is_upd_class} text-left">
                                    <p><i class="fas fa-clock"></i>${is_update_date_ng}${update_date}</p>
                                    <p class="${is_110V_class}"><i class="fas fa-tachometer-alt"></i>110V： ${amount_110V}</p>
                                    <p class="${is_220V_class}"><i class="fas fa-tachometer-alt"></i>220V： ${amount_220V}</p>
                                  </div>
                                </div>
                            </div>
                        </div>
                    `;
                  ng_id.append(ng_html);
              }
    },
    error_func:(err)=>{
      alert(`連線失敗\n
            HTTP狀態代碼訊息:${err.statusText}\n
            服務器返回訊息:${err.responseText}
          `);
          console.log(`連線失敗\n
            當前狀態：${err.readyState}\n
            HTTP狀態代碼:${err.status}\n
            HTTP狀態代碼訊息:${err.statusText}\n
            服務器返回訊息:${err.responseText}
          `);
    }
};

  $(document).ready(function() {
    // DEMO ver
      // render_page();

    // API link ver
        // GetData('./model/query_rent_device_status.php',ajax_func.render_page);
  });