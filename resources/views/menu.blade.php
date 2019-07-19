@foreach($items as $val)
    <li class="{{ $val->children ? "treeview" : "" }} {{ url()->current() == $val->url ? "active" : "" }}">
        <a href="{{ $val->url }}">
            {!! $val->title !!}
            @if($val->children)
                <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                </span>
            @endif
        </a>
        @if ($val->children)
            <ul class="treeview-menu">
                {!! view('admin::menu', [
            'items' => $val->children
        ]) !!}
            </ul>
        @endif
    </li>
@endforeach