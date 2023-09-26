/* ICON切換頁_IconPage(url,type,dataType,pageTitle) */
/***********************************************************************
    IconPage(url,type,dataType,pageTitle)
    適用頁面：ICON切換頁
    url：'API路徑'
    type：'請求類型'
    dataType：'資料格式'
    pageTitle：調用本函式頁面名稱
    success:function(data){}    連線成功後傳回來的data值&資料處理
    error:function(){}  連線失敗後傳回來的功能
***********************************************************************/ 
    
    // function IconPage(url,type,dataType,pageTitle,this_page_Name){
        function IconPage(data,this_page_Name,n,col_num) {
            $.ajax({
                url:url,
                type:type,
                dataType:dataType,
                success:function(data){
                    const current_url =this_page_Name.split('/')
                    let current_html = current_url[current_url.length-1]
                    $.each(data,function(num,val){
                        let pageUrl = val.pageUrl
                            if(pageUrl == current_html){
                                var b =val.showInfo
                                b.forEach(d => {
                                        var img = d.imageSrc
                                        $('#content').append(
                                        `<div class="col-lg-4 col-sm-6 py-3">
                                            <a href="` +d.iconUrl+ ` "> `+
                                                `<img class="mb-3" src="`+img+`">`+
                                                `<h4>`+d.title+`</h4>
                                            </a></div>`
                                        );
                                });
                            }
                    })
                },
                error:function(){
                    alert('出錯了!!');
                }
            })
        }
    
    // 在append中添加事件是可行的
    //  append測試內容：<a href="` +d.iconUrl+ `" onclick="`+Test()+`"> `+ 
    // function Test(){
    //     alert('HI~');
    // }

/* 載入動畫開啟/關閉_LoadingMask(load_id) / ClearMask(load_id,search_area) */
/***********************************************************************
    LoadingMask(load_id)
    適用功能：開啟loading動畫，適用於需要載入動畫的頁面
    load_id：動畫所在位置的HTML id ，ex. id="waitloading"

    function ClearMask(load_id,search_area)
    適用功能：關閉loading動畫，適用於需要結束載入動畫，並接續顯示一些資料的頁面
    load_id：動畫所在位置的HTML id ，ex. id="waitloading"
    search_area：動畫結束後，要呈現出資料的位置的HTML id

    舉例：若查詢資料筆數較多，LoadingMask、ClearMask可結合使用
***********************************************************************/ 
    function LoadingMask(load_id){
        setTimeout(()=>{
            $(load_id).append(
                `<div class="btn btn-orange" role="button">
                    <span class="spinner-border" role="status" aria-hidden="true"></span>
                    <span class="d-block">Loading...</span>
                </div>`
            )
            $(load_id).removeClass("d-none");
        },0)
    }
    function ClearMask(load_id,search_area){
        setTimeout(()=>{
            // $(load_id+'>div').remove();
            $(load_id).empty()
            $(load_id).addClass("d-none");
            $(search_area).removeClass("d-none");
        },0)
    }

/* AJAX查詢功能_SearchData(url,type,dataType,success_function) */
/***********************************************************************
    SearchData(url,type,dataType,success_function)
    適用功能：查詢ICON頁，AJAX查詢功能
    url     從後端串接的路徑
    type    資料傳遞的方法
    dataType    請求的資料格式
    beforeSend  完成success之前執行的內容
    complete    完成success之後執行的內容
    success_function 連線成功調用的function變數名稱，配合static\js\model\content.js使用
    DEMO：
    SearchData('./api/power-nowmeter.json','GET','json',powerNowmeter)
***********************************************************************/ 
    function SearchData(url,type,dataType,success_function){
        var params = $('form').eq(0).serializeArray();
        $.ajax({
            url:url,
            type:type,
            data:params,
            dataType:dataType,
            beforeSend:function(){LoadingMask('#waitloading')},
            complete:function(){ClearMask('#waitloading','#search-data');}
        })
        .then(
            function(data){success_function(data)},
            function(err){alert('連線失敗請重新再試!!');}
        )
    }

