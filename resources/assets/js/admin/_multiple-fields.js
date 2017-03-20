;(function ( $, window, document, undefined ) {

  // Create the defaults once
  var pluginName = 'multipleFields',
    defaults = {
      row: '.multiple-field',
      fieldName: 'multiple', // This should be set by the user or else they won't know how to handle it
      addButtonClass: 'btn btn-primary btn-outline btn-xs multiple-fields-add-more',
      addButtonText: '<i class="fa fa-plus"></i> Add',
      addEventCallback: undefined,
      removeButtonClass: 'btn btn-danger btn-outline btn-xs remove-multiple',
      removeButtonText: '<i class="fa fa-trash"></i> Remove'
    };

  // The actual plugin constructor
  function Plugin( element, options ) {
    this.element = element;
    this.$element = $(element);
    // Extend the options
    this.options = $.extend( {}, defaults, options) ;

    // Create the button instances
    this.$add = $('<a href="#" id="' + this.options.fieldName + '-add-more" class="' + this.options.addButtonClass + '">' + this.options.addButtonText + '</a>');
    this.$remove = $('<a href="#" class="' + this.options.fieldName + '-remove ' + this.options.removeButtonClass + '">' + this.options.removeButtonText + '</a>');

    this._defaults = defaults;
    this._name = pluginName;
    this._template = '';

    return this.init();
  }

  Plugin.prototype = {

    // Initialization
    init: function() {
      this.stripIds().addButtons().createTemplate().delegateEvents().initailizeSort();

      // Fire an intialized event
      this.$element.trigger('initialized');

      return this;
    },

    // Make it sortable
    initailizeSort: function() {
      this.$element.closest('.dd').nestable({
        maxDepth: 1
      });

      return this;
    },

    // Strip the ids from the inputs
    stripIds: function() {
      this.$element.find(':input').removeAttr('id');

      return this;
    },

    // Add Add\Remove buttons to the fields
    addButtons: function() {
      var that = this;
      this.$element.after(this.$add);
      this.$element.find(this.options.row).each(function() {
        $(this).append(that.$remove.clone());
      });

      return this;
    },

    // Create a template from the first item
    createTemplate: function() {

      // If we need to create a custom template and not one from the dom
      if (this.options.template !== undefined) {
        this._template = this.options.template();

        return this;
      }

      var $field = this.$element.find(this.options.row).first();

      // Clone the template
      this._template = $field.clone();
      var $inputs = this._template.find(':input');

      // If there is reset function defined, then call that
      if (this.options.reset !== undefined) {
        this.options.reset($inputs);
      } else {
        $inputs.each(function() {
          var $self = $(this);
          // Handle all the types of inputs
          // I'm only handling the types that are needed now
          // Add handlers as required
          if ($self.is('input')) {
            if ($self.attr('type') == 'checkbox') {
              $self.removeAttr('checked');
            } else {
              // We are going to empty out the value here
              // Again, handle other options before
              $self.val('');
            }
          } else if ($self.is('select')) {
            var $options = $self.find('option');
            $options.removeAttr('selected');
            $options.first().attr('selected', 'selected');
          } else if ($self.is('textarea')) {
            // Empty the textarea
            $self.html('');
          }
        });
      }

      return this;
    },

    // Append a new field into the collection
    appendField: function() {
      // Find the last item in the multiple-field
      var newField = this._template.clone();
      // Add the field after the row
      this.$element.append(newField);

      this.reNumber().initailizeSort();
      this.$element.trigger('added', [newField]);

      return this;
    },

    // Re number the array
    reNumber: function() {
      var counter = 0,
          $fields = this.$element.find(this.options.row),
          fieldName = this.options.fieldName;

      $fields.each(function() {
        var $field = $(this),
            $items = $field.find(':input');

        // Set the right id
        $field.attr('data-id', counter);

        $items.each(function() {
          var $self = $(this),
              name = $self.attr('name'),
              re = new RegExp(fieldName + '\\\[\\\d+\\\]', 'g');

          if (name) {
            // Replace the names
            name = name.replace(re, fieldName + "["+counter+"]");
            $self.attr('name', name);
          }
        });

        counter++;
      });

      return this;
    },

    // Delegate the events
    delegateEvents: function() {
      var that = this;

      // Remove element event
      this.$element.on('click', '.'+this.options.fieldName+'-remove', function(e) {
        e.preventDefault();
        that.$element.trigger('removing', this);
        $(this).closest(that.options.row).remove();
        if ( ! that.$element.find(that.options.row).length) {
          that.appendField();
        }
        that.reNumber();
        that.initailizeSort();
        that.$element.trigger('removed');
      });

      // Add button event
      this.$add.on('click', function(e) {
        if (that.options.addEventCallback !== undefined) {
          e.preventDefault();
          that.options.addEventCallback.call(this);
        } else {
          e.preventDefault();
          that.appendField().reNumber();
        }
      });

      // Ability to reorder by event
      this.$element.on('reNumber', function(e) {
        that.reNumber();
      });

      // Add remove buttons
      this.$element.on('addRemoveButton', function(e) {
        that.$element.find(that.options.row).each(function() {
          var $self = $(this);
          if ($self.find('.' + that.options.fieldName + '-remove').length === 0) {
            $self.append(that.$remove.clone());
          }
        });
      });

      this.$element.on('change', '.dd', function() {
        that.reNumber();
      });

      return this;
    }
  };

  // A really lightweight plugin wrapper around the constructor,
  // preventing against multiple instantiations
  $.fn[pluginName] = function ( options ) {
    return this.each(function () {
      if (!$.data(this, "plugin_" + pluginName)) {
        $.data(this, "plugin_" + pluginName,
        new Plugin( this, options ));
      }
    });
  };

})( jQuery, window, document );