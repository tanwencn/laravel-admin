@php
        $id = isset($id)?"id={$id}":"";
        $nameAttr = isset($name)?"name={$name}":"";
        $selected = isset($selected)?$selected:"";
        $multiple = isset($multiple)?'multiple="multiple"':false;
        $search = isset($search) && $search=='false'?'data-minimum-results-for-search="Infinity"':"";
        $slot = (string)$slot;
        $results = isset($results)?$results:[];
        if(isset($toName) && $toName && !empty($results)) $results = array_combine($results, $results);

@endphp

<select style = "width : 100%"
        {!! $id !!} {!! $nameAttr !!} {!! $search !!} {!! $multiple !!} class="select2 form-control form-control-sm @if($errors->has($name)) is-invalid @endif"
        data-placeholder="{{ trans('admin.please_select') }}">
    <option></option>
    @if(!empty($slot))
        {!! $slot !!}
    @else
        @foreach($results as $name => $val)
            <option {{ $selected==$val?'selected':'' }} value="{{ $val }}">{{ $name }}</option>
        @endforeach
    @endif
</select>
@if($errors->first($name)) <span class="error invalid-feedback">{{$errors->first($name)}}</span> @endif