/* CSV檔案下載功能_正式用_createCsvFile(url) */
/***********************************************************************
    createCsvFile(url)
    適用：所有匯出功能
    url     後端回傳下載連結 ex. /power-nowmeter-export/
    params  查詢表單抓取到的參數
    url_string  查詢表單參數遍歷後，依對應name名稱，回傳對應value
***********************************************************************/ 
    function createCsvFile(url){ 
        var params = $('form').eq(0).serializeArray();
        url_string = "";
        $.each(params,(n,v)=>{
            url_string += v.name + "=" + v.value + "&"; 
        })

        url = url.slice(0,-1);
        url = url+ "?" +url_string;
        // console.log(url+ "?" +url_string);

        var link = document.createElement("a");
        document.body.appendChild(link);
        link.href = url;
        link.click();
    }

/* CSV檔案下載功能_origin_createCsvFile_origin (fileName,AJAXdata) */
/***********************************************************************
    createCsvFile_origin (fileName,AJAXdata)
    適用：前端產出匯出
    fileName                    下載的檔案名稱，自取或抓Django插值語法來用
    AJAXdata                    在查詢AJAX階段時，抓到的data，會進一步丟到function getRandomData處理
    new Blob()                  將getRandomData()處理好的data，裝到Blob物件，並定義資料類型的MIME格式
    URL.createObjectURL(blob)   將裝有資料的Blob物件，產生對應的Blob url連結
    var link ～ link.click();   模擬檔案下載：
                                =>建立<a></a>，存放在變數 link 內
                                =>並放在<body>內最後面
                                =>將產生的data Blob url 添加到 href 屬性內
                                =>將fileName 添加到 download 屬性內
                                =>前製事件觸發後，自動觸發<a></a>的click事件，完成下載CSV檔案

    link.href = '';             清掉暫存
    URL.revokeObjectURL(href);  清掉暫存
            =>使用時會暫存，沒有清除的話會一直佔用記憶體。
                若要從原始碼debug，可註解掉

***********************************************************************/ 
    function createCsvFile_origin(fileName,AJAXdata){
        var fileName = fileName;
        var data = "\ufeff" + getRandomData(AJAXdata);
        var blob = new Blob([data], {type : "text/csv,charset=UTF-8"});
        var href = URL.createObjectURL(blob);

        var link = document.createElement("a");
        document.body.appendChild(link);
        link.href = href;
        link.download = fileName;
        link.click();

        link.href = '';
        URL.revokeObjectURL(href);
    }

/*CSV檔案下載_JSON資料整理成CSV_getRandomData(data)*/
/***********************************************************************
    getRandomData(data)
    適用：前端匯出前端資料處理，將傳回來的JSON轉成CSV格式
    說明：
        data 等於AJAXdata
        print_date 列印日期，抓當前時間
        header  匯出的表頭(預留空間)
        
        csvArray 二維陣列，是csvContent的依據，最後所有的資料儲存的地方。
                由data(JSON_陣列對象)經過for處理後，回存結果而成

        for(var i=0; i<data.length; i++){}  data外圍的陣列遍歷，並建立新陣列items
        for(var j in data[i]){}             data中針對物件遍歷，並存成Array，每1筆obj=1組Array
        data[i]                             每1筆obj
        data[i][j]                          每1筆obj內的每個value值
        items                               兩次for迴圈遍歷的結果，每1筆obj=1組Array，最後統一由items push 到csvArray

        dataString =  val.join(',')+'\n';   遍歷完成的字串資料。由csvArray的第二層陣列val，
                                            每1 val陣列，經過join(',')區分後再斷行轉成字串，再繼續抓下1個val處理
        csvContent                          要放到CSV中的內容，依據csvArray遍歷，再處理成dataString後，添加進csvContent
************************************************************************/ 
    function getRandomData(data) {
        var print_date = '列印日期:'+ new Date().toLocaleString('zh-TW',{hour12:false})+'\n';
        // var header = "第一欄,第二欄,第三欄,第四欄,第五欄\n";
                let csvArray = []
                for(var i=0; i<data.length; i++){
                    let items = [];
                    for(var j in data[i]){
                        items.push(data[i][j])
                    }
                    csvArray.push(items);
                }
                let csvContent = ''
                $.each(csvArray,(i,val)=>{
                    let dataString =  val.join(',')+'\n';
                    csvContent += dataString
                })
        return print_date+csvContent;
    }

