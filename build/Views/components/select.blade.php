@php
    $id = isset($id)?"id={$id}":"";
    $name = isset($name)?"name={$name}":"";
    $selected = isset($selected)?$selected:"";
    $multiple = isset($multiple)?'multiple="multiple"':false;
    $search = isset($search) && $search=='false'?'data-minimum-results-for-search="Infinity"':"";
    $slot = isset($slot)?$slot:"";
    $results = isset($results)?$results:[];
    if(isset($toName) && $toName) $results = array_combine($results, $results);

@endphp

<select id="select2"
        {!! $id !!} {!! $name !!} {!! $search !!} {!! $multiple !!} class="select2 form-control form-control-sm @if($errors->has($name)) is-invalid @endif"
        data-placeholder="{{ trans('admin.please_select') }}">
    <option></option>
    @if(!empty($slot))
        {{ $slot }}
    @else
        @foreach($results as $name => $val)
            <option value="{{ $val }}">{{ $name }}</option>
        @endforeach
    @endif
</select>
@if($errors->first($name)) <span class="error invalid-feedback">{{$errors->first($name)}}</span> @endif
