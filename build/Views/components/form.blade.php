@php
$action = !empty($model->id)?Admin::action('update', $model->id) : Admin::action('store');
@endphp

<form action="{{ $action }}" method="POST">
{{ csrf_field() }}
@if(isset($model->id))
    {{ method_field("PUT") }}
@endif
    {{ $slot }}
</form>
