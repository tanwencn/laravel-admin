@if(request()->pjax())
    @include('admin::_layouts.pjax')
@else
    @include('admin::_layouts.base')
@endif