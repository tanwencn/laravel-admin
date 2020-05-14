@php
    $middles = isset($middles)?$middles:[];

    if(!empty($middle)) $middles = array_merge($middles, [$middle]);

@endphp

@section('breadcrumbs')
    @foreach($middles as $val)
        <li class="breadcrumb-item">
            <a href="{{ $val['url'] }}"> {{ $val['name'] }}</a>
        </li>
    @endforeach
@endsection
