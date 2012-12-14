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
 * Bootstraps and return the application singleton.
 *
 * @return IoViz
 */
function _bootstrap()
{
	static $ioviz;

	if (!isset($ioviz))
	{
		// Variables definition.
		$root_dir = defined('__DIR__')
			? __DIR__
			: dirname(__FILE__)
			;
		if (defined('APPLICATION_ENV'))
		{
			$app_env = APPLICATION_ENV;
		}
		elseif (($app_env = getenv('APPLICATION_ENV')) === false)
		{
			$app_env = 'development';
		}

		// Class autoloading.
		if (!class_exists('Gallic'))
		{
			require('Gallic.php');
		}
		spl_autoload_register(array(
			new Gallic_ClassLoader_Standard(
				array($root_dir.'/lib')
				+ explode(PATH_SEPARATOR, get_include_path())
			),
			'load'
		));

		// Reads configuration.
		$conffile = $root_dir.'/config.ini';
		$config   = new Zend_Config_Ini($conffile, $app_env);
		$config   = new Config($config->toArray());

		// Injects some variables.
		$config->set('root_dir', $root_dir);
		$config->set('application_env', $app_env);

		// Dependency injector.
		$di = new DI;
		$di->set('config', $config);

		// Logs all errors.
		$error_logger = $di->get('error_logger');
		set_error_handler(array($error_logger, 'log'));
		register_shutdown_function(array($error_logger, 'handleShutdown'));

		// Finally, creates the inventory.
		$ioviz = $di->get('ioviz');
	}

	return $ioviz;
}

return _bootstrap();