/* 表單屬性控制_InputNum(form_id,required,min,max,step)*/
/***********************************************************************
    InputNum(form_id,num,required,min,max,step)
    適用功能：表單input[type="number"]初始屬性參數設置
    form_id     陣列變數form_id中的id索引值
    num         表單中第幾個input[type="number"]
    required    T/F 是否必填
    min         輸入最小值
    max         輸入最大值
    step        遞加/遞減值
************************************************************************/ 
    function InputNum(id,num,required,min,max,step){
        const input_num = $(form_id[id]+' input[type="number"]:eq('+num+')');
        input_num.attr({
            min:min,
            max:max,
            step:step
        });
        input_num.prop({required:required});
    }

    // 實驗正規表達製作設定各欄位數值規範
    // $(form_id+' input[type="number"]:eq('+num+')').on('keyup',function(e){
    //     console.log(e.target.value)
    //     if(num > 1){
    //         // 固定電費、固定水費 =>只能輸入正整數(不含0)
    //         var a = /^\-[1-9][0-9]*$/g;
    //         e.target.value = e.target.value.replace(a,'')
    //     }else{
    //         return false
    //     }
    // });
    
/* 表單屬性控制_checkboxVal(id,num,val,check_on,check_off)*/
/***********************************************************************
    checkboxVal(id,num,val,check_on,check_off)
    適用功能：表單input[type="checkbox"]初始屬性參數設置
    id          陣列變數form_id中的id索引值
    num         表單中第幾個input[type="checkbox"]
    val         存放checkbox 預設value值
    check_on    checkbox 觸發打勾時，顯示的文字
    check_off   checkbox 觸發不勾時，顯示的文字(預設)
************************************************************************/ 
    function checkboxVal(id,num,val,check_on,check_off){
        const check_setting = $(form_id[id]+ ' input[type="checkbox"]:eq('+num+')')
        check_setting.prop({
            'checked' : val,
            'value' : val
        });
        // 預設值判斷：取決於val
        if(val){
            // console.log(val);
            $(check_setting).next().text(check_on);
        }else{
            // console.log(val);
            $(check_setting).next().text(check_off);
        }
        
        check_setting.on('click',function(){
            if($(this).prop("checked")){
                // 打勾
                // console.log(this)
                check_setting.prop({
                    'checked' : true,
                    'value' : true
                });
                $(this).next().text(check_on)
            }else{
                // 不打勾
                check_setting.prop({
                    'checked' : false,
                    'value' : false
                });
                $(this).next().text(check_off)
                // console.log(this)
            }
        })
    }

