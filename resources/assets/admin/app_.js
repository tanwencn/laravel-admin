toastr.options = {
    progressBar: true
};
$.pjax.defaults.timeout = 5000;
$.pjax.defaults.maxCacheLength = 0;
NProgress.configure({parent: '#pjax-container'/*, showSpinner:false*/});
NProgress.start();
$(function () {

    $(document).ajaxStart(function () {
        NProgress.start();
    });
    $(document).ajaxStop(function () {
        NProgress.done();
    });
    $(document).on('pjax:start', function () {
        NProgress.start();
    });
    $(document).on('pjax:end', function () {
        NProgress.done();
    });

    $(document).pjax('a', '#pjax-container');

    $(document).on('pjax:timeout', function (event) {
        event.preventDefault();
        toastr.warning(timeout_load);
        NProgress.done();
    });

    $(document).on('submit', 'form', function (event) {
        $.pjax.submit(event, '#pjax-container');
    });

    $(document).on('pjax:send', function (xhr) {
        if (xhr.relatedTarget && xhr.relatedTarget.tagName && xhr.relatedTarget.tagName.toLowerCase() === 'form') {
            $submit_btn = $('form [type="submit"]');
            if ($submit_btn) {
                $submit_btn.attr("disabled", true);
            }
        }
        NProgress.start();
    });

    $(document).on('pjax:complete', function (xhr) {
        if (xhr.relatedTarget && xhr.relatedTarget.tagName && xhr.relatedTarget.tagName.toLowerCase() === 'form') {
            $submit_btn = $('form [type="submit"]');
            if ($submit_btn) {
                $submit_btn.removeAttr("disabled");
            }
        }
        NProgress.done();
    });

    NProgress.done();
    Blog.init();

});

function currentMenu(url) {
    var current = $('aside .sidebar-menu li a[href="' + url + '"]');
    if (current.length > 0) {
        var parents_li = current.parents('li');
        var parents_treeview = current.parents('.treeview-menu');
        parents_li.addClass('active').addClass('menu-open');
        parents_treeview.slideDown("slow");
        $('aside .sidebar-menu li').not(parents_li).removeClass('active').removeClass('menu-open');
        $('aside .sidebar-menu .treeview-menu').not(parents_treeview).slideUp("slow");
    }
}

var Blog = function () {
    "use strict";
    return {
        init: function () {
            $('.box').boxWidget();
            /** list **/
            $('.grid-row-checkbox').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                increaseArea: '10%' // optional
            }).on('ifChanged', function () {
                if (this.checked) {
                    $(this).closest('tr').css('background-color', '#ffffd5');
                } else {
                    $(this).closest('tr').css('background-color', '');
                }
            });

            $('.grid-select-all').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                increaseArea: '10%' // optional
            }).on('ifChanged', function (event) {
                if (this.checked) {
                    $('.grid-row-checkbox').iCheck('check');
                } else {
                    $('.grid-row-checkbox').iCheck('uncheck');
                }
            });
            $('.grid-batch-delete,.grid-row-delete').on('click', function () {
                if ($(this).hasClass('grid-row-delete')) {
                    var id = $(this).data('id');
                    if (!id)
                        return false;
                } else {
                    var selected = listSelectedRows();
                    if (!selected) {
                        return false;
                    }
                    var id = selected.join();
                }

                ajaxDelete($(this).data('url'), id, $(this).data('type'));
            });
            /** list **/
        }
    }
}();
