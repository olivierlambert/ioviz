<?php
/**
 * @author Julien Fontanet <julien.fontanet@vates.fr>
 */

/**
 *
 */
final class ErrorLogger
{
	/**
	 *
	 */
	function __construct(Zend_Log $logger)
	{
		$this->_logger = $logger;
	}

	/**
	 * Handles fatal errors on shutdown.
	 */
	function handleShutdown()
	{
		$e = error_get_Last();
		if ((($e['type'] === E_ERROR) || ($e['type'] === E_USER_ERROR))
		    && ($e !== $this->_last))
		{
			$this->log($e['type'], $e['message'], $e['file'], $e['line']);
		}
	}

	/**
	 *
	 */
	function log($no, $str, $file, $line)
	{
		static $map = array(
			E_NOTICE            => Zend_Log::NOTICE,
			E_USER_NOTICE       => Zend_Log::NOTICE,
			E_WARNING           => Zend_Log::WARN,
			E_CORE_WARNING      => Zend_Log::WARN,
			E_USER_WARNING      => Zend_Log::WARN,
			E_ERROR             => Zend_Log::ERR,
			E_USER_ERROR        => Zend_Log::ERR,
			E_CORE_ERROR        => Zend_Log::ERR,
			E_RECOVERABLE_ERROR => Zend_Log::ERR,
			E_STRICT            => Zend_Log::DEBUG,
		);

		// Used to prevents the last error from being logged twice.
		$this->_last = array(
			'type'    => $no,
			'message' => $str,
			'file'    => $file,
			'line'    => $line
		);

		$priority = isset($map[$no])
			? $map[$no]
			: Zend_Log::WARN
			;

		// Appends the location if necessary.
		if (!preg_match('/(?:at|in) [^ ]+:[0-9]+$/', $str))
		{
			$str .= " in $file:$line";
		}

		$this->_logger->log($str, $priority, array(
			'no'   => $no,
			'file' => $file,
			'line' => $line,
		));

		return false;
	}

	/**
	 *
	 */
	private $_last;

	/**
	 *
	 */
	private $_logger;
}