/* 表單欄位交互隱藏_BillRate(n,x,y,z)*/
/***********************************************************************
    BillRate(n,x,y,z)
    適用功能：表單中依據，啟用/不啟用checkbox固定收費，產生對應的畫面&動態賦值
    n   對應變數 form_id 陣列索引值
    x   表單中，第x個啟用固定收費checkbox
    y   表單中，第y個樣式=form-group的div區域
    z   表單中，第z個樣式=form-group的div區域
    bill_enable 啟用固定收費checkbox(固定電費/水費)
    fixed_bil   固定電費/水費_input div區塊
    rate        用電/水費率 div區塊
    RateShow    前端表單欄位交互畫面&動態賦值的功能
    調用時機：初始化、checkbox觸發click，顯示對應的畫面
************************************************************************/ 
    function BillRate(n,x,y,z){
        const bill_enable = $(form_id[n]+' [type="checkbox"]:eq('+x+')')
        const fixed_bill = $(form_id[n]+' .form-group:eq('+y+')')
        const rate = $(form_id[n]+' .form-group:eq('+z+')')
        const RateShow = ()=>{
            if($(bill_enable).prop("checked")){
                // 打勾
                    // 畫面：出現固定水/電費、隱藏用水/電費率
                        rate.addClass("d-none");
                        fixed_bill.removeClass("d-none");
        
                    // 動態賦值
                        // checkbox固定水/電費：打勾且value=true
                        bill_enable.prop({
                            'checked' : true,
                            'value' : true
                        })
                        // 水/電費率：選填
                        rate.find('input').prop('required',false);
                        // 固定水/電費：必填
                        fixed_bill.find('input').prop('required',true);
        
                    //畫面&賦值交互帶動 
                        // 若是全部房間設定，則水/電費率，input值會隨隱藏而清空
                        (n == 2) && ( rate.find('input').prop({'value':''}) );
            }else{
                // 不勾
                    // 畫面：出現用水/電費率、隱藏固定水/電費
                        rate.removeClass("d-none");
                        fixed_bill.addClass("d-none");
        
                    // 動態賦值
                        // checkbox固定水/電費：不打勾且value=false
                        bill_enable.prop({
                            'checked' : false,
                            'value' : false
                        })
                        // 水/電費率：必填
                        rate.find('input').prop('required',true);
                        // 固定水/電費：選填
                        fixed_bill.find('input').prop('required',false);
                    
                    //畫面&賦值交互帶動 
                        // 若是全部房間設定，則固定水/電費率，input值會隨隱藏而清空
                        (n == 2) && ( fixed_bill.find('input').prop({'value':''}) );
            }
        };
        RateShow();
        bill_enable.on("click",function() {
            RateShow();
        });
    }

    function BillRate_origin(n,x,y,z,rate_data,fixed_data){
        const bill_enable = $(form_id[n]+' [type="checkbox"]:eq('+x+')')
        const fixed_bill = $(form_id[n]+' .form-group:eq('+y+')')
        const rate = $(form_id[n]+' .form-group:eq('+z+')')
        // const rate_array = rate_data
        // const fixed_bill_array= fixed_data
        // console.log(fixed_bill_array)
        // 用途:初始化賦值
        // RateShow(n,bill_enable,fixed_bill,rate,'','');
        RateShow(n,bill_enable,fixed_bill,rate);
        // 固定收費checkbox，觸發click顯示對應的畫面
        bill_enable.on("click",function() {
            // RateShow(n,bill_enable,fixed_bill,rate,rate_array[z],fixed_bill_array[y])
            RateShow(n,bill_enable,fixed_bill,rate)
        });
    }

