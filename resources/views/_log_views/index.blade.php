@extends('admin::_layouts.app')

@section('title', trans("admin.laravel_logs"))

@section('content')
    <style>
        .modal-dialog{
            width: 860px;
        }
        .modal-content {
            position: relative;
            background-color: transparent;
             background-clip: padding-box;
             border: 0;
             border-radius: 6px;
            outline: 0;
        }
        .modal-body{
            padding: 0;
        }
        pre {
            position: relative;
            background: #21252b;
            border-radius: 5px;
            #font: 15px/22px "Microsoft YaHei", Arial, Sans-Serif;
            #line-height: 1.6;
            #margin-bottom: 24px;
            max-width: 100%;
            overflow: auto;
            text-shadow: none;
            color: #000;
            #padding-top: 30px;
            card-bodyshadow: 0 10px 30px 0 rgba(0, 0, 0, .4);
        }
        pre code {
            background: #1d1f21;
            color: #fff;
            word-break: break-word;
            font-family: 'Source Code Pro', monospace, Helvetica, Tahoma, Arial, STXihei, "STHeiti Light", "Microsoft YaHei", sans-serif;
            padding: 2px;
            text-shadow: none;
            border-radius: 0 0 5px 5px;
        }

    </style>
    <!-- begin row -->
    <div class="row ">
        <!-- begin col-12 -->
        <div class="col-md-3">
            <div class="card sidebar">
                <div class="card-header">
                    <h3 class="card-title">Files</h3>
                </div>
                <div class="card-body p-0 sidebar-light-primary">
                    @php
                        $build_tag = function($results)use(&$build_tag, $current){
                            $str = '';
                            foreach ($results as $key => $value){
                                $is_folder = is_array($value);
                                $icon = $is_folder?"folder":"file";
                                $attr = 'class="nav-link '.($current==$value?"active":"").'" href="'.($is_folder?'#':route('admin.logs', ['f' => encrypt($value, false)])).'"';

                                $str .= '<li class="nav-item '. ($current==$value?"menu-open":"").(!$is_folder?"":"has-treeview") .'"><a '. $attr .'><i class="nav-icon fas fa-'. $icon .'"></i><p>'.$key.($is_folder?'<i class="right fas fa-angle-left"></i>':'').'</p></a>';
                                if(is_array($value)){
                                    $str .= '<ul class="nav nav-treeview">'.$build_tag($value).'</ul>';
                                }
                            }
                            return $str.'</li>';
                        }
                    @endphp
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview"
                        data-accordion="true" id="tree_list">
                        {!! $build_tag($tree) !!}
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">

                    <h3 class="card-title">Logs</h3>
                    <div class="card-tools">

                        <div class="input-group input-group-sm" style="width: 200px;">
                            @if($eof)
                                <div class="input-group-btn">
                                    <a class="btn btn-sm btn-default"
                                       href="{{ Admin::action('index', array_merge(request()->query(), ['page' => $page+1, 'timestrap' => time()])) }}">@lang('pagination.next')</a>
                                </div>
                            @endif
                            <input type="search" name="search" class="form-control float-right" value=""
                                   placeholder="{{ trans('admin.search') }}...">

                            {{--<div class="input-group-btn">
                                <button type="button" class="btn btn-default btn-search"><i class="fa fa-search"></i>
                                </button>
                            </div>--}}
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <admin::table nowrap="false" nodata="true">
                    <slot name="thead">
                        <tr>
                            <th>Level</th>
                            <th>Context</th>
                            <th>Date</th>
                            <th>Content</th>
                            <th>More</th>
                        </tr>
                    </slot>
                    <slot name="tbody">
                        @foreach($data as $key => $val)
                            <tr>
                                <td style="white-space: nowrap;">{{ $val[3] }}</td>
                                <td style="white-space: nowrap;">{{ $val[2] }}</td>
                                <td style="white-space: nowrap;">{{ $val[1] }}</td>
                                <td>
                                    <code style="word-break:break-word">
                                        {{ trim($val[4]) }}
                                    </code>
                                </td>
                                <td>
                                    @if(isset($val[5]) && !empty(trim($val[5])))
                                        <button type="button" class="btn btn-xs btn-primary" data-toggle="modal"
                                                data-target="#modal-default{{ $key }}"> View
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            <div class="modal fade" id="modal-default{{ $key }}">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <pre><code>{{ $val[4].(isset($val[5])?$val[5]:"") }}</code></pre>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                        @endforeach
                    </slot>
                    </admin::table>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
        <!-- end panel -->
    </div>
    <script>
        Admin.boot(function () {

            var table = $('.table').DataTable({
                searching: true,
                dom: 'Brtip',
                buttons: [
                    'print'
                ]
            });

            $('[name="search"]').keyup(function () {
                if ($(this).prop('comStart')) return;    // 中文输入过程中不截断
                NProgress.start();
                table.search($('input[name="search"]').val()).draw();
                NProgress.done();
            }).on('compositionstart', function () {
                $(this).prop('comStart', true);
            }).on('compositionend', function () {
                $(this).prop('comStart', false);
            });

            $('#tree_list a.active').parents('li').addClass('menu-open').children('a').addClass('active');
        });
    </script>
@endsection
