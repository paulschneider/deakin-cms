(function($, undefined) {
  CKEDITOR.dialog.add('icons', function(editor) {

    return {
      title: 'Insert an icon',
      minWidth: 400,
      minHeight: 200,
      contents: [{
        id: 'info',
        label: 'Select an icon',
        elements: [{
          type: 'vbox',
          width: '100%',
          height: 50,
          children: [{
            type: 'button',
            id: 'launchIframe',
            label: 'Browse icons...',
            title: 'Launch the icon browser',
            onClick: function() {
              var finder = editor.openDialog('icon_frame');
              finder.disableButton('ok');

              finder.on("setIcon", function(result) {
                this.hide();

                var icon = null;
                var current = CKEDITOR.dialog.getCurrent();
                var preview = current.parts.dialog.find('.icon-preview');
                preview.$[0].innerHTML = '';

                if (result.data.value.match(/^fa-/)) {
                  icon = result.data.value;
                  current.setValueOf('info', 'icon_id', icon);
                  preview.$[0].innerHTML = '<i class="fa ' + icon + '" style="font-family: FontAwesome; font-size: 60px;"></i>';
                } else {
                  icon = JSON.parse(result.data.value);
                  if (icon.id) {
                    current.setValueOf('info', 'icon_id', icon.id);
                    preview.$[0].innerHTML = icon.svg;
                  }
                }
              });
            }
          }]
        }, {
          type: 'vbox',
          width: '100%',
          children: [{
            type: 'html',
            html: '<div class="icon-preview" style="width: 30%; height: 100px;"></div>'
          }, ]
        }, {
          type: 'vbox',
          width: '100%',
          style: 'display: none;',
          children: [{
            id: 'icon_id',
            type: 'text',
            label: 'Icon id',

            setup: function(widget) {
              var current = CKEDITOR.dialog.getCurrent();
              this.setValue(widget.data.iconid);
              var preview = current.parts.dialog.find('.icon-preview');
              if (widget.data.iconid !== undefined) {
                widget.getIcon.apply(widget, [widget.editor, widget.data, preview.$[0]]);
              } else {
                preview.$[0].innerHTML = '';
              }
            },
            commit: function(widget) {
              widget.setData('iconid', this.getValue());
            },
            validate: CKEDITOR.dialog.validate.notEmpty('Please select an icon first.')
          }]
        }]
      }]
    };
  });
}(jQuery));