/* 表單欄位交互&動態賦值_RateShow() */
/***********************************************************************
    RateShow(n,bill_enable,fixed_bill,rate)
    適用功能：表單
    n   對應變數 form_id 陣列索引值
    bill_enable 啟用固定收費checkbox(固定電費/水費)
    fixed_bil   固定電費/水費_input div區塊
    rate        用電/水費率 div區塊
*********************************************************************** 
    function RateShow_origin(n,bill_enable,fixed_bill,rate){
        if($(bill_enable).prop("checked")){
            // 打勾
                // 畫面：出現固定水/電費、隱藏用水/電費率
                    rate.addClass("d-none");
                    fixed_bill.removeClass("d-none");

                // 動態賦值
                    // checkbox固定水/電費：打勾且value=true
                    bill_enable.prop({
                        'checked' : true,
                        'value' : true
                    })
                    // 水/電費率：選填
                    rate.find('input').prop('required',false);
                    // 固定水/電費：必填
                    fixed_bill.find('input').prop('required',true);

                //畫面&賦值交互帶動 
                    // 若是全部房間設定，則水/電費率，input值會隨隱藏而清空
                    (n == 2) && ( rate.find('input').prop({'value':''}) );

        }else{
            // 不勾
                // 畫面：出現用水/電費率、隱藏固定水/電費
                    rate.removeClass("d-none");
                    fixed_bill.addClass("d-none");

                // 動態賦值
                    // checkbox固定水/電費：不打勾且value=false
                    bill_enable.prop({
                        'checked' : false,
                        'value' : false
                    })
                    // 水/電費率：必填
                    rate.find('input').prop('required',true);
                    // 固定水/電費：選填
                    fixed_bill.find('input').prop('required',false);
                
                //畫面&賦值交互帶動 
                    // 若是全部房間設定，則固定水/電費率，input值會隨隱藏而清空
                    (n == 2) && ( fixed_bill.find('input').prop({'value':''}) );
            
        }
    }

    // function RateShow(n,bill_enable,fixed_bill,rate,rate_array,fixed_bill_array){
    function RateShow_develop(n,bill_enable,fixed_bill,rate){
        if($(bill_enable).prop("checked")){
            // 打勾，出現固定水/電費
            bill_enable.prop('value',true)
            bill_enable.prop("checked",true)
            rate.find('input').prop({'required':false});
            // 當form_id[n]索引未對應到，個別設定表單索引，則用水、電費率值會隨隱藏而清空
            if(n!=1){
                rate.find('input').prop({'value':''});
            }
            rate.addClass("d-none");
            fixed_bill.removeClass("d-none");
            fixed_bill.find('input').prop('required',true);

            // 費率系列input，要還原成原先抓到的值
            // rate.find('input').prop({'value':rate_array})
            // console.log('zz',zz);
        }else{
            bill_enable.prop('value',false)
            bill_enable.prop("checked",false)
            // 不勾，出現用水/電費率
            rate.removeClass("d-none");
            rate.find('input').prop('required',true);

            fixed_bill.find('input').prop({'required':false});
            // 當form_id[n]索引未對應到，個別設定表單索引，則固定水電值會隨隱藏而清空
            if(n!=1){
                fixed_bill.find('input').prop({'value':''});
            }
            // 固定水電系列input，要還原成原先抓到的值
            // fixed_bill.find('input').prop({'value':fixed_array})
            // fixed_bill.find('input').prop({'value':fixed_bill_array})
            fixed_bill.addClass("d-none");
        }
    }
*/


/* 管理設定_抓取所有checkbox值_form_data(id)*/
/***********************************************************************
    form_data(id)
    適用功能：管理設定_表單中_不論勾或不勾，會抓取所有checkbox值
    id     form的id
    備註：會配合_PostData() 一起使用
    說明：抓沒被勾的checkbox，撈name、value值，遍歷存成對象塞回給serializeArray
************************************************************************/
    // $.fn.new_serialize = function(id){
    function form_data(id){
        const a = $(id).find('input[type="checkbox"]:not(:checked)')
        const params = $(id).serializeArray()
        if(a){
            $.each(a,(i,n)=>{
                var z = {name:$(n).prop('name'),value:$(n).val()}
                params.push(z)
                // console.log('z',z)
                // params.splice(從第x個之後增加,刪除數,資料不加會返回空數組);
                // params.splice(2,0,z);
            })
        }
        // console.log('new_serialize_params',params);
        return params;
    }

