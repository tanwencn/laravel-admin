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
             -webkit-box-shadow: 0 3px 9px rgba(0,0,0,.5);
            box-shadow: 0 3px 9px rgba(0,0,0,.5);
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
            box-shadow: 0 10px 30px 0 rgba(0, 0, 0, .4);
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
    <div class="row">
        <!-- begin col-12 -->
        <div class="col-md-3">
            <div class="box box-default">
                <div class="box-header">
                    <h3 class="box-title">Files</h3>
                </div>
                <div class="box box-solid" style="height: 532px; overflow: scroll">
                    <div class="box-body no-padding">
                        @php
                            $build_tag = function($results, $id='', $dep=0)use(&$build_tag, $current){
                                $str = '<ul class="nav nav-pills nav-stacked '.($id?"collapse":"").' " '. ($id?"id={$id}":"") .'>';
                                foreach ($results as $key => $value){
                                    $icon = is_array($value)?"folder":"file";
                                    $attr = is_array($value)?'data-toggle="collapse" href="#'.$key.'"':'href="'. route('admin.logs', ['f' => encrypt($value, false)]) .'"';

                                    $str .= '<li '. (!is_array($value)?($current==$value?"class=\"active\"":""):"") .'><a '. $attr .'><span style="padding: '. ($dep*8) .'px"></span><i class="fa fa-'. $icon .'"></i>'.$key.'</a></li>';
                                    if(is_array($value)){
                                        $str .= $build_tag($value, $key, $dep+1);
                                    }
                                }
                                return $str.'</ul>';
                            }
                        @endphp
                        {!! $build_tag($tree) !!}
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="box box-default">
                <div class="box-header">

                    <h3 class="box-title">Logs</h3>
                    <div class="box-tools">

                        <div class="input-group input-group-sm" style="width: 200px;">
                            @if($eof)
                                <div class="input-group-btn">
                                    <a class="btn btn-sm btn-default"
                                       href="{{ Admin::action('index', array_merge(request()->query(), ['page' => $page+1, 'timestrap' => time()])) }}">@lang('pagination.next')</a>
                                </div>
                            @endif
                            <input type="search" name="search" class="form-control pull-right" value=""
                                   placeholder="{{ trans('admin.search') }}...">

                            {{--<div class="input-group-btn">
                                <button type="button" class="btn btn-default btn-search"><i class="fa fa-search"></i>
                                </button>
                            </div>--}}
                        </div>
                    </div>
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover table-striped no-data" data-scroll-y="490">
                        <thead>
                        <tr>
                            <th>Level</th>
                            <th>Context</th>
                            <th>Date</th>
                            <th>Content</th>
                            <th>More</th>
                        </tr>
                        </thead>
                        <tbody>
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
                                    @if(!empty(trim($val[5])))
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
                                            <pre><code>{{ $val[4].$val[5] }}</code></pre>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
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

            $('.nav li.active').parents('ul.collapse').addClass('in');
            $('.nav li.active').parents('ul.collapse').addClass('in');
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
        });
    </script>
@endsection