window.Admin = function () {
    "use strict";

    return {
        is_one: true,
        is_pjax: true,
        info: {},
        data: {},
        boots: [],
        errors: [],
        boot: function boot(call) {
            this.boots.push(call);
        },
        onece: function onece() {
            var self = this;
            toastr.options = {
                progressBar: true
            };
            NProgress.configure({
                parent: '#pjax-container'
                /*, showSpinner:false*/

            });
            NProgress.start();
            $(document).ajaxStart(function () {
                NProgress.start();
            });
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': Admin.info.csrf_token
                }
            });
            $(document).ajaxStop(function () {
                NProgress.done();
            });
            NProgress.done();

            $.extend($.fn.dataTable.defaults, {
                paging: false,
                ordering: false,
                searching: false,
                info: false,
                scrollY: $(window).height()?$(window).height()-240:420,
                scrollCollapse: true,
                headerCallback: function headerCallback(thead, data, start, end, display) {
                    var th = $(thead).find('th').eq(0);
                    if (th.hasClass('table-select')) th.html('<input type="checkbox" class="grid-select-all checkbox-style">');
                },
                rowCallback: function rowCallback(row, data, index) {
                    var td = $('td:eq(0)', row);
                    var id = $.trim(data[0]);
                    if (td.hasClass('table-select') && id) td.html('<input type="checkbox" class="grid-row-checkbox checkbox-style" value="' + $.trim(data[0]) + '">');
                    if (td.hasClass('table-index')) td.html(index + 1);
                }
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
            $('#pjax-container').on('click', '.grid-batch-delete,.grid-row-delete', function () {
                var selected = [];

                if ($(this).hasClass('grid-batch-delete')) {
                    selected = Admin.listSelectedRows();

                    if (!selected) {
                        return false;
                    }
                }

                self["delete"]($(this).data('url'), selected, $(this).data('type'));
            });
            if (this.is_pjax) this.pjax();
        },
        init: function init() {
            if (this.is_one) {
                this.onece();
                this.is_one = false;
            } // $('#pjax-container .box').boxWidget();


            $('#pjax-container .table:not(.no-data)').DataTable();
            $('#pjax-container .select2').select2();
            toastr.clear();

            if (this.info.toastr_success != '') {
                toastr.success(this.info.toastr_success);
            }

            $.each(Admin.errors, function (i, e) {
                toastr.error(e, Admin.info.failed);
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
            $(document).on('pjax:start', function () {
                NProgress.start();
            });
            $(document).on('pjax:end', function () {
                NProgress.done();
            });
            $(document).pjax('a[target!=_blank]', '#pjax-container');
            $(document).on('pjax:timeout', function (event) {
                event.preventDefault();
                toastr.warning(Admin.info.timeout_load);
                NProgress.done();
            });
            $(document).on('pjax:error', function (event, textStatus) {
                event.preventDefault();
                toastr.error(Admin.info.failed);
                NProgress.done();
            });
            $(document).on('submit', 'form', function (event) {
                $.pjax.submit(event, '#pjax-container');
            });
            $(document).on('pjax:send', function (xhr) {
                if (xhr.relatedTarget && xhr.relatedTarget.tagName && xhr.relatedTarget.tagName.toLowerCase() === 'form') {
                    var submit_btn = $('form [type="submit"]');

                    if (submit_btn) {
                        submit_btn.attr("disabled", true);
                    }
                }

                NProgress.start();
            });
            $(document).on('pjax:complete', function (xhr) {
                if (xhr.relatedTarget && xhr.relatedTarget.tagName && xhr.relatedTarget.tagName.toLowerCase() === 'form') {
                    var submit_btn = $('form [type="submit"]');

                    if (submit_btn) {
                        submit_btn.removeAttr("disabled");
                    }
                }

                NProgress.done();
            });
            NProgress.done();
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
        activityMenu: function activityMenu(url) {
            var current = $('aside .nav-sidebar li a[href="' + url + '"]');

            if (current.length > 0) {
                current.addClass('active');
                var parents_li = current.parents('li');
                parents_li.addClass('menu-open').children('a').addClass('active');
                $('aside .nav-sidebar li').not(parents_li).removeClass('menu-open').find('a').removeClass('active');
                /*var parents_treeview = current.parents('.treeview-menu');
                parents_treeview.slideDown("slow");
                $('aside .sidebar-menu .treeview-menu').not(parents_treeview).slideUp("slow");*/
            }
        },
        "delete": function _delete(url, ids, type) {
            var message = "";

            if (type == 'trash') {
                message = this.info.trashMessage;
            } else {
                message = this.info.deleteMessage;
            }

            $.confirm({
                title: this.info.deleteTitle,
                content: message,
                autoClose: 'cancelAction|6000',
                buttons: {
                    deleteAction: {
                        text: this.info.deleteText,
                        action: function action() {
                            $.ajax({
                                method: 'post',
                                url: url,
                                data: {
                                    _method: 'DELETE',
                                    _token: Admin.info.csrf_token,
                                    ids: ids
                                },
                                success: function success(data) {
                                    $.pjax.reload('#pjax-container');
                                    toastr.success(data.message);
                                }
                            });
                        }
                    },
                    cancelAction: {
                        text: this.info.cancelText
                    }
                }
            });
        },
        listSelectedRows: function listSelectedRows() {
            var selected = [];
            $('#pjax-container .grid-row-checkbox:checked').each(function () {
                selected.push(this.value);
            });

            if (selected.length < 1) {
                $.alert({
                    title: false,
                    type: 'red',
                    content: Admin.info.pleaseSelectData,
                    buttons: {
                        yes: {
                            text: Admin.info.ok
                        }
                    }
                });
                return false;
            }

            return selected;
        }
    };
}();
