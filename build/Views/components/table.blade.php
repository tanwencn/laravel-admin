@php
$checkbox = isset($checkbox)?$checkbox:false;
$nowrap = isset($nowrap)?$nowrap:true;
$nodata = isset($nodata)?$nodata:false;
$id = 'table-'.mt_rand(1000, 9999);
@endphp

<table id="{{ $id }}" class="table table-borderless no-pitch @if($nodata) no-data @endif @if($nowrap) text-nowrap @endif @if($checkbox) table-select @endif">
    <thead class="thead-light">
    {{ $thead }}
    </thead>
    <tbody>
    {{ $tbody }}
    </tbody>
</table>
