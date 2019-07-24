@extends('admin::options.base')

@section('form')
    <div class="form-group">
        <label for="web_name" class="col-sm-3 control-label">{{ trans('admin.web_name') }}</label>
        <div class="col-sm-7">
            <input name="options[web_name]" type="text" class="form-control" id="web_name"
                   value="{{ old('options.web_name', option('web_name')) }}">
        </div>
    </div>
    <div class="form-group">
        <label for="web_desc" class="col-sm-3 control-label">{{ trans('admin.web_desc') }}</label>
        <div class="col-sm-7">
            <input name="options[web_desc]" type="text" class="form-control" id="web_desc"
                   value="{{ old('options.web_desc', option('web_desc')) }}">
        </div>
    </div>
    <div class="form-group">
        <label for="web_url" class="col-sm-3 control-label">{{ trans('admin.web_logo') }}(Logo)</label>
        <div class="col-sm-9">
            <input readonly style="width:210px; float: left" name="options[web_logo]" type="text"
                   class="form-control" id="web_logo"
                   value="{{ old('options.web_logo', option('web_logo')) }}">
            <button type="button" style="width: auto; float: left;"
                    class="btn btn-default select-image"><i
                        class="glyphicon glyphicon-folder-open"></i> {{ trans('admin.select_image') }}
            </button>
        </div>
    </div>
    <script>

        function processSelectedFile(file, requestingField) {
            $('#web_logo').val(file.url);
        }

        $(function () {
            $('.select-image').click(function () {
                Admin.showImageSelector('logo?multiple=false');
            });

            $('.btn-save').click(function () {
                var form = $(this).parents('form');
                $.post(form.attr('action'), form.serialize(), function (data) {
                    if (data.status) {
                        toastr.success(data.message);
                    } else {
                        toastr.error(data.message);
                    }
                }, 'json');
            });
        });
    </script>
@endsection