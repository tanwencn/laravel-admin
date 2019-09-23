var AdminOnece = true;
var Admin = function () {
    "use strict";
    return {
        info: {},
        data: {},
        boots: [],
        errors: [],
        boot: function (call) {
            this.boots.push(call);
        },
        onece: function () {
            var self = this;
            toastr.options = {
                progressBar: true
            };

            NProgress.configure({parent: '#pjax-container'/*, showSpinner:false*/});
            NProgress.start();

            $(document).ajaxStart(function () {
                NProgress.start();
            });
            $(document).ajaxStop(function () {
                NProgress.done();
            });

            NProgress.done();

            $.extend( $.fn.dataTable.defaults, {
                paging: false,
                ordering: false,
                searching: false,
                info: false,
                scrollY: 400,
                scrollCollapse:true,
                headerCallback: function(thead, data, start, end, display ) {
                    var th = $(thead).find('th').eq(0);
                    if(th.hasClass('table-select'))
                        th.html( '<input type="checkbox" class="grid-select-all checkbox-style">' );
                },
                rowCallback:function(row, data, index){
                    var td = $('td:eq(0)', row);
                    var id = $.trim(data[0]);
                    if(td.hasClass('table-select') && id)
                        td.html('<input type="checkbox" class="grid-row-checkbox checkbox-style" value="'+ $.trim(data[0]) +'">');
                    if(td.hasClass('table-index'))
                        td.html(index+1);
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
                $('.grid-row-checkbox').prop('checked',this.checked).trigger('change');
            });


            $('#pjax-container').on('click', '.grid-batch-delete,.grid-row-delete', function () {
                var selected = [];
                if ($(this).hasClass('grid-batch-delete')) {
                    selected = Admin.listSelectedRows();
                    if (!selected) {
                        return false;
                    }
                }

                self.delete($(this).data('url'), selected, $(this).data('type'));
            });

        },
        init: function () {
            if (AdminOnece) {
                this.onece();
                AdminOnece = false;
            }

            $('#pjax-container .box').boxWidget();
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
        icheckEvent:function(){
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
        query: function (q) {
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

        activityMenu: function (url) {
            var current = $('aside .sidebar-menu li a[href="' + url + '"]');
            if (current.length > 0) {
                var parents_li = current.parents('li');
                var parents_treeview = current.parents('.treeview-menu');
                parents_li.addClass('active').addClass('menu-open');
                parents_treeview.slideDown("slow");
                $('aside .sidebar-menu li').not(parents_li).removeClass('active').removeClass('menu-open');
                $('aside .sidebar-menu .treeview-menu').not(parents_treeview).slideUp("slow");
            }
        },

        delete: function (url, ids, type) {
            var message = "";
            if (type == 'trash') {
                message = this.info.trashMessage
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
                        action: function () {
                            $.ajax({
                                method: 'post',
                                url: url,
                                data: {
                                    _method: 'DELETE',
                                    _token: Admin.info.csrf_token,
                                    ids: ids,
                                },
                                success: function (data) {
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

        listSelectedRows: function () {
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
    }
}();