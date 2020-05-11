@extends('admin::_layouts.app')

@section('title', trans_choice('admin.role', 1))

@section('content')
    <!-- begin row -->
    <div class="row">
        <!-- begin col-12 -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    @admin_buttons_dropdown(['name' => trans('admin.batch')])
                    @slot('links')
                        @can('delete_role')
                            <a href="javascript:void(0);" class="dropdown-item"
                               ajax-post="{{ route('admin.roles.destroy', 0) }}" data-method="delete"
                               data-confirm="{{ trans('admin.delete_message') }}"
                               data-selected-list="ids">{{ trans('admin.delete') }}</a>
                        @endcan
                    @endslot
                    @endadmin_buttons_dropdown

                    @can('add_role')
                        <div class="btn-group">
                            <a class="btn btn-sm btn-success" href="{{ Admin::action('create') }}"><i
                                        class="fa fa-plus f-s-12"></i> {{ trans('admin.add_role') }}</a>
                        </div>
                    @endcan

                    <div class="card-tools">
                        <form id="search" action="{{ Admin::action('index') }}">
                            <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" name="search" class="form-control float-right"
                                       value="{{ request('search') }}"
                                       placeholder="{{ trans('admin.search_name') }}...">

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    @admin_table(['checkbox' => true])
                    @slot('thead')
                        <tr>
                            <th>{{ trans('admin.name') }}</th>
                            <th>{{ trans('admin.guard') }}</th>
                            <th>{{ trans('admin.created_at') }}</th>
                            <th>{{ trans('admin.updated_at') }}</th>
                            <th>{{ trans('admin.operating') }}</th>
                        </tr>
                    @endslot
                    @slot('tbody')
                        @foreach($results as $role)
                            <tr @if($role->name != 'superadmin') data-id="{{ $role->id }}" @endif>
                                <td>{{ $role->name }}</td>
                                <td>{{ $role->guard_name }}</td>
                                <td>{{ $role->created_at }}</td>
                                <td>{{ $role->updated_at }}</td>
                                <td>
                                    @can('edit_role')
                                        <a href="{{ Admin::action('edit', $role->id) }}">{{ trans('admin.edit') }}</a>
                                        &nbsp;
                                    @endcan
                                    @can('delete_role')
                                        @if($role->name !='superadmin')
                                            <a href="javascript:void(0);"
                                               ajax-post="{{ route('admin.roles.destroy', $role->id) }}"
                                               data-method="delete"
                                               data-confirm="{{ trans('admin.delete_message') }}">{{ trans('admin.delete') }}</a>
                                        @endif
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    @endslot
                    @endadmin_table
                </div>
                <div class="card-footer clearfix">
                    @admin_page(['results' => $results]) @endadmin_page
                </div>
            </div>
        </div>
        <!-- end panel -->
    </div>
@endsection