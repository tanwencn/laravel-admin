$.pjax.defaults.timeout = 5000;
$.pjax.defaults.maxCacheLength = 0;

$(function () {

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
        toastr.error(Admin.info.error_msg);
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
});