(function($, undefined) {
  CKEDITOR.dialog.add('buttons', function(editor) {

    return {
      title: 'Insert an button',
      minWidth: 600,
      minHeight: 200,
      contents: [{
        id: 'info',
        label: 'Select an icon',
        elements: [{
          type: 'vbox',
          width: '100%',
          height: '50px',
          children: [{
            type: 'text',
            id: 'text',
            label: 'Text',
            validate: CKEDITOR.dialog.validate.notEmpty('Please enter the button text.'),
            setup: function(widget) {
              this.setValue(widget.data.text);
            },
            commit: function(widget) {
              widget.setData('text', this.getValue());
            }
          }, {
            type: 'hbox',
            widths: ['20%', '20%', '60%'],
            children: [{
              id: 'protocal',
              type: 'select',
              label: 'Protocol',
              width: '100%',
              style: 'width: 100%;',
              items: [
                [editor.lang.common.notSet, ''],
                ['http://', 'http://'],
                ['https://', 'https://'],
                ['ftp://', 'ftp://'],
                ['news://', 'news://'],
                ['<other>', ''],
              ],
              setup: function(widget) {
                this.setValue(widget.data.protocol);
              },
              commit: function(widget) {
                widget.setData('protocol', this.getValue());
              }
            }, {
            id: 'target',
            type: 'select',
            label: 'Target',
            items: [
              [editor.lang.common.notSet, '_self'],
              ['New window', '_blank']
            ],
            'default': '_self',
            setup: function(widget) {
              this.setValue(widget.data.target);
            },
            commit: function(widget) {
              widget.setData('target', this.getValue());
            }
          }, {
              type: 'text',
              id: 'url',
              label: 'Url',
              validate: CKEDITOR.dialog.validate.notEmpty('Please enter the button url.'),
              setup: function(widget) {
                this.setValue(widget.data.url);
              },
              commit: function(widget) {
                widget.setData('url', this.getValue());
              }
            }]
          }]
        }, {
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
                  preview.$[0].innerHTML = '<i class="fa ' + icon + '"></i>';
                } else {
                  icon = JSON.parse(result.data.value);
                  if (icon.id) {
                    current.setValueOf('info', 'icon_id', icon.id);
                    preview.$[0].innerHTML = icon.svg;
                  }
                }
              });
            }
          },{
            type: 'button',
            id: 'clearButton',
            label: 'Clear icon',
            title: 'Clear the selected icon',
            onClick: function() {
              var current = CKEDITOR.dialog.getCurrent();
              var preview = current.parts.dialog.find('.icon-preview');
              preview.$[0].innerHTML = '';
              current.setValueOf('info', 'icon_id', '');
            }
          }]
        }, {
          type: 'vbox',
          width: '100%',
          height: 150,
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
              preview.$[0].innerHTML = '';
              if (widget.data.iconid !== undefined) {
                widget.getIcon.apply(widget, [widget.editor, widget.data, preview.$[0]]);
              }
            },
            commit: function(widget) {
              widget.setData('iconid', this.getValue());
            }
            // validate: CKEDITOR.dialog.validate.notEmpty('Please select an icon first.')
          }]
        }]
      }]
    };
  });
}(jQuery));

