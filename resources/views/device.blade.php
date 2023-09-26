@include('header_layout')
@include('nav')
<link href="{{ asset('assets/css/device.css') }}" rel="stylesheet">
<section class="wrapper">
    <div class="col-12 btn-back"><a href="manage" ><i class="fas fa-chevron-circle-left fa-3x"></i><span class='back-font'></span></a></div>
    <div class="row container-fluid mar-bot50 mar-center2">
        @if ($errors->any())
            <div class="col-12 row container-fluid mar-bot50 mar-center2">
                <div style="margin: 0 auto; text-align: center;  width: 600px;" class="alert alert-success" role="alert">
                    @foreach ($errors->all() as $error)
                        <strong>{{ $error }}<br></strong> 
                    @endforeach
                </div>
            </div>
        @endif
    </div>
    <div class="container">
        <h1 class="mb-4 text-center">裝置設定</h1>
        <div class="row justify-content-center">
            <div class="col-12">
                <form id="add-member" method="post" action="add_device">
                    @csrf
                    <h3 class="card-header">新增裝置</h3>
                    <div class="form-row">
                        <div class="col-md-3">
                            <input type="text" name="device_name" class="form-control mb-2" required placeholder="裝置名稱">
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="device_number" class="form-control mb-2" required placeholder="序號">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-loginfont d-block mr-auto">新增</button>
                        </div>
                    </div>
                </form>
            </div>
            @if($device->isNotEmpty())
            <div class="col-lg-12">
                <form id="mform1" method="post" action="revise_device">
                    @csrf
                    <div class="table-responsive">
                        <table class="table  text-center">
                            <thead class="thead-green">
                                <tr class="text-center">
                                    <th scope="col">
                                        <div class="checkbox-table">
                                            <input type="checkbox" id="CheckAll" name="CheckAll">
                                            <label for="CheckAll" class="text-white">批次修改</label>
                                        </div>
                                    </th>
                                    <th scope="col">ID</th>
                                    <th scope="col">裝置名稱</th>
                                    <th scope="col">序號</th>
                                </tr>
                            </thead>
                            <tbody id="device">
                                @foreach($device as $i => $row)
                                    <tr>
                                        <td>
                                            <div class="checkbox-table">
                                                <input type="checkbox" id="Chk{{ $i }}" name="device_id[]" value="{{ $row->id }}" onclick="checkOne(this)">
                                                <label for="Chk{{ $i }}"></label>
                                            </div>
                                        </td>
                                        <td>{{ $i+1 }}<input type="hidden" name="hidden_id[]"  value="{{ $i+1 }}" disabled></td>
                                        <td><input type="text" name="device_name[]"  value="{{ $row->name }}" disabled></td>
                                        <td><input type="text" name="device_number[]"  value="{{ $row->mac }}" disabled></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-12 mb-4">
                        <button type="submit" class="btn btn-loginfont d-block mx-auto">確認修改</button>
                    </div>
                </form>
            </div>
            @endif
        </div>
    </div>
</section>
@include('footer_layout')
<script>

    let deviceId = $('input[name="device_id[]"]');  
    let deviceItem = $('input[name="device_name[]"], input[name="device_number[]"], input[name="hidden_id[]"]');
    let checkCss = 'bg-white';
    // 全選
    // true checkbox打勾  裝置名稱&序號取消disabled
    // false checkbox打勾  裝置名稱&序號取消disabled
    const checkAll = ()=>{
        let checkAll = $('#CheckAll');
        checkAll.on('click',function(){
            let isChecked = $(this).prop('checked');
            let checkDevice = deviceId.prop('checked',isChecked);
            let checkDevice2 = deviceItem.prop('disabled',!isChecked);
            const returnDevice = deviceId.map(()=>{checkDevice;});
            const returnDevice2 = deviceItem.map(()=>{checkDevice2;});
            const isDeviceCheck2 = isChecked ? deviceItem.addClass(checkCss):deviceItem.removeClass(checkCss);
            // const isDeviceCheck = isChecked && (returnDevice);
        });
    };

    // 單選-onclick版
    const checkOne = (id)=>{
            let isChecked = $(id).prop('checked');
            const findDevice = $(id).closest('tr').find('input[type="text"],input[type="hidden"]');
            let checkDevice2 = findDevice.prop('disabled',!isChecked);
            const isDeviceCheck1 = isChecked && (checkDevice2);
            const isDeviceCheck2 = isChecked ? findDevice.addClass(checkCss):findDevice.removeClass(checkCss);
            // console.log('isChecked',isChecked);
            // console.log('checkDevice2',checkDevice2);
    };

    // 單選-自動遍歷版
    const checkOne_map = ()=>{
        deviceId.map(function(){
            let isChecked = $(this).prop('checked');
            const findDevice = $(this).closest('tr').find('input[type="text"],input[type="hidden"]');
            let checkDevice2 = findDevice.prop('disabled',!isChecked);
            const isDeviceCheck = isChecked && (checkDevice2);
            const isDeviceCheck2 = isChecked ? findDevice.addClass(checkCss):findDevice.removeClass(checkCss);
            // console.log('this',this);
        })
    };
    checkAll();
    document.getElementById('mform1').addEventListener('submit', function(event) {
        var checkboxes = document.querySelectorAll('input[name="device_id[]"]');
        var isChecked = false;

        // 檢查是否有勾選的 checkbox
        checkboxes.forEach(function(checkbox) {
            if (checkbox.checked) {
                isChecked = true;
            }
        });

        // 如果沒有勾選，禁止表單提交
        if (!isChecked) {
            alert('請勾選要變更的資料！');
            event.preventDefault();
        } else {
            // 在提交之前顯示警告提示框
            var confirmed = confirm("是否確認送出修改?");
            
            if (!confirmed) {
                // 如果使用者點擊取消，阻止表單提交
                event.preventDefault();
            }
        }
    });
</script>

