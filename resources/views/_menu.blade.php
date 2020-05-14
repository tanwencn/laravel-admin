@foreach($items as $val)
    @if(is_string($val)) <li class="nav-header">{{ $val }}</li> @continue @endif
    <li class="nav-item {{ $val->children ? "has-treeview" : "" }}">
        <a target="{{ $val->target?:'_self' }}" href="{{ $val->url }}" class="nav-link">
            <i class="nav-icon {{ $val->icon }}"></i>
            <p>
                {!! $val->title !!}
                @if($val->children)
                    <i class="right fas fa-angle-left"></i>
                @endif
            </p>
        </a>
        @if ($val->children)
            <ul class="nav nav-treeview">
                {!! view('admin::_menu', ['items' => $val->children]) !!}
            </ul>
        @endif
    </li>
@endforeach