/* 管理設定用的AJAX_PostData(element,url,type,dataType,success_function)*/
/***********************************************************************
    PostData(id,url,type,dataType,success_function)
    適用功能：管理設定ICON頁，AJAX查詢、設定功能
    id     form的id
    url         從後端串接的路徑
    type        資料傳遞的方法
    dataType    請求的資料格式
    beforeSend  完成success之前執行的內容
    complete    完成success之後執行的內容
    success_function(data,element) 連線成功調用的function變數名稱，配合static\js\model\content.js使用
                                    會回傳data,element
    DEMO：
    PostData(id,url,'post','json',charge_single)
************************************************************************/
    function PostData(id,url,type,dataType,success_function){
        var params = form_data(id);
        console.log('PostData',params);
        $.ajax({
            url:url,
            type:type,
            data:params,
            dataType:dataType,
            beforeSend:function(){LoadingMask('#waitloading')},
            complete:function(){ClearMask('#waitloading','')}
        })
        .then(
            function(data){success_function(data,id)},
            function(err){alert('連線失敗請重新再試!!');}
        )
    }

    function PostData2(id,url,type,dataType,success_function,params,async){
        var params = params;
        console.log('PostData2_params',params);
        $.ajax({
            url:url,
            type:type,
            data:params,
            dataType:dataType,
            async:async,
            beforeSend:function(){LoadingMask('#waitloading')},
            complete:function(){ClearMask('#waitloading','')}
        })
        .then(
            function(data){success_function(data,id)},
            function(err){alert('連線失敗請重新再試!!');}
        )
    }

    function PostData_origin(id,url,type,dataType,success_function){
        var params = $(id).serializeArray();
        $.ajax({
            url:url,
            type:type,
            data:params,
            dataType:dataType,
            beforeSend:function(){LoadingMask('#waitloading')},
            complete:function(){ClearMask('#waitloading','')}
        })
        .then(
            function(data){success_function(data,id)},
            function(err){alert('連線失敗請重新再試!!');}
        )
    }

/* 管理設定表單流程_ManageForm_origin(id,url)*/
/***********************************************************************
    ManageForm_origin(id,url)
    適用功能：管理設定，複雜版合管家初期開發版本，不含sweet-alert套件
              有全部房間設定、個別房間查詢、查詢後顯示修改的個別房間表單，共3個表單的流程走向
    form_id 當前頁面的表單id，會寫在有用此功能的各個HTML檔內
            form:eq(0)表示當前頁面索引值第0的，個別房間表單，
    id      表單的id，統一存在form_id  
    url     各表單的後端api連結
    備註：若有增加新form需求，如：整層樓設定，HTML結構、form_id請往後遞增，並且if判斷也是
************************************************************************/
    function ManageForm_origin(id,url){
        if(id == form_id[0]){
            PostData(id,url,'GET','json',charge_single)
        }else if( id === form_id[1]){
            if(confirm('確認更新?')){
                PostData(id,url,'POST','json',charge_all_alert)
            }else{
                return false
            }
        } else {
            let prompt_text = prompt('確認更新?\n請輸入:Yes(大小寫皆須相符)');
            if( prompt_text == 'Yes'){
                PostData(id,url,'POST','json',charge_all_alert)
            }else{
                return false
            }
        }
    }

