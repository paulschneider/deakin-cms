(function($) {
    CMSAdmin.menu_selector = function() {

        $('#attach-menu-select').change(function(){
            var $into = $('#attach-menu-parent');

            var uri_addon = $('.menu-selector').data('url-addon');
            if (uri_addon === undefined) {
                uri_addon = '';
            }

            $into.find('option').remove();

            if ( ! $(this).val() ) {
                var newOption = $('<option/>').attr('value', '').text('Please select a menu');
                $into.append(newOption);
            } else {

                $.get('/admin/menus/' + $(this).val() + '/links/ajax' + uri_addon, function(data){

                    var keys = Object.keys(data);

                    for (i = 0; i < keys.length; i++) {
                        var key = keys[i];
                        var newOption = $('<option/>').attr('value', data[i].key).text(data[i].value);

                        $into.append(newOption);
                    }
                });
            }
        });

  };
}(jQuery));
