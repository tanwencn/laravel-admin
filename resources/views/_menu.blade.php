@foreach($items as $val)
    <li class="{{ $val->children ? "treeview" : "" }} {{ url()->current() == $val->url ? "active" : "" }}">
        <a target="{{ $val->target?:'_self' }}" href="{{ $val->url }}">
            {!! $val->title !!}
            @if($val->children)
                <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                </span>
            @endif
        </a>
        @if ($val->children)
            <ul class="treeview-menu">
                {!! view('admin::_menu', [
            'items' => $val->children
        ]) !!}
            </ul>
        @endif
    </li>
@endforeach