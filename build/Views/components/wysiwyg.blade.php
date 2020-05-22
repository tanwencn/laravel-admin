@php
    $id = 'wysiwyg-'.mt_rand(100, 999);
    $nameAttr = isset($name)?"name='{$name}'":"";
@endphp

<textarea id="{{ $id }}" {!! $nameAttr !!}>{{ $slot }}</textarea>

<script>
    Admin.boot(function () {
        $('#{{ $id }}').summernote();
    });
</script>
