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
 * Dependency injector.
 */
final class DI extends Base
{
	function __construct()
	{
		parent::__construct();
	}

	function get($id)
	{
		if (isset($this->_entries[$id])
		    || array_key_exists($id, $this->_entries))
		{
			return $this->_entries[$id];
		}

		$tmp = str_replace(array('_', '.'), array('', '_'), $id);

		if (method_exists($this, '_get_'.$tmp))
		{
			return $this->{'_get_'.$tmp}();
		}

		if (method_exists($this, '_init_'.$tmp))
		{
			$value = $this->{'_init_'.$tmp}();
			$this->set($id, $value);
			return $value;
		}

		throw new Exception('no such entry: '.$id);
	}

	function set($id, $value)
	{
		$this->_entries[$id] = $value;
	}

	private $_entries = array();

	////////////////////////////////////////

	private function _init_errorLogger()
	{
		return new ErrorLogger($this->get('logger'));
	}

	private function _init_ioviz()
	{
		return new IoViz($this);
	}

	private function _init_logger()
	{
		$config = $this->get('config');

		return Zend_Log::factory(
			$config->get('log')
		);
	}

	private function _init_manager()
	{
		$config = $this->get('config');

		$db = new PDO(
			$config->get('database.dsn'),
			$config->get('database.username', null),
			$config->get('database.password', null)
		);

		return new Gallic_Manager_Pdo($db);
	}

	private function _init_parser_iozone()
	{
		return new Parser_IOzone;
	}

	private function _init_template_manager()
	{
		return new Gallic_Template_Manager(
			$this->get('config')->get('root_dir').'/templates',
			0
		);
	}
}
