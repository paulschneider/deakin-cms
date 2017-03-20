(function($) {
  CMSAdmin.sections = function() {
    var $sections = $('.section-multiple-fields'),
      $picker = $('.sections .template-picker-wrapper'),
      $listElement = $sections.closest('.dd'),
      templateUrl = '/admin/sections/template/',
      imageUrl = '/admin/attachments/url/',
      blockUrl = '/admin/sections/block-render/',
      $add = null,
      $change = $('<a href="#" class="btn btn-warning btn-outline btn-xs sections-change"><i class="fa fa-exchange"></i></a>'),
      $cancelChange = $('<a href="#" class="btn btn-danger btn-outline btn-xs sections-cancel-change"><i class="fa fa-close"></i> Cancel</a>'),
      $padTop = $('<a href="#" class="btn btn-primary btn-outline btn-xs sections-pad-top"><i class="fa fa-caret-up"></i></a>'),
      $padBottom = $('<a href="#" class="btn btn-primary btn-outline btn-xs sections-pad-bottom"><i class="fa fa-caret-down"></i></a>'),
      $padLeft = $('<a href="#" class="btn btn-primary btn-outline btn-xs sections-pad-left"><i class="fa fa-caret-left"></i></a>'),
      $padRight = $('<a href="#" class="btn btn-primary btn-outline btn-xs sections-pad-right"><i class="fa fa-caret-right"></i></a>'),
      $clonedPicker = $('<div class="template-switcher clearfix"></div>'),
      selectOptions = {},
      $removeImage = $('<a href="#" class="btn btn-danger btn-outline btn-xs sections-remove-image">Remove image <i class="fa fa-close"></i></a>'),
      contentEditableSupport = "contentEditable" in document.documentElement;

    // Once the multiple selector has been initialized fire this function
    $sections.on('initialized', function() {
      $sections.find('.wysiwyg').addClass('initialized');
      // Delegate the events
      delegateEvents();
      // Add the extra buttons for this section
      addChangeButtons();
      // Initialize the switcher
      $picker.find('.picker').clone().appendTo($clonedPicker);
      // Initialize the editable titles
      editableTitle();
      // Toggle the image remove buttons
      toggleRemoveImageButton();
      // Widgets
      widget();
      // Add The section padding options
      addPaddingButtons();
    });

    // Initialize the widget
    $sections.multipleFields({
      fieldName: 'sections',
      removeButtonText: '<i class="fa fa-trash"></i>',
      addButtonClass: 'btn btn-primary btn-outline btn-xs multiple-fields-add-more',
      addEventCallback: function(e) {
        showAddDialog.call(this);
      }
    });

    function addChangeButtons() {
      $sections.find('.dd-item').each(function() {
        var $self = $(this);

        if ($self.find('.sections-change').length === 0) {
          $self.append($change.clone());
        }
      });
    }

    function addPaddingButtons() {
      $sections.find('.dd-item').each(function() {
        var $self = $(this);

        if ($self.find('.sections-pad-top').length === 0 && $self.find('.section-top-padding-field').length) {
          var $topClone = $padTop.clone(),
            $bottomClone = $padBottom.clone(),
            valTop = $self.find('.section-top-padding-field').val(),
            valBottom = $self.find('.section-bottom-padding-field').val();

          if (valTop.length > 1) $topClone.addClass('selected').removeClass('btn-outline ');
          if (valBottom.length > 1) $bottomClone.addClass('selected').removeClass('btn-outline ');

          $self.append($topClone);
          $self.append($bottomClone);
        }

        if ($self.find('.sections-pad-left').length === 0 && $self.find('.section-left-padding-field').length) {
          var $leftClone = $padLeft.clone(),
            $rightClone = $padRight.clone(),
            valLeft = $self.find('.section-left-padding-field').val(),
            valRight = $self.find('.section-right-padding-field').val();

          if (valLeft.length > 1) $leftClone.addClass('selected').removeClass('btn-outline ');
          if (valRight.length > 1) $rightClone.addClass('selected').removeClass('btn-outline ');

          $self.append($leftClone);
          $self.append($rightClone);
        }
      });
    }

    // Add or remove the remove image buttons
    function toggleRemoveImageButton() {
      var $dropzones = $sections.find('.image-field');

      $dropzones.each(function() {
        var $self = $(this),
          value = $self.find('input[type=hidden]').val();

        $self.find('.sections-remove-image').remove();
        if (value) {
          $self.append($removeImage.clone());
        }
      });
    }

    // Show the add new
    function showAddDialog() {
      // Cache the add button
      $add = $(this);
      // Show the picker and hide the button
      togglePicker();
    }

    // Add the wysiwyg
    function wysiwyg() {
      $sections.find('.wysiwyg').not('.initialized').each(function() {
        convertWysiwyg($(this));
      });
    }

    // Get the widget data
    function widget() {
      var $widgets = $('.multiple-section.block-widget');
      $widgets.not('.initialized').each(function() {
        var $self = $(this),
          selected = $self.find('.block-switcher').val();

        if (selected) {
          var promise = getBlock(selected);
          promise.done(function(data) {
            injectBlock(data, $self);
          }).fail(function(data) {
            console.log(data);
          });
        }
      });
    }

    // Add the content editable title
    function editableTitle() {
      if (contentEditableSupport) {
        var $titles = $sections.find('.editable-title').not('.initialized');

        // Add a H1 tag after it
        $titles.each(function() {
          var $self = $(this),
            tag = $self.data('tag'),
            classes = $self.data('editable-class');

          if (tag === undefined) tag = 'h2';
          $new = $('<' + tag + ' contentEditable="true" class="editable-title-element"></' + tag + '>').html($self.val());
          if (classes !== undefined) $new.addClass(classes);

          $self.addClass('initialized').hide();

          // Append the tag after
          $self.after($new);
        });
      }
    }

    function convertWysiwyg($textarea) {

      if (!$textarea.html()) {
        $textarea.html('<p></p>');
      }

      CMSAdmin.wysiwyg($textarea.addClass('initialized'));
    }

    function destroyWysiwyg($item) {
      $item.find('textarea.wysiwyg').each(function() {
        var $self = $(this),
          id = $self.attr('id');

        if (!id) id = $self.attr('name');
        if (CKEDITOR.instances[id]) CKEDITOR.instances[id].destroy(false);
      });
    }

    // Apply dropzone to elements and add remove button
    function dropzone() {
      $('.embedded-dropzone').not('.initialized').embededDropzone();
    }

    // Add a template
    function addTemplate(e) {
      e.preventDefault();
      var $self = $(this),
        promise = getTemplate($self.data('template'), $sections.find('.dd-item').length);

      promise.done(injectTemplate)
        .fail(function(data) {
          console.log(data);
        });
    }

    // Switch the template
    function switchTemplate(e) {
      e.preventDefault();
      var $self = $(this),
        $section = $self.closest('.dd-item'),
        fieldData = getFieldData($section),
        id = $section.find('.section-id').val(),
        promise = getTemplate($self.data('template'), $section.data('id'));

      // Get the new template
      promise.done(function(data) {
        replaceTemplate(data, $section, fieldData);
      });
    }

    // Copy the content
    function getFieldData($section) {
      var fields = {};

      $section.find(':input').each(function() {
        var $field = $(this);

        if ($field.attr('name').length) {
          var name = $field.attr('name'),
            re = new RegExp('\\]\\[(.+)\\]', 'gi'),
            matches = re.exec(name);

          if (matches) {
            if ($field.attr('type') === 'radio') {
              if ($field.attr('checked') === 'checked') {
                fields[matches[1]] = $field.val();
              }
            } else {
              fields[matches[1]] = $field.val();
            }
          }
        }
      });

      return fields;
    }

    function replaceTemplate(data, $section, fieldData) {
      var $template = $(data.template),
        classes = getPropertiesString(CMSAdmin.sectionOptions),
        thisClass = keyToClass($template.find('.section-template').val());

      $template = mapFieldData($template, fieldData);
      $section.removeClass(classes);
      $section.addClass(thisClass);

      destroyWysiwyg($section);
      $section.children().remove();
      $section.append($template.children());
      addSectionExtraClasses($section);
      wysiwyg();
      dropzone();
      checkColumnColors($section);
      editableTitle();
      $sections.trigger('addRemoveButton');
      $('.video-url').trigger('blur');
      addChangeButtons();
      addPaddingButtons();
    }

    function addSectionExtraClasses($section) {
      var options = getOptions($section.find('.section-template').val(), 'classes');
      if (options && options.length) {
        $section.addClass(options);
      }
    }

    function mapFieldData($template, fieldData) {
      var $id = $template.find('.section-id'),
        re = new RegExp('sections\\[.+?\\]', 'g'),
        stub = re.exec($id.attr('name'))[0],
        options = getOptions($template.find('.section-template').val(), 'mapping'),
        newField = getFieldData($template);

      // Go through the mapping and set the value
      $.each(newField, function(field, value) {
        var $field = $template.find('[name="' + stub + '[' + field + ']"]');

        if ($field.length && field !== 'template') {
          // First set the value if it already exists in the array
          if (fieldData[field] !== undefined) {
            if ($field.attr('type') === 'radio') {
              // Find all the options
              var $options = $template.find('[name="' + $field.attr('name') + '"]');
              $options.each(function() {
                var $item = $(this);
                if ($item.val() === fieldData[field]) {
                  $item.attr('checked', 'checked').next('.color-swatch').addClass('selected');
                } else {
                  $item.removeAttr('checked').next('.color-swatch').removeClass('selected');
                }
              });
            } else {
              $field.val(fieldData[field]);
            }
          }
          // Now check if it exists in the extra field maps
          if (options !== undefined && options[field] !== undefined) {
            $.each(options[field], function() {
              if (fieldData[this.toString()]) {
                $field.val($field.val() + fieldData[this.toString()]);
              }
            });
          }
        }
      });

      return $template;
    }

    // Inject the template to the list
    function injectTemplate(data) {
      var $template = $(data.template);
      addSectionExtraClasses($template);
      $sections.append($template);
      togglePicker();
      wysiwyg();
      $sections.trigger('addRemoveButton');
      addChangeButtons();
      addPaddingButtons();
      editableTitle();
      dropzone();
    }

    // Inject the data
    function injectBlock(data, $section) {
      $section.addClass('initialized').find('.block-content').append(data);
    }

    // Show hide the picker
    function togglePicker() {
      if ($picker.hasClass('hidden')) {
        $picker.removeClass('hidden');
        $add.hide();
      } else {
        $picker.addClass('hidden');
        $add.show();
      }
    }

    function toggleSwitcher(e) {
      e.preventDefault();
      var $self = $(this),
        $section = $self.closest('.dd-item'),
        $switcher = $section.find('.template-switcher'),
        $cancel = $cancelChange.clone();

      if ($switcher.length === 0) {
        var template = $section.find('.section-template').val(),
          $newPicker = $clonedPicker.clone();

        template = keyToClass(template);
        $newPicker.find('.' + template).remove();
        $self.after($cancel);
        $cancel.after($newPicker);
      } else {
        var $parent = $self.closest('.dd-item');
        $parent.find('.sections-cancel-change').remove();
        $switcher.remove();
      }
    }

    function keyToClass(key) {
      var re = new RegExp('_', 'g');

      return key.replace(re, '-');
    }

    function getOptions(template, options) {
      console.log(template, options)
      return CMSAdmin.sectionOptions[template][options];
    }

    function getPropertiesString(options) {
      var classes = '';

      for (var property in options) {
        if (options.hasOwnProperty(property)) {
          classes += property + " ";
        }
      }

      return classes;
    }

    // Change section color
    function changeColor(e, option, switcher, $target) {

      var $self = $(this),
        color = $self.val(),
        $section = $self.closest('.multiple-section'),
        $parent = $self.closest(switcher.toString());

      // Remove selected
      $parent.find('.color-swatch').removeClass('selected');

      if ($section.find('.section-template').length) {
        var options = getOptions($section.find('.section-template').val(), option),
            classes = getPropertiesString(options);


        // Add the class to the item
        $target.removeClass(classes).addClass(color);
      } else {
        $target.attr('class', null).addClass(color);
      }

      $self.parent().find('.color-swatch').addClass('selected');

    }

    function checkColumnColors($section) {
      $section.find('.section-column-color input[checked=checked]').trigger('change');
    }

    function changeEditableTitle(e) {
      var $self = $(this),
        $field = $self.prev('.editable-title'),
        value = $self.html();

      value = strip_tags(value, '<b><strong><br>');

      $field.val(value);
    }

    function removeImage(e) {
      e.preventDefault();
      var $self = $(this),
        $parent = $self.closest('.image-field').parent(),
        $target = $parent.find('.image-target');

      // It's on the parent
      if ($target.length === 0) {
        $target = $parent.closest('.image-target');
      }

      $target.css('background-image', '');
      $self.parent().find('input[type=hidden]').val('');
      $self.remove();
    }

    function imageChanged(e) {
      var $self = $(this),
        $parent = $self.closest('.image-field').parent();
      promise = getImageUrl($self.val());

      // Wait for the async method to return
      promise.done(function(data) {
        var $target = $parent.find('.image-target');

        // It's on the parent
        if ($target.length === 0) {
          $target = $parent.closest('.image-target');
        }

        $target.removeAttr('style')
          .attr('style', 'background-image: url(' + data.url + ');');
        toggleRemoveImageButton();
      }).fail(function(data) {
        console.log(data);
      });
    }

    // Show the video url
    function showVideo(e) {
      var $self = $(this),
        $section = $self.closest('.multiple-section'),
        url = $section.find('.video-url').val();

      if (url.length > 1) {
        // Open it in a new tab, but we can implement the modal
        var win = window.open(url, '_blank');
        win.focus();
      }
    }

    // Show or hide a video field of a column
    function toggleVideo(e) {
      var $self = $(this),
        $element = $self.closest('.col, .half-width').find('.inner'),
        url = $self.val();

      if (url.length > 0) {
        $element.addClass('has-video');
      } else {
        $element.removeClass('has-video');
      }
    }

    function switchBlock(e) {
      var $self = $(this),
        selected = $self.val(),
        $section = $self.closest('.multiple-section');

      $section.removeClass('initialized');
      $section.find('.block-content').children().remove();

      if (selected.length) {
        widget();
      }
    }

    function toggleArrow(e) {
      var $self = $(this),
        selected = $self.is(':checked'),
        $column = $self.closest('.col').find('.inner');

      $column.toggleClass('has-arrow');
    }

    /* Toggle the padding values */
    function togglePadding(e) {
      e.preventDefault();
      var $self = $(this),
        $section = $self.closest('.dd-item'),
        classname = '',
        $field;

      // Toggle the classes
      $self.toggleClass('btn-outline selected');

      // Find out which one it is
      if ($self.hasClass('sections-pad-top')) {
        $field = $section.find('.section-top-padding-field');
        classname = 'sections-pad-top';

      } else if ($self.hasClass('sections-pad-bottom')) {
        $field = $section.find('.section-bottom-padding-field');
        classname = 'sections-pad-bottom';

      } else if ($self.hasClass('sections-pad-left')) {
        $field = $section.find('.section-left-padding-field');
        classname = 'sections-pad-left';

      } else if ($self.hasClass('sections-pad-right')) {
        $field = $section.find('.section-right-padding-field');
        classname = 'sections-pad-right';
      } else {
        return;
      }

      // Add the value to the field
      if ($field.val().length > 1) {
        $field.val('');
        $section.removeClass(classname);
      } else {
        $section.addClass(classname);
        $field.val(classname);
      }
    }

    function strip_tags(input, allowed) {
      // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
      allowed = (((allowed || "") + "").toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []).join('');
      var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
        commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;

      return input.replace(commentsAndPhpTags, '').replace(tags, function($0, $1) {
        return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
      });
    }

    // Delegate the events
    function delegateEvents() {
      // When the cancel button has been clicked for a the template picker
      $(document).on('click', '.cancel-picker', function(e) {
        e.preventDefault();
        togglePicker();
      });

      // Add the handler for when a new template has been selected
      $(document).on('click', '.template-picker .picker', addTemplate);
      $(document).on('click', '.template-switcher .picker', switchTemplate);

      // Show the template switcher
      $(document).on('click', '.sections-change', toggleSwitcher);
      $(document).on('click', '.sections-cancel-change', toggleSwitcher);

      // Clean up when an item is being removed
      $sections.on('removing', function(e, element) {
        var $section = $(element).closest('.dd-item');

        destroyWysiwyg($section);
      });

      // Remove the wysiwyg when the drag has started
      $sections.on('dragStart', '.dd-item', function(e) {
        var $section = $(this);
        $section.find('textarea.wysiwyg').removeClass('initialized');
        destroyWysiwyg($section);
      });

      // Convert the dragged item
      $(document).on('dragStarted', '.dd-dragel', function(e) {
        $(this).find('.wysiwyg').each(function() {
          convertWysiwyg($(this));
        });
      });

      // Destroy the wysiwyg of drop
      $(document).on('dragStop', '.dd-dragel', function(e) {
        var $section = $(this);
        $section.find('textarea.wysiwyg').removeClass('initialized');
        destroyWysiwyg($(this));
      });

      // Recreate the wysiwyg
      $(document).on('change', '.dd', function(e) {
        wysiwyg();
      });

      // Color Changer
      $(document).on('change', '.section-column-color input', function(e) {
        var $target = $(this).closest('.col, .half-width, .color-target-top').find('.color-changer');
        changeColor.call(this, e, 'colors', '.section-column-color', $target);
      });

      // Container color changer
      $(document).on('change', '.section-container-color input', function(e) {
        var $target = $(this).closest('.color-target-container');

        console.log($target);
        changeColor.call(this, e, 'colors', '.section-container-color', $target);
      });

      // Change Section Color
      $(document).on('change', '.section-color input', function(e) {
        var $target = $(this).closest('.multiple-section');
        changeColor.call(this, e, 'section_colors', '.section-color', $target);
      });

      // Change Entity Color
      $(document).on('change', '.entity-color input', function(e) {
        var $target = $('#entity-color-box');
        changeColor.call(this, e, 'colors', 'entity-color', $target);
      });

      // Arrow Changer
      $(document).on('change', '.arrow-selector input', toggleArrow);

      // When an editable title has been changed
      $(document).on('DOMSubtreeModified', '.editable-title-element', changeEditableTitle);

      // Remove an image
      $(document).on('click', '.sections-remove-image', removeImage);

      $(document).on('dropzone:image-added', '.image-field input[type=hidden]', imageChanged);
      $(document).on('click', '.multiple-section.video .play-button', showVideo);
      $(document).on('click', '.multiple-section.video-testimonial .play-button', showVideo);
      $(document).on('click', '.multiple-section.video-list .play-button', showVideo);
      $(document).on('click', 'a.sections-pad-top, a.sections-pad-bottom, a.sections-pad-left, a.sections-pad-right', togglePadding);

      // Video url field changes
      $(document).on('blur', '.video-url', toggleVideo);
      // When a block has been switched
      $(document).on('change', '.block-switcher', switchBlock);
    }

    /*
    |---------------------------------------------------------------------
    | Get data from url
    |---------------------------------------------------------------------
    | Get content for templates and blocks
    |
    */

    // Get the template. Returns a promise
    function getTemplate(template, counter) {
      return $.ajax({
        url: templateUrl + template + '/' + counter
      });
    }

    // Get the file url
    function getImageUrl(file) {
      return $.ajax({
        url: imageUrl + file
      });
    }

    function getBlock(block_id) {
      return $.ajax({
        url: blockUrl + block_id
      });
    }
  };
}(jQuery));

