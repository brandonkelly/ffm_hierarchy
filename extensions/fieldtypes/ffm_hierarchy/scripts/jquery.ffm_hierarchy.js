(function($){


$.fn.ffMatrix.onDisplayCell.ffm_hierarchy = function($cell, obj) {
	var $indentBtn = $('a.indent', $cell);
	var $outdentBtn = $('a.outdent', $cell);
	var $row = $cell.parent();
	var $indentedCell = $row.find('.td:not(.ffm_hierarchy):first');
	var indent = 0;
	var indentSize = 30;

	var updateIndent = function(diff) {
		if (indent + diff < 0) return;

		if (indent == 0) {
			$indentedCell.css({
				backgroundImage: 'url('+PATH_CP_IMG+'cat_marker.gif)',
				backgroundRepeat: 'no-repeat'
			});
		}
		indent += diff;
		if (indent == 0) {
			$indentedCell.css('backgroundImage', 'none');
		}
		else {
			$indentedCell.css({
				backgroundPosition: (15 + (indent-1) * indentSize)+'px 11px',
				paddingLeft: 10 + indent * indentSize
			});
		}
	}

	$indentBtn.click(
		function() {
			updateIndent(1);
		}
	);
	$outdentBtn.click(
		function() {
			updateIndent(-1);
		}
	);
}


})(jQuery);