@php
    $id = 'input-date-rang-'.mt_rand(100, 999);
    $nameAttr = isset($name)?"name='{$name}'":"";
    $valueAttr = isset($value)?"value='{$value}'":"";
    $readonly = isset($readonly) && 'false'==$readonly?"":"readonly";
    $placeholder = isset($placeholder)?"placeholder='{$placeholder}'":""
@endphp

<div class="input-group">
    <div class="input-group-prepend">
        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
    </div>
    <input id="{{ $id }}" type="text" {!! $readonly !!}
    {!! $nameAttr !!} {!! $valueAttr !!} {!! $placeholder !!} class="form-control form-control-sm float-right @if($errors->has($name)) is-invalid @endif">
    @if($errors->first($name)) <span class="error invalid-feedback">{{ $errors->first($name) }}</span> @endif
</div>
<script>
    Admin.boot(function () {
        $('#{{ $id }}').daterangepicker({
            locale: { //汉化
                format: "YYYY-MM-DD", //设置显示格式
                applyLabel: '确定', //确定按钮文本
                cancelLabel: '取消', //取消按钮文本
                daysOfWeek: ['日', '一', '二', '三', '四', '五', '六'],
                monthNames: ['一月', '二月', '三月', '四月', '五月', '六月',
                    '七月', '八月', '九月', '十月', '十一月', '十二月'],
                firstDay: 1
            }
        });
    });
</script>
