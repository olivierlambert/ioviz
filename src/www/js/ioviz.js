/**
 * This file is a part of IoViz.
 *
 * IoViz is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * IoViz is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with IoViz. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Julien Fontanet <julien.fontanet@isonoe.net>
 * @license http://www.gnu.org/licenses/gpl-3.0-standalone.html GPLv3
 *
 * @package IoViz
 */

function create_graph(benchmark, title, ticks, data)
{
	'use strict';

	var container = $('<div class="flotr"></div>');
	container.appendTo($('#ioviz'));

	var tmp = [];
	var min;
	var max;
	for (var run in data)
	{
		if ((min === undefined)
			|| (min > +ticks[run][0]))
		{
			min = ticks[run][0];
		}
		if ((max === undefined)
			|| (max < +ticks[run][ticks[run].length - 1]))
		{
			max = ticks[run][ticks[run].length - 1];
		}

		var tmp2 = [];
		for (var i in data[run])
		{
			tmp2.push([ticks[run][i], data[run][i] / 1024]);
		}

		tmp.push({
			'label': run,
			'data':  tmp2,
		});
	}
	data = tmp;

	Flotr.draw(container[0], data, {
		'xaxis': {
			'scaling': 'logarithmic',
			'base': 2,
			'min': min,
			'max': max,
		},
		'grid': {
			'verticalLines': true,
			'backgroundColor': {
				'colors': [[0, '#fff'],[1, '#eee']],
				'start': 'top',
				'end': 'bottom',
			}
		},
		'legend': {position: 'ne'},
		'spreadsheet': {show: true},
		'title': title,
		'subtitle': benchmark,
	});
}

$(function() {
	$('a[data-confirm]').click(function(e) {
		if (!window.confirm($(this).attr('data-confirm')))
		{
			e.preventDefault();
		}
	});
});
