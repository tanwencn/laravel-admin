window.Admin = function () {
    "use strict";

    return {
        is_one: true,
        is_pjax: true,
        language: {},
        data: {},
        boots: [],
        errors: [],
        boot: function boot(call) {
            this.boots.push(call);
        },
        onece: function onece() {
            toastr.options = {
                progressBar: true
            };
            NProgress.configure({
                parent: '#pjax-container'
                /*, showSpinner:false*/
            }).start();
            $(document).ready(function() {
                NProgress.done();
            });

            $(document).ajaxStart(function () {
                NProgress.start();
            }).ajaxStop(function () {
                NProgress.done();
            });
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': Admin.language.csrf_token
                }
            });

            $.extend($.fn.dataTable.defaults, {
                paging: false,
                ordering: false,
                searching: false,
                info: false,
                scrollX:true,
                scrollY: $(window).height() ? $(window).height() - 240 : 420,
                scrollCollapse: true,
                /*headerCallback: function headerCallback(thead, data, start, end, display) {
                    var th = $(thead).find('th').eq(0);
                    if ($(this).hasClass('table-select')) th.html('<input type="checkbox" class="grid-select-all checkbox-style" />');
                },
                rowCallback: function rowCallback(row, data, index) {
                    var td = $('td:eq(0)', row);
                    if($(this).hasClass('table-select')) td.html('<input type="checkbox" class="grid-row-checkbox checkbox-style" value="' + $.trim(data[0]) + '">');
                }*/
            });
            $('#pjax-container').on('change', '.grid-row-checkbox:not(.checkbox-style)', function () {
                if (this.checked) {
                    $(this).closest('tr').css('background-color', '#ffffd5');
                } else {
                    $(this).closest('tr').css('background-color', '');
                }
            });
            $('#pjax-container').on('change', '.grid-select-all:not(.checkbox-style)', function () {
                $('.grid-row-checkbox').prop('checked', this.checked).trigger('change');
            });
            if (this.is_pjax) this.pjax();
        },
        init: function init() {
            if (this.is_one) {
                this.onece();
                this.is_one = false;
            } // $('#pjax-container .box').boxWidget();

            this.activityMenu();
            $('#pjax-container .table').each(function(_, table){
                if($(table).hasClass('no-pitch')){
                    $(table).parent().addClass('table-responsive').addClass('p-0');
                }
                if($(table).hasClass('table-select')){
                    $(table).find('thead tr:first').prepend('<th><input type="checkbox" class="grid-select-all checkbox-style" /></th>')
                    $(table).find('tbody tr').each(function(_, tr){
                        var id = $.trim($(tr).data('id'));
                        if(id) $(tr).prepend('<td><input type="checkbox" class="grid-row-checkbox checkbox-style" value="' + id + '"></td>')
                        else $(tr).prepend('<td></td>');
                    });
                }

                if(!$(table).hasClass('no-data')) $(table).DataTable();
            });
            $('#pjax-container .select2').select2();
            toastr.clear();

            $.each(Admin.errors, function (i, e) {
                Admin.error(e);
            });
            Admin.errors = [];
            /** list **/

            $('#pjax-container input.checkbox-style').iCheck({
                checkboxClass: 'icheckbox_flat-blue',
                increaseArea: '10%' // optional

            });
            this.icheckEvent();
            /** list **/

            $.each(this.boots, function (i, call) {
                call();
            });
            this.boots = [];
        },
        pjax: function pjax() {
            $.pjax.defaults.timeout = 5000;
            $.pjax.defaults.maxCacheLength = 0;
            $(document).pjax('a[target!=_blank]', '#pjax-container');
            $(document).on('pjax:timeout pjax:error', function (event, xhr) {
                event.preventDefault();
                var serverUrl = xhr.getResponseHeader('X-PJAX-URL');
                window.history.replaceState(null, "", serverUrl);
                NProgress.done();
                if (event.handleObj.type == 'pjax:error')
                    toastr.error(xhr.statusText + " " + xhr.status);
                else
                    toastr.warning(xhr.statusText + " " + xhr.status);
            }).on('submit', 'form', function (event) {
                $.pjax.submit(event, '#pjax-container');
            }).on('pjax:send pjax:complete', function (event) {
                if (event.relatedTarget && event.relatedTarget.tagName && event.relatedTarget.tagName.toLowerCase() === 'form') {
                    var submit_btn = $('form [type="submit"]');

                    if (submit_btn) {
                        submit_btn.attr("disabled", event.handleObj.type == 'pjax:send');
                    }
                }
            });
        },
        icheckEvent: function icheckEvent() {
            $('#pjax-container .grid-row-checkbox.checkbox-style').on('ifChanged', function () {
                if (this.checked) {
                    $(this).closest('tr').css('background-color', '#ffffd5');
                } else {
                    $(this).closest('tr').css('background-color', '');
                }
            });
            $('#pjax-container .grid-select-all.checkbox-style').on('ifChanged', function () {
                if (this.checked) {
                    $('#pjax-container .grid-row-checkbox.checkbox-style').iCheck('check');
                } else {
                    $('#pjax-container .grid-row-checkbox.checkbox-style').iCheck('uncheck');
                }
            });
            $('#pjax-container .grid-row-checkbox.checkbox-style:checked').closest('tr').css('background-color', '#ffffd5');
        },
        query: function query(q) {
            var parms = [];
            $.each(q, function (name, val) {
                if (val instanceof Array) {
                    var n = {};
                    $.each(val, function (i, v) {
                        n[name + '[' + i + ']'] = v;
                    });
                    parms.push(Admin.query(n));
                } else if (val instanceof Object) {
                    var n = {};
                    $.each(val, function (i, v) {
                        n[name + '[' + i + ']'] = v;
                    });
                    parms.push(Admin.query(n));
                } else {
                    parms.push(name + '=' + val);
                }
            });
            return parms.join('&');
        },
        activityMenu: function activityMenu() {
            var url = window.location.href.split("?")[0];
            var current = $('aside .nav-sidebar li a[href="' + url + '"]');

            if (current.length > 0) {
                current.addClass('active');
                var parents_li = current.parents('li');
                parents_li.addClass('menu-open').children('a').addClass('active');
                $('aside .nav-sidebar li').not(parents_li).removeClass('menu-open').find('a').removeClass('active');
            }
        },
        alert: function alert(content) {
            $.alert({
                title: false,
                autoClose: 'yes|3000',
                content: content,
                buttons: {
                    yes: {
                        text: Admin.language.ok
                    }
                }
            });
        },
        confirm: function confirm(content, call) {
            $.confirm({
                title: Admin.language.confirmTitle,
                content: content,
                autoClose: 'cancel|6000',
                buttons: {
                    ok: {
                        text: Admin.language.confirm,
                        action: function action() {
                            call();
                        }
                    },
                    cancel: {
                        text: Admin.language.cancel
                    }
                }
            });
        },
        error: function error(content) {
            toastr.error(content);
        },
        success: function success(content) {
            toastr.success(content);
        }
    };
}();
