(function($) {
    CMSAdmin.schedule = function() {
        var $schedule = $('.schedule-multiple-fields');

        // Init the bindings
        $schedule.on('initialized', bindScheduledTask);

        // Initialize the widget
        $schedule.multipleFields({
            fieldName: 'schedule',
            removeButtonText: '<i class="fa fa-trash"></i>'
        }).on('added', bindScheduledTask);

        function bindScheduledTask(e){
            $('.date', this).not('.has-datepicker').each(function(){
                var $e = $(this);

                $e.datepicker({
                    format: 'dd/mm/yyyy',
                    startDate: 'today',
                    keyboardNavigation: false,
                    forceParse: false,
                    autoclose: true,
                    weekStart: 1
                });
            }).addClass('has-datepicker');


            $('.time input', this).not('.has-timepicker').timepicker({
                appendWidgetTo: 'body',
                minuteStep: 10
            }).addClass('has-timepicker');
        }
  };
}(jQuery));
