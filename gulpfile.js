require('es6-promise').polyfill();

var gulp = require('gulp');
var Elixir = require('laravel-elixir');
var plumber = require('gulp-plumber');
var gutil = require('gulp-util');
var cmq = require('gulp-merge-media-queries');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var notify = require('gulp-notify');
var autoprefixer = require('gulp-autoprefixer');
var minifyCss = require('gulp-minify-css');
var Task = Elixir.Task;

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

Elixir.config.sassDir = 'resources/assets/sass';
Elixir.config.vendorDir = 'resources/assets/vendor';
Elixir.config.jsOutput = 'public/assets/js';

/*
|---------------------------------------------------------------------
| Combine media query scripts
|---------------------------------------------------------------------
*/


Elixir(function(mix) {

  // Copy files
  mix.copy('resources/assets/images', 'public/assets/images');
  mix.copy('resources/assets/js/admin/ck-full.js', 'public/assets/js/admin/ck-full.js');
  mix.copy('resources/assets/js/admin/ck-basic.js', 'public/assets/js/admin/ck-basic.js');
  mix.copy('resources/assets/js/admin/ck-micro.js', 'public/assets/js/admin/ck-micro.js');
  mix.copy('resources/assets/js/admin/ck-styles.js', 'public/assets/js/admin/ck-styles.js');
  mix.copy('resources/assets/vendor', 'public/assets/vendor');
  mix.copy('resources/assets/vendor/iconinc-admin-theme/dist/css/animate.css', 'resources/assets/sass/plugins/animate.scss');
  mix.copy('resources/assets/vendor/iconinc-admin-theme/dist/css/plugins/toastr/toastr.min.css', 'resources/assets/sass/plugins/toastr.scss');
  mix.copy('resources/assets/vendor/iconinc-admin-theme/dist/css/plugins/datapicker/datepicker3.css', 'resources/assets/sass/plugins/datepicker3.scss');
  mix.copy('resources/assets/vendor/iconinc-admin-theme/dist/css/plugins/dataTables/dataTables.bootstrap.css', 'resources/assets/sass/plugins/dataTables.bootstrap.scss');
  mix.copy('resources/assets/vendor/iconinc-admin-theme/dist/css/plugins/dataTables/dataTables.responsive.css', 'resources/assets/sass/plugins/dataTables.responsive.scss');
  mix.copy('resources/assets/vendor/iconinc-admin-theme/dist/css/plugins/dataTables/dataTables.tableTools.min.css', 'resources/assets/sass/plugins/dataTables.tableTools.min.scss');

  // SASS
  mix.sass('resources/assets/sass/frontend/email.scss', 'public/assets/css/frontend');
  mix.sass('resources/assets/sass/frontend/footer.scss', 'public/assets/css/frontend');
  mix.sass('resources/assets/sass/admin/editor.scss', 'public/assets/css/admin');
  mix.sass('resources/assets/sass/admin/admin.scss', 'public/assets/css/admin');
  mix.sass('resources/assets/sass/frontend/screen.scss', 'public/assets/css/frontend');
  mix.sass('resources/assets/sass/frontend/print.scss', 'public/assets/css/frontend');
  mix.sass('resources/assets/sass/font-awesome.scss', 'public/assets/css');

  // Only do this for production, slows things down
  if (mix.production) {
    mix.version([
      'public/assets/css/admin/editor.css',
      'public/assets/css/font-awesome.css',
      'public/assets/css/admin/admin.css',
      'public/assets/css/frontend/screen.css',
      'public/assets/css/frontend/print.css',
      'public/assets/css/frontend/footer.css',
      'public/assets/js/frontend/vendors.js',
      'public/assets/js/frontend/main.js',
      'public/assets/js/admin/admin.js',
      'public/assets/js/admin/vendors.js',
      'public/assets/js/bootstrap.js'
    ]);

    mix.copy('public/assets/images', 'public/build/assets/images');
  }

  // Bootstrap Js
  mix.scripts([
      'affix.js',
      'alert.js',
      'button.js',
      'carousel.js',
      'collapse.js',
      'dropdown.js',
      'tab.js',
      'transition.js',
      'scrollspy.js',
      'modal.js',
      'tooltip.js',
      'popover.js'
    ],
    'public/assets/js/bootstrap.js',
    Elixir.config.vendorDir + "/bootstrap-sass/vendor/assets/javascripts/bootstrap");

  // Admin vendors
  mix.scripts([
      '../vendor/iconinc-admin-theme/dist/js/plugins/metisMenu/jquery.metisMenu.js',
      '../vendor/iconinc-admin-theme/dist/js/plugins/slimscroll/jquery.slimscroll.min.js',
      '../vendor/iconinc-admin-theme/dist/js/plugins/dataTables/jquery.dataTables.js',
      '../vendor/iconinc-admin-theme/dist/js/plugins/dataTables/dataTables.bootstrap.js',
      '../vendor/iconinc-admin-theme/dist/js/plugins/dataTables/dataTables.responsive.js',
      '../vendor/iconinc-admin-theme/dist/js/inspinia.js',
      '../vendor/iconinc-admin-theme/dist/js/plugins/pace/pace.min.js',
      '../vendor/iconinc-admin-theme/dist/js/plugins/toastr/toastr.min.js',
      '../vendor/iconinc-admin-theme/dist/js/plugins/datapicker/bootstrap-datepicker.js',
      '../vendor/iconinc-admin-theme/dist/js/plugins/nestable/jquery.nestable.js',
      '../vendor/foundation/js/foundation/foundation.js',
      '../vendor/foundation/js/foundation/foundation.abide.js',
      '../vendor/magnific-popup/dist/jquery.magnific-popup.js',
    ],
    'public/assets/js/admin/vendors.js');


  // Admin js
  mix.scripts([
      'admin/admin.js',
      'admin/_attachments.js',
      'admin/_timepicker.js',
      'admin/_multiple-fields.js',
      'admin/_multiple-widgets.js',
      'admin/_multiple-sections.js',
      'admin/_video.js',
      'admin/_wysiwyg.js',
      'admin/_multiple-related.js',
      'admin/_multiple-schedule.js',
      'admin/_banners.js',
      'admin/_menu-selector.js',
      'admin/_dropzone.js',
      'admin/_session.js',
      'admin/_icon-picker.js',
      'admin/_date-time.js',
      'admin/_editor.js',
      '_collapsibles.js',
      'admin/_functionRouter.js'
    ],
    'public/assets/js/admin/admin.js');

  // Frontend vendors
  mix.scripts([
    '../vendor/foundation/js/foundation/foundation.js',
    '../vendor/foundation/js/foundation/foundation.abide.js',
    '../vendor/magnific-popup/dist/jquery.magnific-popup.js',
    '../vendor/js-cookie/src/js.cookie.js',
    '../vendor/nprogress/nprogress.js',
    '../vendor/jQuery.mmenu/dist/js/jquery.mmenu.all.min.js',
  ], 'public/assets/js/frontend/vendors.js');

  // Frontend js
  mix.scripts([
    'frontend/main.js',
    '_collapsibles.js',
    'frontend/_social.js',
    'frontend/_tableFormatter.js',
    'frontend/forms/_contact-form.js',
    'frontend/forms/_newsletter-form.js',
    'frontend/forms/_tick-form.js',
    'frontend/_actionButtons.js',
    'frontend/_recaptcha.js',
    'frontend/_video-list.js',
    'frontend/_magnific.js',
    'frontend/_home.js',
    'frontend/_menu.js',
    'frontend/_search.js',
    'frontend/_partners.js',
    'frontend/_functionRouter.js',
  ], 'public/assets/js/frontend/main.js');

  // Frontend js
  mix.scripts([
    'frontend/footer.js',
  ], 'public/assets/js/frontend/footer.js');
});

