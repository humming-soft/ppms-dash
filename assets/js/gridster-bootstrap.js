var bsgridster = function(gridsterBoxes, unitHeight, customBoxClass) {
	'use strict';

	if(typeof _ === 'undefined') {
		throw 'Shucks! We need underscore.js as a dependency. For now at least...'
	}

	if (!unitHeight) {
		unitHeight = 50;
	}

	document.querySelector('style').textContent +=
    '@media (min-width: 992px) { #portlet_container .row {height:'+unitHeight.toString()+'px;}}';
	
	gridsterBoxes = _.sortBy(gridsterBoxes, function(box) {
		return box.col;
	});

	function makeBox(width, height, offset, id) {
		var boxElem = document.createElement('div');
		boxElem.className = customBoxClass;

		boxElem.className += ' col-md-' + width.toString();

		if(offset>0) {
			boxElem.className += ' col-md-offset-' + offset.toString();
		}

		boxElem.style.height = (height * unitHeight).toString() + 'px';

		boxElem.id = id;

		return boxElem;
	}
	
	function makePortlet(width, height, offset, id) {
		var boxElem = document.createElement('div');
		var portletElem = document.createElement('div');
		boxElem.className = customBoxClass;

		boxElem.className += ' col-md-' + width.toString();
		if (width >= 6) { boxElem.className += " col-sm-12 col-xs-12" } /* Too simple logic */
		if(offset>0) {
			boxElem.className += ' col-md-offset-' + offset.toString();
		}

		boxElem.style.minHeight = (height * unitHeight).toString() + 'px';
		//boxElem.style.overflowY = 'hidden';
		//boxElem.style.overflowX = 'hidden';
		boxElem.style.backgroundColor = '#cccccc';
		boxElem.style.border = '1px solid white';
		portletElem.className = "block block-drop-shadow";
		portletElem.id = "portlet_"+id;
		boxElem.appendChild(portletElem);
		return boxElem;
	}

	function makeRow() {
		var rowElem = document.createElement('div');
		rowElem.className = 'row col-md-12';
		return rowElem;
	}

	function html() {
		var containerElem = document.createElement('div');
		var rows = [];

		var groupedBoxes = _.groupBy(gridsterBoxes, function(box) {
			return box.row;
		});

		var maxRow = (_.max(gridsterBoxes, function(box) {
			return box.row;
		})).row;

		_.times(maxRow, function(n) {
			rows.push({row: n, elem: makeRow()});
		});

		_.each(groupedBoxes, function (boxes, row) {
			var rowElem = rows[row-1].elem;

			_.each(boxes, function (box, index, boxesInRow) {
				var immediateLeftBox = boxesInRow[index-1];
				var offset = 0;

				if(immediateLeftBox) {
					offset = box.col - (immediateLeftBox.col+immediateLeftBox.size_x);
				} else {
					offset = box.col-1;
				}

				var boxElem = makePortlet(box.size_x, box.size_y, offset, box.id);
				rowElem.appendChild(boxElem);
			});
		});

		_.each(rows, function(row) {
			containerElem.appendChild(row.elem);
		});

		return containerElem;
	}

	return {
		getHtml: html
	};
};