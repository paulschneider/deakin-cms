window.TREE_FILE_ATTACHMENT = null;
window.TREE_FILE_DATATABLE = null;




(function($) {

  $(function() {


    var clickTreeGo = function(id) {
      window.TREE_FILE_ATTACHMENT = id;
      window.TREE_FILE_DATATABLE.ajax.reload(bindTableRefresh);
      window.TREE_FILE_DATATABLE.on('draw.dt', bindTableRefresh);
    };


    var bindTableRefresh = function() {

      $('.attachment-attach').unbind('click').click(function(e) {
        e.preventDefault();
        var $fid = $(this).data('file-id'),
          $ftype = $(this).data('file-type');

        if (typeof window.parent.CKEDITOR !== 'undefined') {
          var dialog = window.parent.CKEDITOR.dialog.getCurrent();
          dialog.fireOnce("setFile", {
            fid: $fid,
            ftype: $ftype
          });
        }
      });

      $('.attachment-selection').unbind('change').change(function() {
        $('.attachment-table tfoot').removeClass('sr-only');
      });

      $('#file-listing').removeClass('files-loading');

      $('.attachment-table tfoot').addClass('sr-only');

      // Show popover for images with larger version.
      // Use: data-toggle="thumbnail"
      $('img[data-toggle="thumbnail"]').popover({
        html: true,
        trigger: 'hover',
        container: 'body',
        content: function() {
          return '<img src="' + $(this).data('thumbnail') + '" style="max-width: 600px; max-height: 400px;"/>';
        }
      }).on("show.bs.popover", function() {
        $(this).data("bs.popover").tip().css({
          maxWidth: "600px"
        });
      });
    };

    var reloadTree = function() {
      if ($('#attachment-jstree').length) {
        $('#attachment-jstree .jstree-clicked').trigger('click');
      } else {
        setTimeout(function() {
          window.location.reload(true);
        }, 1000);
      }
    };

    var bindAttachmentForm = function() {
      var $form = $('.attachment-modify-selected');

      $form.unbind('submit').submit(function(e) {
        e.preventDefault();
      });

      $('select[name="term_id"]').unbind('change').change(function() {
        var selected = $form.serialize();
        var action = $form.attr('action');
        var method = $form.attr('method');

        selected += '&move=true';

        $.ajax({
          method: method,
          url: action,
          data: selected,
          jsonp: false,
          cache: false,
          dataType: 'json'
        }).done(function(data) {
          toastr.success(data.message, null, {
            positionClass: "toast-top-right"
          });
          reloadTree();
        }).error(function() {
          toastr.error('Files could not be changed.', null, {
            positionClass: "toast-top-right"
          });
          reloadTree();
        });
      });

      var btnDeleteTimer = null;

      $('button.delete').unbind('click').click(function() {

        // Confirm for multiple selections
        if (!$(this).hasClass('confirmed')) {
          var $self = $(this);

          $self.removeClass('btn-outline');
          $self.data('original-text', $self.text());
          $self.text('Really?');
          $self.addClass('confirmed');

          var $x = $('<a href="#" class="cancel-delete btn btn-default"></a>');
          $x.html('<i class="fa fa-close"></i>');

          $self.after($x);

          var cancelDelete = function() {
            $self.removeClass('confirmed').addClass('btn-outline').text($self.data('original-text'));
            $self[0].blur();
            $('.cancel-delete').remove();
          };

          clearTimeout(btnDeleteTimer);

          btnDeleteTimer = setTimeout(function() {
            cancelDelete();
          }, 5000);

          $x.click(function(e) {
            e.preventDefault();
            cancelDelete();
          });
          return;
        }

        var selected = $form.serialize();
        var action = $form.attr('action');
        var method = $form.attr('method');

        // Set the delete flag.
        selected += '&delete=true';

        $.ajax({
          method: method,
          url: action,
          data: selected,
          jsonp: false,
          cache: false,
          dataType: 'json'
        }).done(function(data) {
          toastr.success(data.message, null, {
            positionClass: "toast-top-right"
          });
          reloadTree();
        }).error(function() {
          toastr.error('Files could not be changed.', null, {
            positionClass: "toast-top-right"
          });
          reloadTree();
        });
      });
    };




    /**
     * ATTACHMENT TREE
     */
    if ($('#attachment-jstree').length) {

      $('#file-listing').addClass('files-loading');

      var $tree = $('#attachment-jstree');

      $.getScript('/assets/vendor/iconinc-admin-theme/dist/js/plugins/jsTree/jstree.min.js', function() {
        $tree.jstree({
          'core': {
            'check_callback': true,
            'data': {
              'url': function(node) {
                return $tree.data('tree-src') + '?_' + $.now();
              },
              'data': function(node) {
                return {
                  'id': node.id
                };
              }
            }
          },
          'plugins': ['types', 'wholerow', 'state'],
          'types': {
            'default': {
              'icon': 'fa fa-folder'
            }
          }
        }).on('changed.jstree', function(e, data) {
          $('#file-listing').addClass('files-loading');
          var selected = data.selected;
          if (selected.length) {
            var id = selected.toString().match(/tree_([\d]+)/)[1];
            clickTreeGo(id);
          }
        });
      });

      bindAttachmentForm();
    }


  }); // End jQuery Ready

}(jQuery));
