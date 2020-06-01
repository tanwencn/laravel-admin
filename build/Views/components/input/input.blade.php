@php
    $type = isset($type)?"{$type}":"text";
    $id = isset($id)?$id:'input-'.mt_rand(100, 999);
    $nameAttr = isset($name)?"name='{$name}'":"";
    $valueAttr = isset($value)?"value='{$value}'":"";
    $readonly = isset($readonly)?"readonly='{$readonly}'":"";
    $placeholder = isset($placeholder)?"placeholder='{$placeholder}'":"";
    $class = isset($class)?$class:'';
    if($type == 'password') $class .= ' password';
@endphp

<input type="{{ $type }}" id="{!! $id !!}" {!! $nameAttr !!} {!! $valueAttr !!} {!! $placeholder !!} class="form-control form-control-sm {!! $class !!} @if($errors->has($name)) is-invalid @endif" />
@if($errors->first($name)) <span class="error invalid-feedback">{{ $errors->first($name) }}</span> @endif

<script>
    Admin.boot(function(){
        $('.input-group').on('click', '.fa-eye-slash', function(){
            $(this).removeClass('fa-eye-slash');
            $(this).addClass('fa-eye');
            $(this).parents('.input-group').find('.password').attr('type', 'text');
        });
        $('.input-group').on('click', '.fa-eye', function(){
            $(this).removeClass('fa-eye');
            $(this).addClass('fa-eye-slash');
            $(this).parents('.input-group').find('.password').attr('type', 'password');
        });
    });
</script>
