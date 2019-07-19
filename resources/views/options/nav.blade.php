<li class="{{ url()->current() == Admin::action('general') ? "active" : "" }}">
    <a href="{{ Admin::action('general') }}">{{ trans('admin.general_settings') }} <span
                class="pull-right fa fa-angle-right"></span></a>
</li>
