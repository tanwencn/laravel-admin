@extends('admin::layouts.app')

@section('title', trans_choice('admin.operationlog', 1))

@section('content')
    <!-- begin row -->
    <div class="row">
        <!-- begin col-12 -->
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover table-striped table-scroll" data-table-height="490">
                        <thead>
                        <tr class="nowrap">
                            <th>{{ trans('admin.user') }}</th>
                            <th>{{ trans('admin.method') }}</th>
                            <th>{{ trans('admin.uri') }}</th>
                            <th>{{ trans('admin.content') }}</th>
                            <th>{{ trans('admin.format') }}</th>
                            <th>{{ trans('admin.created_at') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($results as $log)
                            <tr>
                                <td>{{ $log->user->name }}</td>
                                <td><span class="label bg-green">{{ $log->method }}</span></td>
                                <td><code>{{ $log->uri }}</code></td>
                                <td><pre>{{ $log->body }}</pre></td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-xs model-collapse" data-target="#collapse{{ $log->id }}">
                                        {{ trans('admin.click_view') }}
                                    </button>
                                </td>
                                <td style="white-space:nowrap">{{ $log->created_at }}</td>
                            </tr>
                            <tr class="collapse" id="collapse{{$log->id}}">
                                <td colspan="6">
                                    <pre>{{ $log->body }}</pre>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer clearfix">
                    <div class="pull-left">
                        {{ trans('admin.pagination.range', [
                        'first' => $results->firstItem(),
                        'last' => $results->lastItem(),
                        'total' => $results->total(),
                        ]) }}
                    </div>

                    <div class="pull-right">
                        {{ $results->appends(request()->query())->links() }}
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
        <!-- end panel -->
    </div>
    <script>
        Admin.boot(function(){
            $('.collapse pre').each(function($key, $val){
                $(this).html(JSON.stringify(JSON.parse($($val).html()), null, 2));
            });
            $('.model-collapse').click(function () {
                var target = $(this).data('target');
                $(target).toggle();
            });
        });
    </script>
@endsection