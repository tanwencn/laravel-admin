<li class="nav-item {{ url()->current() == Admin::action('general') ? "menu-open" : "" }}">
    <a class="nav-link {{ url()->current() == Admin::action('general') ? "active" : "" }}" href="{{ Admin::action('general') }}"><p>{{ trans('admin.general_settings') }}</p></a>
</li>
