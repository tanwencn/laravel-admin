@php
$checkbox = isset($checkbox)?"table-select":"";
$nowrap = isset($nowrap)?"":"text-nowrap";
$nodata = isset($nodata)?"no-data":"";
$id = 'table-'.mt_rand(1000, 9999);
@endphp

<table id="{{ $id }}" style="width: 100%" class="table table-hover table-borderless no-pitch {{ $nodata }} {{ $nowrap }} {{ $checkbox }}">
    <thead class="thead-light">
    {{ $thead }}
    </thead>
    <tbody>
    {{ $tbody }}
    </tbody>
</table>
