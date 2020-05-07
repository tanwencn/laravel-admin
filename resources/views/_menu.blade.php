@foreach($items as $val)
    <li class="nav-item {{ $val->children ? "has-treeview" : "" }}">
        <a target="{{ $val->target?:'_self' }}" href="{{ $val->url }}" class="nav-link">
            <i class="nav-icon fas fa-{{ $val->icon }}"></i>
            <p>
                {!! $val->title !!}
                @if($val->children)
                    <i class="right fas fa-angle-left"></i>
                @endif
            </p>
        </a>
        @if ($val->children)
            <ul class="nav nav-treeview">
                {!! view('admin::_menu', [
            'items' => $val->children
        ]) !!}
            </ul>
        @endif
    </li>
@endforeach