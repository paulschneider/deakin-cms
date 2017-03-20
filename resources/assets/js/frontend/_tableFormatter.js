(function($) {
  CMS.tableFormatter = {
    init: function() {
      var mainWrapperWidth = $('.main-wrapper').width();
      var $tables = $('.one-column-wysiwyg table');

      $tables.each(function() {
        var $table = $(this);
        if ($table.length && ($table.width() >= mainWrapperWidth)) {
          $table.wrap('<div class="table-overflow"></div>');
        }
      });

      var allCells = $("td, th");

      allCells
        .on("mouseover", function() {
          var el = $(this),
            pos = el.index();
          allCells.filter(":nth-child(" + (pos + 1) + ")").addClass("hover");
        })
        .on("mouseout", function() {
          allCells.removeClass("hover");
        });
    }
  };
}(jQuery));