/* 管理設定表單流程_ManageForm_origin_fin(id,url)*/
/***********************************************************************
    ManageForm_origin_fin(id,url)
    適用功能： 管理設定，複雜版合管家最後整合的版本，不含sweet-alert套件
    說明：表單的流程走向，全部、個別房間查詢、及查詢後顯示個別房間表單，共3個
    form_id 當前頁面的表單id，會寫在有用此功能的各個HTML檔內
            form:eq(0)表示當前頁面索引值第0的，個別房間表單，
    id      表單的id，統一存在form_id  
    url     各表單的後端api連結
    swal    sweet-alert套件，依表單的流程，觸發對應的彈窗
    備註：若有增加新form需求，如：整層樓設定，HTML結構、form_id請往後遞增，並且if判斷也是
************************************************************************/
    function ManageForm_origin_fin(id,url){
        if(id == form_id[0]){
            PostData(id,url,'GET','json',charge_single)
        }else if( id === form_id[1]){
            swal({
                title: '確認更新?',
                text: "將修改設定，確認更新?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3366CC',
                cancelButtonColor: '#FF8367',
                confirmButtonText: '是',
                cancelButtonText: '否',
                })
                .then((isConfirm) => {
                    if (isConfirm) {
                        PostData(id,url,'POST','json',charge_all_alert)
                    }
                })
        } else if( id === form_id[2]){
                swal({
                    title: '確認更新?',
                    text: "請輸入:Yes",
                    input: 'text',
                    confirmButtonColor: '#3366CC',
                    cancelButtonColor: '#FF8367',
                    confirmButtonText: '是',
                    cancelButtonText: '否',
                    showCancelButton: true,
                    inputValidator: function(value) {
                        return new Promise(function(resolve, reject) {
                                if (value == 'Yes') {
                                    resolve();
                                }else if(value == ''){
                                    reject('必填，且大小寫皆須相符！');
                                }else{
                                    reject('輸入錯誤，請重新輸入！');
                                }
                        });
                    }
                })
                .then((result) => {
                        if (result) {
                            PostData(id,url,'POST','json',charge_all_alert)
                        }
                    }
                )
        }else{
            return false
        }
    }

/* 管理設定表單流程_ManageForm(id,url,page_var)*/
/***********************************************************************
    ManageForm(id,url,page_var)
    適用功能： 管理設定，合管家簡易版使用
    說明：表單的流程走向，全部、個別房間查詢、及查詢後顯示個別房間表單，共3個
    form_id 當前頁面的表單id，會寫在有用此功能的各個HTML檔內
            form:eq(0)表示當前頁面索引值第0的，個別房間表單，
    id          表單的id，統一存在form_id  
    url         各表單的後端api連結
    page_var    要執行調用的頁面變數名稱
    swal    sweet-alert套件，依表單的流程，觸發對應的彈窗
    備註：若有增加新form需求，如：整層樓設定，HTML結構、form_id請往後遞增，並且if判斷也是
************************************************************************/
    function ManageForm(id,url,page_var){
        if(id == form_id[0]){
            PostData(id,url,'GET','json',page_var)
        }else if( id === form_id[1]){
            swal({
                title: '確認更新?',
                text: "將修改設定，確認更新?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3366CC',
                cancelButtonColor: '#FF8367',
                confirmButtonText: '是',
                cancelButtonText: '否',
                })
                .then((isConfirm) => {
                    if (isConfirm) {
                        PostData(id,url,'POST','json',charge_all_alert)
                    }
                })
        } else if( id === form_id[2]){
                swal({
                    title: '確認更新?',
                    text: "請輸入:Yes",
                    input: 'text',
                    confirmButtonColor: '#3366CC',
                    cancelButtonColor: '#FF8367',
                    confirmButtonText: '是',
                    cancelButtonText: '否',
                    showCancelButton: true,
                    inputValidator: function(value) {
                        return new Promise(function(resolve, reject) {
                                if (value == 'Yes') {
                                    resolve();
                                }else if(value == ''){
                                    reject('必填，且大小寫皆須相符！');
                                }else{
                                    reject('輸入錯誤，請重新輸入！');
                                }
                        });
                    }
                })
                .then((result) => {
                        if (result) {
                            PostData(id,url,'POST','json',charge_all_alert)
                        }
                    }
                )
        }else if( id != form_id[0] ||  id != form_id[1] ||  id != form_id[2]){
            console.log('進入資料特殊處理路線');
            PostData2(id,url,'POST','json',charge_all_alert,page_var,false);
        }else{
            return false
        }
    }


