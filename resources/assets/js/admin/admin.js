/* ========================================================================
 * Admin theme minimal requirements
 * http://www.iconinc.com.au
 * ======================================================================== */
// Storing the namespaced object
window.CMSAdmin = {};

/* ========================================================================
 * Custom JS
 * http://www.iconinc.com.au
 * ======================================================================== */


(function($) {

    $(function(){

        /**
         * THEME OVERIDE
         */
        if ($('#side-menu').length) {
            $('#side-menu').data('metisMenu').settings.doubleTapToGo = true;
        }


        /**
         * TOASTR ALERT DEFAULTS
         */
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "progressBar": false,
            "positionClass": "toast-top-full-width",
            "onclick": null,
            "showDuration": "200",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };


        /**
         * TOASTR ALERT ALL MESSAGES
         */
        $('.toastr-alerts .alert').each(function(){

            $(this).find('button.close').remove();

            var positionClass = toastr.options.positionClass;
            var text = $(this).html();

            if (text.length < 50) {
                // We'll let small info and success be a toast
                positionClass = "toast-top-right";
            }

            if ($(this).hasClass('alert-success')) {
                toastr.success(text, null, {positionClass: positionClass});
            } else if ($(this).hasClass('alert-danger')) {
                toastr.error(text, null, {timeOut: 15000, progressBar: true});
            } else if ($(this).hasClass('alert-warning')) {
                toastr.warning(text, null, {timeOut: 15000, progressBar: true});
            } else {
                toastr.info(text, null, {positionClass: positionClass});
            }

            $(this).remove();
        });

    }); // End jQuery Ready

}(jQuery));



/* ========================================================================
 * Custom Addons
 * http://www.iconinc.com.au
 * ======================================================================== */