@if(!request()->ajax())

    <!-- elfinder -->
   <link rel="stylesheet" href="{{ asset('vendor/laravel-admin/jquery-ui/themes/base/minified/jquery-ui.min.css') }}"/>
   {{--<link rel="stylesheet" type="text/css" href="{{ asset('vendor/laravel-admin/elfinder/css/elfinder.min.css') }}">--}}
   {{--<link rel="stylesheet" type="text/css" href="{{ asset('vendor/laravel-admin/elfinder/css/theme.css') }}">--}}
   <!-- elfinder -->
       <script src="{{ asset('/vendor/laravel-admin/jquery/jquery.min.js') }}"></script>
       <!-- elfinder -->
       <script src="{{ asset('vendor/laravel-admin/jquery-ui/ui/minified/jquery-ui.min.js') }}"></script>
       <script src="{{ asset('vendor/laravel-admin/elfinder/js/elfinder.min.js') }}"></script>
       <!-- elfinder -->
    @endif

<div id="elfinder"></div>
<script type="text/javascript" charset="utf-8">
    // Documentation for client options:
    // https://github.com/Studio-42/elFinder/wiki/Client-configuration-options
    $(function () {
        var opts = {
            //cssAutoLoad : ['/vendor/laravel-admin/jquery-ui/themes/base/minified/jquery-ui.min.css'],
            cssAutoLoad : true,
            commandsOptions: {
                getfile: {
                    multiple: !!parseInt("{{ $multiple }}"),
                    oncomplete: 'destroy'
                }
            },
            //height:'100%',
            //onlyMimes:false,
            ui: ['toolbar', 'path', 'stat'],
            uiOptions: {
                toolbarExtra: {
                    displayTextLabel: true
                },

                tree: {
                    // expand current root on init
                    openRootOnLoad: true,
                    // auto load current dir parents
                    syncTree: true
                },
                // toolbar configuration
                toolbar: [
                    ['back', 'forward', "up"],
                    ['reload'],
                    ['home', 'up'],
                    ['upload'],
                    ['getfile', 'open', 'download'],
                    ['rm'],
                    ['extract', 'archive']
                    // ,['search']
                ],
                // current working directory options
                cwd: {
                    // display parent directory in listing as ".."
                    oldSchool: false
                }
            },
            contextmenu: {
                // navbarfolder menu
                navbar: ['open', '|', 'copy', 'cut', 'paste', 'duplicate', '|', 'rm', '|'],

                // current directory menu
                cwd: ['reload', 'back', '|', 'upload', 'mkdir', 'paste'],

                // current directory file menu
                files: ['getfile', '|', 'open', 'quicklook', '|', '|', 'copy', 'cut', 'paste', 'duplicate', '|', 'rm', '|', 'edit', 'rename', 'resize', '|', 'archive', 'extract', '|']
            },
            handlers: {
                contextmenu: function (event, elfinderInstance) {
                    event.data.raw = [];
                }
            },
            // set your elFinder options here
            customData: {
                _token: '{{ csrf_token() }}'
            },
            url: '{{ route("elfinder.connector") }}',  // connector URL
            soundPath: '{{ asset('vendor/laravel-admin/elfinder/sounds') }}'
        };

       // opts.getFileCallback = function(){};
        if (typeof Finder !== 'undefined') {
            opts.getFileCallback = function (file, fm) {
                if(file instanceof Array){
                    $.each(file, function(i){
                        file[i].absoluteUrl = fm.convAbsUrl(file[i].url);
                    })
                }else {
                    file.absoluteUrl = fm.convAbsUrl(file.url);
                }

                Finder.instance("{{ request('j_instance_key') }}").close(file);
            };
        }
        @if($is_tree)
        opts.ui.push('tree');
        @endif
                @if($locale)
            opts.lang = '{{ $locale }}'; // locale
                @endif
        var elfinder = $('#elfinder').elfinder(opts).elfinder('instance');

        /*var index = parent.layer.getFrameIndex(window.name);
        parent.layer.iframeAuto(index);*/
    });

</script>

