(function($) {

    CMSAdmin.dateTimePickers = {

        init: function(){
            var $date = $('.input-group.date').not('.has-datepicker');
            var $time = $('.input-group.time input').not('.has-timepicker');

            $date.each(this.bindDatePicker);
            $time.each(this.bindTimePicker);
        },

        bindDatePicker: function(e){

            $(this).datepicker({
                format: 'dd/mm/yyyy',
                keyboardNavigation: false,
                forceParse: false,
                autoclose: true,
                weekStart: 1
            });
        },

        bindTimePicker: function(e){
            $(this).timepicker({
                appendWidgetTo: 'body',
                minuteStep: 10
            }).addClass('has-timepicker');
        }

    };

}(jQuery));
