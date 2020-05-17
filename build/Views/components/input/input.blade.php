@php
    $type = isset($type)?"{$type}":"text";
    $id = isset($id)?"id='{$id}'":"";
    $nameAttr = isset($name)?"name='{$name}'":"";
    $valueAttr = isset($value)?"value='{$value}'":"";
    $readonly = isset($readonly)?"readonly='{$readonly}'":"";
    $placeholder = isset($placeholder)?"placeholder='{$placeholder}'":"";
@endphp

<input type="{{ $type }}" {!! $id !!} {!! $nameAttr !!} {!! $valueAttr !!} {!! $placeholder !!} class="form-control form-control-sm @if($errors->has($name)) is-invalid @endif" />
@if($errors->first($name)) <span class="error invalid-feedback">{{ $errors->first($name) }}</span> @endif
