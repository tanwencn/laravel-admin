@php
    $type = isset($type)?"type={$type}":"text";
    $nameAttr = isset($name)?"name={$name}":"";
    $valueAttr = isset($value)?"value={$value}":"";
    $id = "input-file-".mt_rand(100,999);
@endphp

<div class="input-group input-group-sm">
    <input id="{{ $id }}" readonly {!! $nameAttr !!} {!! $valueAttr!!} {!! $value !!} class="form-control form-control-sm @if($errors->has('metas.avatar')) is-invalid @endif">
    @if($errors->first($name)) <span class="error invalid-feedback">{{ $errors->first($name) }}</span> @endif
    <div class="input-group-append">
        <button id="{{ $id }}-button" type="button"
                class="btn btn-default btn-"><i
                class="glyphicon glyphicon-folder-open"></i> {{ trans('admin.select') }}
        </button>
    </div>
</div>
<script>
    Admin.boot(function () {
        Finder.disk().click('#{{ $id }}-button', '#{{ $id }}');
    });
</script>

