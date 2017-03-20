CKEDITOR.dialog.add('attachment', function(editor) {

  var imageDocumentSwitch = function() {
    var current = CKEDITOR.dialog.getCurrent();
    var file_type = current.getValueOf('info', 'file_type');

    toggle('align', false);
    toggle('caption', false);
    toggle('filemode', false);
    toggle('displaysize', false);

    if (file_type.match(/^image/i)) {
      toggle('align', true);
      toggle('caption', true);
      toggle('filemode', true);
      toggle('displaysize', true);
    }
  };

  var imageEmbedSwitch = function() {
    var current = CKEDITOR.dialog.getCurrent();
    var filemode = current.getValueOf('info', 'filemode');
    var file_type = current.getValueOf('info', 'file_type');

    toggle('caption', false);
    toggle('align', false);

    if (file_type.match(/^image/i)) {
      if (filemode == 'link') {
        toggle('caption', false);
        toggle('align', false);
        toggle('displaysize', false);
      } else {
        toggle('caption', true);
        toggle('align', true);
        toggle('displaysize', true);
      }
    }
  };

  var toggle = function(name, visible) {
    if (visible) {
      visible = 'block';
    } else {
      visible = 'none';
    }

    var current = CKEDITOR.dialog.getCurrent();
    document.getElementById(current.getContentElement('info', name).domId).style.display = visible;
  };

  CKEDITOR.dialog.addIframe('attachment_iframe', 'Browse files', editor.config.attachment_url, 1300, 600, function() {
    var log = CKEDITOR.dialog.getCurrent();
    document.getElementById(log.getButton('ok').domId).style.display = 'none';
  });

  var sizes = [
    [editor.lang.common.notSet, '']
  ];

  if (CKEDITOR.ckImageSize !== undefined) {
    var configSizes = [];
    for (index = 0; index < CKEDITOR.ckImageSize.length; ++index) {
      configSizes.push([CKEDITOR.ckImageSize[index], CKEDITOR.ckImageSize[index]]);
    }

    sizes = sizes.concat(configSizes);
  }

  return {
    title: 'Upload or select an attachment',
    minWidth: 600,
    minHeight: 250,
    resizable: CKEDITOR.DIALOG_RESIZE_NONE,
    contents: [{
      id: 'info',
      elements: [{
        type: 'hbox',
        widths: ['50%', '50%'],
        height: 250,
        children: [{
          type: 'vbox',
          width: '100%',
          height: 250,
          children: [{
            type: 'button',
            id: 'launchIframe',
            label: 'Browse files...',
            title: 'Launch the file browser',
            //className: 'cke_dialog_ui_button_ok',
            onClick: function() {

              var finder = editor.openDialog('attachment_iframe');
              finder.disableButton('ok');

              finder.on("setFile", function(result) {
                this.hide();

                if (result.data.fid) {
                  var current = CKEDITOR.dialog.getCurrent();

                  current.setValueOf('info', 'file_id', result.data.fid);
                  current.setValueOf('info', 'file_type', result.data.ftype);

                  var preview = document.getElementById('attachment-preview');
                  preview.src = editor.config.attachment_preview_url + '/' + result.data.fid;

                  imageDocumentSwitch();
                  imageEmbedSwitch();
                }
              });
            }
          }, {
            id: 'file_id',
            type: 'text',
            label: 'File ID',
            style: 'display: none',
            setup: function(widget) {
              this.setValue(widget.data.fileid);

              if (widget.data.fileid) {
                var preview = document.getElementById('attachment-preview');
                preview.src = editor.config.attachment_preview_url + '/' + widget.data.fileid;
              }
            },
            commit: function(widget) {
              widget.setData('fileid', this.getValue());
            },
            validate: CKEDITOR.dialog.validate.notEmpty("Please select a file first.")
          }, {
            id: 'file_type',
            type: 'text',
            label: 'File Type',
            style: 'display: none',
            setup: function(widget) {
              this.setValue(widget.data.filetype);
            },
            commit: function(widget) {
              widget.setData('filetype', this.getValue());
            }

          }, {
            id: 'filemode',
            type: 'select',
            label: 'Format',
            items: [
              [editor.lang.common.notSet, ''],
              ['Embed as an image', 'image'],
              ['Embed as a link', 'link']
            ],
            'default': 'Embed as an image',
            setup: function(widget) {
              this.setValue(widget.data.filemode);
            },
            commit: function(widget) {
              widget.setData('filemode', this.getValue());
            },
            onChange: function() {
              imageEmbedSwitch();
            }
          }, {
            id: 'align',
            type: 'select',
            label: 'Float',
            items: [
              [editor.lang.common.notSet, ''],
              [editor.lang.common.alignLeft, 'left'],
              [editor.lang.common.alignRight, 'right']
            ],
            setup: function(widget) {
              this.setValue(widget.data.align);
            },
            commit: function(widget) {
              widget.setData('align', this.getValue());
            }
          }, {
            id: 'displaysize',
            type: 'select',
            label: 'Display size',
            items: sizes,
            setup: function(widget) {
              this.setValue(widget.data.displaysize);
            },
            commit: function(widget) {
              widget.setData('displaysize', this.getValue());
            }
          }, {
            id: 'caption',
            type: 'checkbox',
            label: 'Show an image caption',
            setup: function(widget) {
              this.setValue(widget.data.caption);
            },
            commit: function(widget) {
              widget.setData('caption', this.getValue());
            }
          }]
        }, {
          type: 'html',
          html: '<iframe id="attachment-preview" src="' + editor.config.attachment_preview_url + '" border="0" frameborder="0" style="width: 100%; height: 250px;"></iframe>'
        }, ]
      }]
    }],

    onShow: function() {
      // Wait for init.
      setTimeout(function() {
        imageDocumentSwitch();
        imageEmbedSwitch();
      }, 150);

    },

    onHide: function() {
      var preview = document.getElementById('attachment-preview');
      preview.src = editor.config.attachment_preview_url;
    }
  };
});
