@php
    $id = isset($id)?"id='{$id}'":"";
$class = "col-control-label col-control-label-sm text-right {$class} ";
if(isset($required) && true == $required) $class .='asterisk';

$for = isset($for)?"for='{for}'":"";
@endphp

<label class="{{ $class }}" {!! $for !!}>{{ $text }}：</label>
