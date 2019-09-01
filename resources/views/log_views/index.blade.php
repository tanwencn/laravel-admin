@extends('admin::layouts.app')

@section('title', "Laravel Logs")

@section('content')
    <!-- begin row -->
    <div class="row">
        <!-- begin col-12 -->
        <div class="col-md-4">
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
        <div class="col-md-8">
            <div class="box box-default">
                <div class="box-header">

                    <h3 class="box-title">Logs</h3>

                    <div class="box-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="search" class="form-control pull-right" value=""
                                   placeholder="Search...">

                            <div class="input-group-btn">
                                <button type="button" class="btn btn-default btn-search"><i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover table-striped table-scroll" data-table-height="508">
                        <thead>
                        <th>Level</th>
                        <th>Context</th>
                        <th>Date</th>
                        <th>Content</th>
                        <th>More</th>
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
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">Content</h4>
                                        </div>
                                        <div class="modal-body">
                                            <pre>{{ $val[4].$val[5] }}</pre>
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

            $('.nav li.active').parents('ul.collapse').addClass('in');
            $('.btn-search').click(function () {
                NProgress.start();
                //var key = $('input[name="search"]').val();
                // 声明变量
                var filter, tr;
                filter = $('input[name="search"]').val().toUpperCase();
                tr = $('.table').find('tr');

                // 循环表格每一行，查找匹配项
                tr.each(function (i, tr) {
                    tr = $(tr);
                    tr.show();
                    var inner = tr.text();

                    if (inner.toUpperCase().indexOf(filter) < 0) {
                        tr.hide();
                    }
                });
                NProgress.done();
            });
        });
    </script>
@endsection