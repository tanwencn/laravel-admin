let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.setPublicPath('public/').setResourceRoot('../')
    .copy('resources/images/logo.png', 'public/logo.png')
    .copy('resources/images/logo2.png', 'public/logo2.png')
    .sass('resources/sass/vendor.scss', 'css')
    .sass('resources/sass/app.scss', 'css')
    .combine([
        'node_modules/admin-lte/plugins/jquery/jquery.js',
        'node_modules/admin-lte/plugins/bootstrap/js/bootstrap.bundle.js',
        'node_modules/admin-lte/plugins/datatables/jquery.dataTables.js',
        'node_modules/admin-lte/plugins/datatables-bs4/dataTables.bootstrap4.js',
        'node_modules/admin-lte/plugins/select2/js/select2.js',
        'node_modules/admin-lte/plugins/moment/moment-with-locales.js',
        'node_modules/admin-lte/plugins/fastclick/fastclick.js',
        'node_modules/admin-lte/plugins/bootstrap-switch/bootstrap-switch.js',
        'node_modules/admin-lte/plugins/popper/umd/popper.js',
        'node_modules/admin-lte/plugins/overlayScrollbars/js/OverlayScrollbars.js',
        'node_modules/admin-lte/plugins/daterangepicker/daterangepicker.js',
        'node_modules/admin-lte/plugins/bootstrap-switch/js/bootstrap-switch.js',
        'resources/js/jquery.pjax.js',
        'node_modules/admin-lte/plugins/jquery-ui/jquery-ui.js',
        'node_modules/admin-lte/plugins/toastr/toastr.min.js',
        'node_modules/icheck/icheck.js',
        'node_modules/jquery-confirm/dist/jquery-confirm.min.js',
        'node_modules/admin-lte/dist/js/adminlte.js',
        'node_modules/summernote/dist/summernote-bs4.js',
        'node_modules/nprogress/nprogress.js',
        '../laravel-elfinder/resources/assets/app.js'
    ], 'public/js/vendor.js')
    .version()
    .js('resources/js/app.js', 'js')
;
