@php
    $id = 'input-date-rang-'.mt_rand(100, 999);
    $nameAttr = isset($name)?"name='{$name}'":"";
    $readonly = isset($readonly) && 'false'==$readonly?"":"readonly";
    $placeholder = isset($placeholder)?"placeholder='{$placeholder}'":"";
    $start = isset($start)?$start:"";
    $end = isset($end)?$end:"";
    $value = !empty($start) && !empty($end)?$start.' - '.$end:@$value;
@endphp

<div class="input-group">
    <div class="input-group-prepend">
        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
    </div>
    <input id="{{ $id }}" type="text" {!! $readonly !!}
    {!! $nameAttr !!} {!! $placeholder !!} value="{{ $value }}" class="form-control form-control-sm float-right @if($errors->has($name)) is-invalid @endif">
    @if($errors->first($name)) <span class="error invalid-feedback">{{ $errors->first($name) }}</span> @endif
</div>
<script>
    Admin.boot(function () {
        $('#{{ $id }}').daterangepicker({
            singleDatePicker:{{ @$single?:'true' }},
            timePicker:{{ @$time?$time:'true' }},
            timePicker24Hour:true,
            timePickerSeconds:{{ @$second?:'true' }},
            locale: { //汉化
                format: "YYYY-MM-DD{{ @$time!=='false'?' HH:mm':"" }}{{ @$second!=='false'?':ss':"" }}", //设置显示格式
                applyLabel: '确定', //确定按钮文本
                cancelLabel: '取消', //取消按钮文本
                daysOfWeek: ['日', '一', '二', '三', '四', '五', '六'],
                monthNames: ['一月', '二月', '三月', '四月', '五月', '六月',
                    '七月', '八月', '九月', '十月', '十一月', '十二月'],
                firstDay: 1
            }
        }, function(start, end, label) {
        });
    });
</script>
