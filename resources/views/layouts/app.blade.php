@if(request()->pjax())
    @include('admin::layouts.pjax')
@else
    @include('admin::layouts.base')
@endif