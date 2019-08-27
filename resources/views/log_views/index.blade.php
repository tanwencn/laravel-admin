@extends('admin::layouts.app')

@section('title', "Laravel Logs")

@section('content')
    <!-- begin row -->
    <div class="row">
        <!-- begin col-12 -->
        <div class="col-md-4">
            <div class="box box-default">
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
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover table-striped table-scroll" data-table-height="508">
                        <thead>
                        <th>Level</th>
                        <th>Context</th>
                        <th>Date</th>
                        <th>Content</th>
                        </thead>
                        <tbody>
                        @foreach($data as $key => $val)
                            <tr>
                                <td style="white-space: nowrap;">{{ $val[3] }}</td>
                                <td style="white-space: nowrap;">{{ $val[2] }}</td>
                                <td style="white-space: nowrap;">{{ $val[1] }}</td>
                                <td><a class="btn btn-xs btn-default view" data-target="#grid-collapse-{{ $key }}"> View
                                    </a>
                                </td>
                            </tr>
                            <template id="grid-collapse-{{ $key }}">
                                <div style="white-space: pre-wrap;background: #333;color: #fff; padding: 10px;">{{ $val[4].$val[5] }}</div>
                            </template>
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
            $('.view').click(function () {
                var target = $(this).data('target');
                $.dialog({
                    boxWidth: '80%',
                    useBootstrap: false,
                    title: 'View Info!',
                    content: $(target).html(),
                });
            });
        });
    </script>
@endsection