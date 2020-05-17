@php
    $id = isset($id)?"$id":'input-switch-'.mt_rand(100, 999);
    $name = isset($name)?"name='{$name}'":"";
    $checked = isset($checked)?$checked:false;
    $readonly = isset($readonly)?$readonly:false;
    $ajax = isset($ajax)?$ajax:false;
@endphp

<input type="checkbox" id="{!! $id !!}" {!! $name !!} @if($checked) checked
       @endif class="form-control form-control-sm"/>
<script type="text/javascript">
    Admin.boot(function () {
        $("#{{ $id }}").bootstrapSwitch({
            'state': $(this).prop('checked'),
            size: "small",
            onSwitchChange: function (event, state) {
                //监听switch change事件，可以根据状态把相应的业务逻辑代码写在这里
                @if($ajax)
                $.ajax({
                    method: 'GET',
                    url: '{{ $ajax }}',
                    //data: data,
                    dataType: 'JSON',
                    success: function (data) {
                        //$.pjax.reload('#pjax-container');
                        if (data['message']) Admin.success(data['message']);
                    },
                    error: function (rs) {
                        if (rs['responseJSON'] && rs['responseJSON']['message']) {
                            Admin.error(rs['responseJSON']['message']);
                        }
                    }
                });
                @endif
            }
        });
        @if($readonly)
        $("#{{ $id }}").bootstrapSwitch('readonly', true);
        @endif
    });
</script>
