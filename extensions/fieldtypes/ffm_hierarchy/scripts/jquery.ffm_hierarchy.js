(function($){


// initialize new cells
$.fn.ffMatrix.onDisplayCell.ffm_hierarchy = function(cell, FFM) {
	if (!FFM.hierarchy) {
		FFM.hierarchy = new Array();
	}

	var obj = {
		cell:     cell,
		$cell:    $(cell),
		FFM:      FFM
	};

	// input
	obj.$input = $('input', obj.$cell);

	// buttons
	obj.$buttons = $('a', obj.$cell);
	obj.$indent = obj.$buttons.filter('.indent')
		.click(function(){
			if (!$(this).hasClass('disabled')) {
				nudgeIndent(obj, 1);
			}
		});
	obj.$outdent = obj.$buttons.filter('.outdent')
		.click(function(){
			if (!$(this).hasClass('disabled')) {
				nudgeIndent(obj, -1);
			}
		});

	obj.$row = obj.$cell.parent();
	obj.$indentedCell = $('.td:not(.ffm_hierarchy):first', obj.$row);
	setIndex(obj);

	// register the obj
	obj.FFM.hierarchy[obj.index] = obj;

	if (obj.index == 0) {
		obj.indent = 0;
		obj.$buttons.addClass('disabled');
	}
	else {
		var indent = parseInt(obj.$input.val());
		if (indent == -1) indent = obj.FFM.hierarchy[obj.index-1].indent;
		setIndent(obj, indent);
		if (obj.indent == 0) {
			obj.$outdent.addClass('disabled');
		}
	}
};


// handle row sorting
$.fn.ffMatrix.onSortRow.ffm_hierarchy = function(cell, FFM) {
	// update the index
	var obj = findObj(cell, FFM);
	FFM.hierarchy.splice(obj.index, 1);
	setIndex(obj);
	FFM.hierarchy.splice(obj.index, 0, obj);

	// cleanup
	cleanupHierarchy(FFM);
};


// handle row deletes
$.fn.ffMatrix.onDeleteRow.ffm_hierarchy = function(cell, FFM) {
	//remove obj
	var obj = findObj(cell, FFM);
	FFM.hierarchy.splice(obj.index, 1);
	delete obj;

	// cleanup
	cleanupHierarchy(FFM);
};


function findObj(cell, FFM) {
	var obj;
	$.each(FFM.hierarchy, function(){
		if (this.cell == cell) {
			obj = this;
			return false;
		}
	});
	return obj;
}

function cleanupHierarchy(FFM) {
	$.each(FFM.hierarchy, function(index){
		this.index = index;

		if (this.index == 0) {
			setIndent(this, 0);
			this.$buttons.addClass('disabled');
		} else {
			var maxIndent = FFM.hierarchy[this.index-1].indent + 1;
			var indent = (this.indent <= maxIndent) ? this.indent : maxIndent;
			setIndent(this, indent);
		}
	});
}

function setIndex(obj) {
	obj.index = obj.$row.attr('rowIndex') - 1;
}

function nudgeIndent(obj, diff) {
	var children = getChildren(obj);
	setIndent(obj, obj.indent+diff);
	$.each(children, function() {
		nudgeIndent(this, diff);
	});
}

var indentSize = 40;
function setIndent(obj, indent) {
	obj.indent = indent;
	obj.$input.val(obj.indent);

	if (!obj.indent) {
		obj.$indentedCell.css({
			backgroundImage: 'none',
			paddingLeft:     '10px'
		});

		obj.$outdent.addClass('disabled');
		obj.$indent.removeClass('disabled');
	}
	else {
		obj.$indentedCell.css({
			backgroundImage:    'url('+PATH_CP_IMG+'cat_marker.gif)',
			backgroundRepeat:   'no-repeat',
			backgroundPosition: (20 + (obj.indent-1) * indentSize)+'px 11px',
			paddingLeft:        (10 + obj.indent * indentSize)+'px'
		});

		obj.$outdent.removeClass('disabled');
		if (obj.indent <= obj.FFM.hierarchy[obj.index-1].indent) {
			obj.$indent.removeClass('disabled');
		} else {
			obj.$indent.addClass('disabled');
		}
	}
}

function getParent(obj) {
	if (obj.indent > 0) {
		for (var i=obj.index-1; i>=0; i--) {
			var row = obj.FFM.hierarchy[i];
			if (row.indent == obj.indent-1) {
				return row;
			}
		}
	}
	return false;
}

function getChildren(obj) {
	var children = new Array();
	for (var i=obj.index+1; i<obj.FFM.hierarchy.length; i++) {
		var row = obj.FFM.hierarchy[i];
		if (row.indent == obj.indent+1) {
			children.push(row)
		} else if (row.indent <= obj.indent) {
			break;
		}
	}
	return children;
}


})(jQuery);