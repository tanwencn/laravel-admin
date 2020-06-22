@extends('admin::_layouts.app')

@section('title', trans_choice('admin.user', 1))

@section('content')
    <!-- begin row -->
    <div class="row">
        <!-- begin col-12 -->
        <div class="col-md-12">
            <div class="card">
                <!-- /.card-header -->
                <div class="card-header">
                    <admin::button-dropdown :name="trans('admin.batch')">
                        <slot name="links">
                            @can('delete_role')
                                <admin::ajax class="dropdown-item" :url="route('admin.users.destroy', 0)"
                                             method="delete" :confirm="trans('admin.delete_message')" selected="ids"
                                             :text="trans('admin.delete')"/>
                            @endcan
                        </slot>
                    </admin::button-dropdown>

                    @can('add_user')
                        <div class="btn-group">
                            <a class="btn btn-sm btn-success" href="{{ Admin::action('create') }}"><i
                                        class="fa fa-plus f-s-12"></i> {{ trans('admin.add_user') }}</a>
                        </div>
                    @endcan

                    <div class="card-tools">
                        <form id="search" action="{{ Admin::action('index') }}">

                            <div class="input-group input-group-sm" style="width: 150px;">
                                <admin::input name="search" :value="request('search')"
                                              :placeholder="trans('admin.search_name')"/>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <admin::table checkbox="true">
                        <slot name="thead">
                            <tr>
                                @foreach($user_name_fileds as $filed)
                                    <th>{{ trans('admin.'.$filed) }}</th>
                                @endforeach
                                <th>{{ trans_choice('admin.role', 0) }}</th>
                                <th>{{ trans('admin.created_at') }}</th>
                                <th>{{ trans('admin.updated_at') }}</th>
                                <th>{{ trans('admin.last_login_time') }}</th>
                                <th>{{ trans('admin.operating') }}</th>
                            </tr>
                        </slot>
                        <slot name="tbody">
                            @foreach($results as $user)
                                <tr @if($user->id > 1) data-id="{{ $user->id }}" @endif>
                                    @foreach($user_name_fileds as $filed)
                                        <td>{{ $user->$filed }}</td>
                                    @endforeach
                                    <td>
                                        @foreach ($user->roles->pluck('name') as $role)
                                            <span class="badge bg-primary">{{ $role }}</span>
                                        @endforeach
                                    </td>
                                    <td>{{ $user->created_at }}</td>
                                    <td>{{ $user->updated_at }}</td>
                                    <td>{{ $user->last_login_time }}</td>
                                    <td>
                                        @can('edit_user')
                                            <a href="{{ Admin::action('edit', $user) }}">{{ trans('admin.edit') }}</a>
                                            &nbsp;
                                        @endcan
                                        @can('delete_user')
                                            @if($user->id > 1 && Auth::user()->can('delete_user'))
                                                <admin::ajax :url="route('admin.users.destroy', $user->id)"
                                                             method="delete" :confirm="trans('admin.delete_message')"
                                                             :text="trans('admin.delete')"/>
                                            @endif
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </slot>
                    </admin::table>
                </div>
                <div class="card-footer clearfix">
                    <admin::page :results="$results"/>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
        <!-- end panel -->
    </div>
@endsection