/* 管理設定_提示文字_origin__Box_origin(id_name,status,msg) */
/***********************************************************************
    Box_origin(id_name,status,msg)
    適用功能：管理設定_提示文字，從後端回傳=>前端的驗證訊息
    id_name     用Bootstrap4 Modal嵌入製作
                <!-- lightbox彈窗
                <div class="modal fade lightbox" data-keyboard="false" tabindex="-1" aria-labelledby="lightboxLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content"></div>
                    </div>
                </div> -->
    status      提示文字狀態，串接對應後端給的data.res(T/F)
    msg         對應狀態的提示文字訊息
************************************************************************/
    function Box_origin(id_name,status,msg){
        $('.modal-content').empty()
        $('.lightbox').modal('show');
        if(status){
            status = '<i class="fas fa-5x fa-check-circle"></i>'
        }else{
            status = '<i class="fas fa-5x fa-times-circle"></i>'
        }
        $('.modal-content').append(`
            <div id="`+ id_name +`" class="modal-body">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-12 label-center">
                            <p>`+ status +`</p>
                            <p>`+ msg + `</p> 
                        </div>
                    </div>		
                </div>			
            </div>`
        )
    }

/* 查詢提示文字_Search_alert()*/
/***********************************************************************
    Search_alert()
    適用功能：查詢=>查無此條件的資料時觸發
    swal      sweet-alert套件，依表單的流程，觸發對應的彈窗
************************************************************************/
    function Search_alert(){
        $('#search-data button:eq(0)').addClass("d-none")
        swal('Error', '查無資料，請重新查詢！！', 'error')
    }

/* 管理設定_提示文字_Manage_alert(res,msg) */
/***********************************************************************
    Manage_alert(status,msg)
    適用功能：管理設定_提示文字，從後端回傳=>前端的驗證訊息
    res      提示文字狀態，串接對應後端給的data.res(T/F)，也可拿回傳資料的某一欄位當判斷
    msg         對應狀態的提示文字訊息
    swal        sweet-alert套件，依表單的流程，觸發對應的彈窗
************************************************************************/
    function Manage_alert(res,msg){
        res ? 
        swal('Success',msg,'success') : swal('Error', msg, 'error')
    }
    function Manage_confirm(title,text,params){
        const confirm_option = {
            title: title,
            text,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3366CC',
            cancelButtonColor: '#FF8367',
            confirmButtonText: '是',
            cancelButtonText: '否',
        };
        swal(confirm_option)
            .then((isConfirm) => {
                if (isConfirm) {
                    PostData2(this,'./api/charge-lateday_all.json','POST','json',charge_all_alert,params,false)
                }
                return isConfirm
            }).then((isConfirm)=>{
                if (isConfirm) {
                    location.reload();
                }else{
                    return false
                }
            })
    }
/* 管理設定_提示文字_Manage_pageJump(res,msg) */
/***********************************************************************
    Manage_pageJump(status,msg)
    適用功能：管理設定_提示文字，從後端回傳=>前端的驗證訊息，並跳轉至指定頁面
    res      提示文字狀態，串接對應後端給的data.res(T/F)，也可拿回傳資料的某一欄位當判斷
    text         對應狀態的提示文字訊息，等同text:text
    pageJump     跳轉頁面的路徑位置
    swal     sweet-alert套件，依表單的流程，觸發對應的彈窗
************************************************************************/
    function Manage_pageJump(res,text,pageJump){
        let isRes = res ? 'Success':'Error';
        const alert_option = {
            title: isRes,
            text,
            type: isRes.toLocaleLowerCase(),
            confirmButtonText: res ? '確認':'返回'
        };
        swal(alert_option).then(()=>{
            window.location.href = pageJump;
        })   
    }

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

        if(n==0){
            tab.eq(n).show();
            tab.eq(n+1).hide();
        }else{
            tab.eq(n).show();
            tab.eq(n-1).hide();
        }

        if(n == (tab.length -1)){
            $(btn_id[1]).text('上一步');
            $(btn_id[2]).text('確認送出');
        }else{
            $(btn_id[0]).text('下一步');
        }
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
    }
                                    