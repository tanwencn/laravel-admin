@extends('admin::layouts.app')

@section('title', trans_choice('admin.user', 1))

@section('content')
    <!-- begin row -->
    <div class="row">
        <!-- begin col-12 -->
        <div class="col-md-12">
            <div class="box box-default">
                <!-- /.box-header -->
                <div class="box-header">
                    <div class="btn-group">
                        <button type="button" class="btn btn-default btn-sm">{{ trans('admin.batch') }}</button>
                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            @can('delete_user')
                                <li>
                                    <a href="javascript:void(0)" class="grid-batch-delete"
                                       data-url="{{ route('admin.users.destroy', 0) }}">{{ trans('admin.delete') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </div>

                    @can('add_user')
                        <div class="btn-group">
                            <a class="btn btn-sm btn-success" href="{{ Admin::action('create') }}"><i
                                        class="fa fa-plus f-s-12"></i> {{ trans('admin.add_user') }}</a>
                        </div>
                    @endcan

                    <div class="box-tools">
                        <form id="search" action="{{ Admin::action('index') }}">
                            <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" name="search" class="form-control pull-right"
                                       value="{{ request('search') }}"
                                       placeholder="{{ trans('admin.search_name') }}...">

                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-hover table-striped table-scroll">
                        <thead>
                        <tr>
                            <th><input type="checkbox" class="grid-select-all checkbox-style"></th>
                            @foreach($user_name_fileds as $filed)
                                <th>{{ trans('admin.'.$filed) }}</th>
                            @endforeach
                            <th>{{ trans_choice('admin.role', 0) }}</th>
                            <th>{{ trans('admin.created_at') }}</th>
                            <th>{{ trans('admin.updated_at') }}</th>
                            <th>{{ trans('admin.operating') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($results as $user)
                            <tr>
                                <td>
                                    @if($user->id > 1)
                                        <input type="checkbox" class="grid-row-checkbox checkbox-style"
                                               value="{{ $user->id }}">
                                    @endif
                                </td>
                                @foreach($user_name_fileds as $filed)
                                    <td>{{ $user->$filed }}</td>
                                @endforeach
                                <td>
                                    @foreach ($user->roles->pluck('name') as $role)
                                        <span class="label label-success label-many">{{ $role }}</span>
                                    @endforeach
                                </td>
                                <td>{{ $user->created_at }}</td>
                                <td>{{ $user->updated_at }}</td>
                                <td>
                                    @can('edit_user')
                                        <a href="{{ Admin::action('edit', $user) }}">{{ trans('admin.edit') }}</a>
                                        &nbsp;
                                    @endcan
                                    @can('delete_user')
                                        @if($user->id > 1 && Auth::user()->can('delete_user'))
                                            <a href="javascript:void(0);"
                                               data-url="{{ route('admin.users.destroy', $user->id) }}"
                                               class="grid-row-delete">{{ trans('admin.delete') }}</a>
                                        @endif
                                    @endcan
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
@endsection