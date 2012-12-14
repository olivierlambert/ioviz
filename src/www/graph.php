<?php
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
 * @author Julien Fontanet <julien.fontanet@vates.fr>
 * @license http://www.gnu.org/licenses/gpl-3.0-standalone.html GPLv3
 *
 * @package IoViz
 */

$ioviz = require(dirname(__FILE__).'/../bootstrap.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id']))
{
	header('Content-Type: text/plain', 400);
	die('missing graph identifier');
}

$result = $ioviz->getResult((int)$_GET['id']);
$data   = $result->getData();
$ticks  = $data['Reclen']; unset($data['Reclen']);

$ioviz->getTemplate('/pages/graph.html')->render(array(
	'title'  => $result->getTitle(),
	'ticks'  => $ticks,
	'graphs' => $data,
));
