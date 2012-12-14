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

/**
 *
 */
final class Parser_IOzone extends Base implements Parser
{
	/**
	 *
	 */
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * @param string $data
	 *
	 * @return array
	 */
	function parse($data)
	{
		$data = preg_split('/\r\n|\n/', $data);

		$result = array(
			//'KB'            => array(),
			'Reclen'        => array(),
			'Write'         => array(),
			'Rewrite'       => array(),
			'Read'          => array(),
			'ReRead'        => array(),
			'Randomread'    => array(),
			'Randomwrite'   => array(),
			'Bkwdread'      => array(),
			'Recordrewrite' => array(),
			'Strideread'    => array(),
			'Fwrite'        => array(),
			'Frewrite'      => array(),
			'Fread'         => array(),
			'Freread'       => array(),
		);
		foreach ($data as $line)
		{
			// We are only interrested in lines containing 15 numbers.
			if (!preg_match('/^\s*\d+(?:\s+\d+){14}\s*$/', $line))
			{
				continue;
			}

			preg_match_all('/\d+/', $line, $matches);
			$entries = $matches[0];

			$key = array_shift($entries);
			foreach($result as &$field)
			{
				$field[$key][] = (int) array_shift($entries);
			}
		}

		return $result;
	}
}
