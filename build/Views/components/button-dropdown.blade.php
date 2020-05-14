@php
    $id = isset($id)?$id:'btn-drop-down-'.mt_rand(100, 999);
    $class = isset($class)?$class:'btn-secondary btn-sm';
@endphp
<div class="btn-group">
    <button id="{!! $id !!}" type="button" class="btn dropdown-toggle {!! $class !!}" data-toggle="dropdown">
        {{ $name }}
    </button>
    <div class="dropdown-menu" aria-labelledby="{{ $id }}">
        {{ $links }}
    </div>
</div>
