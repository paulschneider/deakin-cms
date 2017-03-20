(function($) {

  CKEDITOR.dialog.add('fontawesome', function(editor) {

    return {
      title: 'Insert a FontaAwesome Icon',
      minWidth: 600,
      minHeight: 200,
      contents: [
        {
          id: 'info',
          label: 'Select an icon',
          elements: [
            {
              type: 'vbox',
              width: '100%',
              height: 50,
              children: [
                {
                  type: 'button',
                  id: 'launchIframe',
                  label: 'Browse icons...',
                  title: 'Launch the FontAwesome browser',
                  onClick: function(){
                    var finder = editor.openDialog('fontawesome_frame');
                    finder.disableButton('ok');

                    finder.on("setIcon", function(result){
                      this.hide();
                      var icon = result.data.value;
                      var current = CKEDITOR.dialog.getCurrent();
                      var preview = current.parts.dialog.find('.icon-preview');
                      if (icon) {
                        current.setValueOf('info', 'icon', icon);
                        preview.$[0].innerHTML = '<i class="fa '+icon+'" style="font-family: FontAwesome; font-size: 60px;"></i>';
                      } else {
                        preview.$[0].innerHTML = '';
                      }
                    });
                  }
                }
              ]
            },
            {
              type: 'vbox',
              width: '100%',
              height: 150,
              children: [
                {
                  type: 'html',
                  html: '<div class="icon-preview" style="width: 30%; height: 100px;"></div>'
                },
              ]
            },
            {
              type: 'text',
              id: 'icon',
              label: 'Icon',
              style: 'display: none;',
              validate: CKEDITOR.dialog.validate.notEmpty('Please select a FontAwesome Icon'),
              setup: function(widget) {
                this.setValue(widget.data.icon);
              },
              commit: function(widget) {
                widget.setData('icon', this.getValue());
              }
            },
          ]
        }
      ]
    };
  });
}(jQuery));