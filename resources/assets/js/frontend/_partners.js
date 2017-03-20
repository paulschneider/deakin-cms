(function($){

    $(function(){

        var partnerCycle = function(){
            var $container = $('.partners-container'),
                $current = $('li.current', $container),
                $next = $current.next('li');

            if ( ! $next.length) {
                $next = $('li', $container).first();
            }

            $current.removeClass('current');
            $next.addClass('current');

            setTimeout(function(){
                partnerCycle();
            }, 3000);

        };

        partnerCycle();

    });


})(jQuery);
