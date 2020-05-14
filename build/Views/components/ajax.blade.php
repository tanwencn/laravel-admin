@php

    $class = isset($class)?"class='{$class}'":'';

    $id = isset($id)?$id:'ajax-'.mt_rand(1000,9999);

    $data = isset($data)?$data:[];
    $data = is_string($data)?json_decode($data):$data;

    $method = isset($method)?$method:'GET';
    if(!in_array(strtolower($method), ['get', 'post'])){
        $data['_method'] = $method;
        $method = 'POST';
    }

    $confirm = isset($confirm)?$confirm:false;

    $selected = isset($selected)?$selected:false;

    $selectedTarget = isset($selectedTarget)?$selectedTarget:'.grid-row-checkbox';
@endphp

<a id="{{ $id }}" href="javascript:void(0);" {!! $class !!} >{{ $text }}</a>

<script>
    Admin.boot(function () {
        $('#{{ $id }}').on('click', function (event) {
            event.preventDefault();
            var url = '{{ $url }}';
            var data = @json($data);

            @if($selected)
            var selected = [];
            $('#pjax-container {{ $selectedTarget }}:checked').each(function () {
                selected.push(this.value);
            });

            if (selected.length < 1) {
                Admin.alert(Admin.language.pleaseSelectData);
                return false;
            }
            data['{{ $selected }}'] = selected;
            @endif

            var request = function () {
                $.ajax({
                    method: '{{ $method }}',
                    url: url,
                    data: data,
                    dataType: 'JSON',
                    success: function (data) {
                        $.pjax.reload('#pjax-container');
                        if (data['message']) Admin.success(data['message']);
                    },
                    error: function (rs) {
                        if (rs['responseJSON'] && rs['responseJSON']['message']) {
                            Admin.error(rs['responseJSON']['message']);
                        }
                    }
                });
            }

            @if($confirm)
            Admin.confirm('{{ $confirm }}', request);
            @else
            request();
            @endif
        });
    });
</script>
