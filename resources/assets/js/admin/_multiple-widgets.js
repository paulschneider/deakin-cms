(function($) {
    CMSAdmin.widgets = function() {
    var $widgets = $('.widget-multiple-fields'),
        options = [],
        $add = null,
        $none = null;

    // Once the multiple selector has been initialized fire this function
    $widgets.on('initialized', function() {
        // Cache the elements to toggle
        $add = $widgets.parent().find('#widgets-add-more');
        $none = $widgets.parent().find('.no-more');
        checkSelectValues();
    });

    // When a widget has been added
    $widgets.on('added removed', function(e) {
        checkSelectValues();
    });

    $widgets.on('change', 'select', function(e) {
        checkSelectValues();
    });

    // Initialize the widget
    $widgets.multipleFields({
        fieldName: 'widgets'
    });

    function checkSelectValues() {
        var $selects = $widgets.find('.multiple-field select');

        // Clone them for when they are removed
        if ($allOptions === undefined) {
            $allOptions = $selects.first().find('option').clone().removeAttr('selected');
        }

        $allOptions.each(function() {
            var value = $(this).val();
            if (value) options.push(value);
        });

        // First add back in all the values that needs to be in there
        $selects.each(function() {
            var $self = $(this);

            // Check what values are missing and add them
            $allOptions.each(function() {
                var $option = $(this);
                if ($option.val() &&  ! $self.find('option[value='+$option.val()+']').length) {
                    $self.append($option.clone());
                }
            });
        });

        // Find each of the selects and get their values
        $selects.each(function() {
            // Get the value of this one
            var $self = $(this),
                selected = $self.find('option:selected').val();

            if (selected > 0) {
                $selects.not(this).each(function() {
                    var $select = $(this);
                    removeValueFromSelect($select, selected);
                });

                options = removeFromOptions(options, selected);
                checkButton();
            }
        });
    }

    // Check button
    function checkButton() {
        if ( ! options.length) {
            if ($add.is(':visible')) $add.hide();
            if ( ! $none.is(':visible')) $none.fadeIn();
        } else {
            if ($none.is(':visible')) $none.hide();
            if ( ! $add.is(':visible')) $add.fadeIn();
        }
    }

    // Remove an option form the select
    function removeValueFromSelect($select, selected) {
        $select.find('option[value="'+selected+'"]').remove();
        if ($select.find('option').length < 2) {
            $select.closest('.multiple-field').remove();
        }
    }

    // Remove option from array
    function removeFromOptions(array, value) {
        for(var i = array.length; i--;) {
            if (array[i] == value) {
                array.splice(i, 1);
            }
        }
        return array;
    }
  };
}(jQuery));
