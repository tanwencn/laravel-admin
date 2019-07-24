<div id="elfinder"></div>
<script type="text/javascript" charset="utf-8">
    // Documentation for client options:
    // https://github.com/Studio-42/elFinder/wiki/Client-configuration-options
    $(function() {
        var elfinder = $('#elfinder').elfinder({
            commandsOptions: {
                getfile: {
                    @if(request('multiple'))
                    multiple:{{ request('multiple') }},
                    @else
                    multiple:true,
                    @endif
                    oncomplete: 'destroy'
                }
            },
            getFileCallback: function (file) {
                processSelectedFile(file, '{{ $input_id  }}');
                Admin.hideImageSelector();
            },
            //height:'100%',
            onlyMimes:JSON.parse('{!! json_encode($onlyMimes)  !!} '),
            ui:['toolbar', 'path', 'stat'],
            uiOptions : {
                toolbarExtra: {
                    displayTextLabel: true
                },
                // toolbar configuration
                toolbar : [
                    ['back', 'forward', "up"],
                    // ['reload'],
                    // ['home', 'up'],
                    ['upload'],
                    ['getfile'],
                    //['rm'],
                    ['extract', 'archive']/*,
                        ['search']*/
                ],
                // current working directory options
                cwd : {
                    // display parent directory in listing as ".."
                    oldSchool : false
                }
            },
            contextmenu : {
                // navbarfolder menu
                navbar : ['open', '|', 'copy', 'cut', 'paste', 'duplicate', '|', 'rm', '|'],

                // current directory menu
                cwd    : ['reload', 'back', '|', 'upload', 'mkdir', 'paste'],

                // current directory file menu
                files  : ['getfile', '|','open', 'quicklook', '|', '|', 'copy', 'cut', 'paste', 'duplicate', '|', 'rm', '|', 'edit', 'rename', 'resize', '|', 'archive', 'extract', '|']
            },
            handlers : {
                contextmenu : function(event, elfinderInstance) {
                    event.data.raw = [];
                }
            },
            baseUrl:"{{ asset('/vendor/laravel-admin/elfinder') }}/",
            // set your elFinder options here
            @if($locale)
            lang: '{{ $locale }}', // locale
            @endif
            customData: {
                _token: '{{ csrf_token() }}'
            },
            url : '{{ route("elfinder.connector") }}',  // connector URL
            soundPath: '{{ asset('vendor/laravel-blog/elfinder/sounds') }}'
        });
        /*var index = parent.layer.getFrameIndex(window.name);
        parent.layer.iframeAuto(index);*/
    });

</script>

