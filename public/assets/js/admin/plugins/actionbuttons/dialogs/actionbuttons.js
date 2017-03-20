(function($, undefined) {
  CKEDITOR.dialog.add('actionbuttons', function(editor) {

    return {
      title: 'Insert an action button',
      minWidth: 600,
      minHeight: 200,
      contents: [
        {
          id: 'info',
          label: 'Action Buttons',
          elements: [
            {
              type: 'vbox',
              width: '100%',
              height: '50px',
              children: [
                {
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
                },
                {
                  type: 'hbox',
                  widths: ['20%', '80%'],
                  children: [
                    {
                      id: 'protocal',
                      type: 'select',
                      label: 'Protocol',
                      width: '100%',
                      style: 'width: 100%;',
                      items: [
                          [ editor.lang.common.notSet, '' ],
                          [ 'http://', 'http://' ],
                          [ 'https://', 'https://' ],
                          [ 'ftp://', 'ftp://' ],
                          [ 'news://', 'news://' ],
                          [ '<other>', '' ],
                      ],
                      setup: function(widget) {
                        this.setValue(widget.data.protocol);
                      },
                      commit: function(widget) {
                        widget.setData('protocol', this.getValue());
                      }
                    },
                    {
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
                    }
                  ]
                }
              ]
            }
          ]
        }
      ]
    };
  });
}(jQuery));