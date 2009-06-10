(function($){


$.fn.ffMatrix.onDisplayCell.ffm_hierarchy = function($cell) {
	var $pos = $('div.pos', $cell);
	var $buttons = $('a', $cell);
	var $indent = $('a.indent', $cell);
	var $outdent = $('a.outdent', $cell);


	$cell.hover(
		function() {
			$buttons.fadeIn(100);
		},
		function() {
			$buttons.fadeOut(100);
		}
	);

	$indent.click(
		function() {
			$pos.css('marginLeft', parseInt($pos.css('margin-left'))+30);
		}
	);
	$outdent.click(
		function() {
			$pos.css('marginLeft', parseInt($pos.css('margin-left'))-30);
		}
	);
}


})(jQuery);