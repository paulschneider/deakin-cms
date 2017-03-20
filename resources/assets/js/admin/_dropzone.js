;(function ( $, window, document, undefined ) {

  if ($.fn.dropzone === undefined) {
    $.getScript('/assets/vendor/dropzone/dist/dropzone.js', function(){
      $('.embedded-dropzone').embededDropzone();
    });
  }

  // undefined is used here as the undefined global
  // variable in ECMAScript 3 and is mutable (i.e. it can
  // be changed by someone else). undefined isn't really
  // being passed in so we can ensure that its value is
  // truly undefined. In ES5, undefined can no longer be
  // modified.

  // window and document are passed through as local
  // variables rather than as globals, because this (slightly)
  // quickens the resolution process and can be more
  // efficiently minified (especially when both are
  // regularly referenced in your plugin).

  // Create the defaults once
  var pluginName = "embededDropzone",
    defaults = {
      paramName: 'file',
      maxFilesSize: 200,
      previewTemplate: undefined,
      thumbnailWidth: 50,
      thumbnailHeight: 50
    };

  // The actual plugin constructor
  function Plugin( element, options ) {
    this.element = element;
    this.$element = $(element);
    this.id = this.$element.attr('id');
    this.options = $.extend( {}, defaults, options);
    this.$dropzoneElement = null;
    this.elementOptions = null;
    this.previewTemplate = $('.dz-preview')[0].outerHTML;
    this.fullscreen = false;

    this._defaults = defaults;
    this._name = pluginName;

    this.init();
  }

  Plugin.prototype = {
    init: function() {
      // Set the dropzone element depending on the type
      this.$dropzoneElement = this.$element;
      if (this.$element.hasClass('dropzone-fullscreen')) {
        this.$dropzoneElement = $(document.body);
        this.options.clickable = true;
        this.fullscreen = true;
      }
      this.setDropzoneOptions()
          .dropzone();

      return this;
    },

    // Set the options for the dropzone
    setDropzoneOptions: function() {
      this.elementOptions = this.$element.data('dropzone-options');
      $.extend(this.options, this.elementOptions);
      this.options.previewTemplate = this.previewTemplate;

      // Add the init events
      this.options.embededDropzone = this;
      this.options.init = this.delegateEvents;
      // Add the initialized flag so that we can test for it
      this.$element.addClass('initialized');

      console.log(this.options);

      return this;
    },

    // Initialize the dropzone
    dropzone: function() {
      return this.$dropzoneElement.dropzone(this.options);
    },

    // Delegate the dropzone event
    delegateEvents: function() {
      this.on('processing', this.options.embededDropzone.processing);
      this.on('addedfile', this.options.embededDropzone.addedFile);
      this.on('complete', this.options.embededDropzone.complete);
      this.on('queuecomplete', this.options.embededDropzone.queueComplete);

      if (this.options.embededDropzone.elementOptions.into !== undefined) {
        this.on('success', this.options.embededDropzone.success);
      }

      if (this.options.embededDropzone.elementOptions.multiple !== undefined) {
        this.on('maxfilesexceeded', this.options.embededDropzone.maxFilesExceeded);
      }
    },

    // Whem
    processing: function(file) {
      if (this.options.path === undefined) {
        this.options.path = window.TREE_FILE_ATTACHMENT;
      }

      this.options.headers = { "X-CSRF-Token" : _token, "X-Attachment-Path" : this.options.path };
    },

    addedFile: function(file) {
      var $preview = $(file.previewElement);

      $preview.find('.data-dz-errormessage').hide();

      if ( ! this.options.embededDropzone.fullscreen) {
        $preview.removeClass('sr-only');
      } else {
        this.options.embededDropzone.$dropzoneElement.removeClass('dz-error dz-complete');
      }

      if ( ! this.options.multiple) {
        if (this.files[1] !== undefined) {
          this.removeFile(this.files[0]);
        }
      }
    },

    errorHandler: function(file) {
      var $preview = $(file.previewElement);

      $preview.find('.dz-progress').hide();
      $preview.addClass('bg-danger');
      $preview.find('.data-dz-errormessage').fadeIn();

      if ( ! empty(this.options.embededDropzone.fullscreen)) {
        this.options.embededDropzone.$dropzoneElement.addClass('dz-error');
        setTimeout(function(){
            this.options.embededDropzone.$dropzoneElement.removeClass('dz-error');
        }, 1000);
      }
    },

    complete: function(file) {
      var $preview = $(file.previewElement);

      $preview.find('.dz-upload')
              .removeClass('progress-bar-info')
              .addClass('progress-bar-success');
    },

    queueComplete: function(file) {
      if (this.options.embededDropzone.fullscreen) {
        var $jstree = $('#attachment-jstree'),
            that = this;

        this.options.embededDropzone.$dropzoneElement.addClass('dz-complete');
        if ($jstree.length) {
          $jstree.find('.jstree-clicked').trigger('click');
          this.options.embededDropzone.$dropzoneElement.removeClass('dz-complete');
        } else {
          setTimeout(function() {
            that.options.embededDropzone.$dropzoneElement.removeClass('dz-complete');
            window.location.reload(true);
          }, 1000);
        }

        this.options.embededDropzone.$dropzoneElement.removeClass('dz-started');
      }
    },

    success: function(file, response) {
      $(this.options.embededDropzone.elementOptions.into).val(response.id).trigger('dropzone:image-added');
    },

    maxFilesExceeded: function(file) {
      // console.log(' File Ignored ');
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
