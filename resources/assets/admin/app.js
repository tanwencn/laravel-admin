var AdminOnece = true;
var Admin = function () {
    "use strict";
    return {
        info: {},
        data: {},
        boots: [],
        errors: [],
        elfinder: {},
        boot: function (call) {
            this.boots.push(call);
        },
        onece: function () {
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
        },
        init: function () {
            if (AdminOnece) {
                this.onece();
                AdminOnece = false;
            }

            $('.select2').select2();

            toastr.clear();
            if (this.info.toastr_success != '') {
                toastr.success(this.info.toastr_success);
            }

            $.each(Admin.errors, function (i, e) {
                toastr.error(e, Admin.info.failed);
            });
            Admin.errors = [];

            $('.box').boxWidget();
            /** list **/
            $('input,.checkbox-style').iCheck({
                checkboxClass: 'icheckbox_flat-blue',
                increaseArea: '10%' // optional
            });

            $('.grid-row-checkbox').on('ifChanged', function () {
                if (this.checked) {
                    $(this).closest('tr').css('background-color', '#ffffd5');
                } else {
                    $(this).closest('tr').css('background-color', '');
                }
            });

            $('.grid-select-all').on('ifChanged', function (event) {
                if (this.checked) {
                    $('.grid-row-checkbox').iCheck('check');
                } else {
                    $('.grid-row-checkbox').iCheck('uncheck');
                }
            });
            $('.grid-batch-delete,.grid-row-delete').on('click', function () {
                var selected = [];
                if ($(this).hasClass('grid-batch-delete')) {
                    selected = Admin.listSelectedRows();
                    if (!selected) {
                        return false;
                    }
                }

                this.delete($(this).data('url'), selected, $(this).data('type'));
            });
            /** list **/

            $.each(this.boots, function (i, call) {
                call();
            });
            this.boots = [];
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
                /*if ((typeof val) == 'object') {
                    var n = {};
                    //val = val.join('&'+name+'[]=')
                    //name = name + '[]';
                }*/

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
            $('.grid-row-checkbox:checked').each(function () {
                selected.push($(this).data('id'));
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

var Finder = function (c) {
    this.key = Math.floor(Math.random() * (999999 - 100000)) + 100000;
    Finder.instances[this.key] = this;
    var container;
    var completeCall = null;

    var config = {
        title: "",
        url: "",
        query: {
            j_instance_key:this.key,
            disks: ['default'],
            multiple: false
        }
    };

    this.config = function (c) {
        $.each(c, function (key, val) {
            if ($.inArray(key, ["title", "url"]) >= 0) {
                config[key] = val;
            } else {
                config.query[key] = val;
            }
        });
        return this;
    };

    this.config(Admin.elfinder);
    if (c != undefined)
        this.config(c);

    var event_el = null;
    this.click = function (el, call) {
        event_el = $(el);
        var self = this;
        if (typeof call !== 'function') {
            var selector = call;
            call = function (file) {
                $(selector).val(file.url)
            };
        }

        event_el.on('click', function () {
            self.success(call).open();
        });
    };
    this.success = function (call) {
        completeCall = call;
        return this;
    };
    this.getUrl = function () {
        if (config.url.indexOf("?") != -1)
            return config.url + "&" + Admin.query(config.query);
        else
            return config.url + "?" + Admin.query(config.query);
    };
    this.multiple = function (bool) {
        return this.config({multiple: bool})
    };
    this.open = function () {
        container = $.dialog({
            title: config.title,
            content: "URL:" + this.getUrl(),
            animation: 'scale',
            closeAnimation: 'scale',
            backgroundDismiss: true,
            //theme: 'supervan',
            columnClass: 'xlarge',
            /*onContentReady: function () {
                var self = this;
            },*/
        });
        return this;
    };
    this.close = function (file) {
        completeCall(file, event_el);
        container.close();
    };
};
Finder.instances = {};
Finder.instance = function(key){
    return Finder.instances[key];
};
Finder.disk = function (disk) {
    var c = {};
    if(disk != undefined) {
        if (!(disk instanceof Array || disk instanceof Object))
            c.disk = [disk];
        else
            c.disk = [disk];
    }

    return new Finder(c);
};
