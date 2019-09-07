@extends('admin::layouts.app')

@section('title', trans_choice('admin.operationlog', 1))

@section('content')
    <style>
        .table.dataTable {
            margin-top: 0 !important;
        }
    </style>
    <!-- begin row -->
    <div class="row">
        <!-- begin col-12 -->
        <div class="col-md-12">
            <div class="box box-widget">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover table-striped no-data" data-scroll-y="480">
                        <thead>
                        <tr>
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
                                <td>
                                    <pre>{{ $log->body }}</pre>
                                </td>
                                <td class="details-control">
                                    <button type="button" class="btn btn-primary btn-xs model-collapse"
                                            data-target="#collapse{{ $log->id }}">
                                        {{ trans('admin.click_view') }}
                                    </button>
                                </td>
                                <td style="white-space:nowrap">{{ $log->created_at }}</td>
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
        Admin.boot(function () {
            var table = $('.table').DataTable();

            $('table tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = table.row(tr);

                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('shown');
                }
                else {
                    var text = JSON.stringify(JSON.parse($(row.data()[3]).text()), null, 2);
                    row.child('<pre>' + text + '</pre>').show();
                    tr.addClass('shown');
                }
            });
        });
    </script>
@endsection