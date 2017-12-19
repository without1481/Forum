<?php if (!defined('VB_ENTRY')) die('Access denied.');
/*========================================================================*\
|| ###################################################################### ||
|| # vBulletin 5.3.4 - Licence Number LF986A3A9C
|| # ------------------------------------------------------------------ # ||
|| # Copyright 2000-2017 vBulletin Solutions Inc. All Rights Reserved.  # ||
|| # This file may not be redistributed in whole or significant part.   # ||
|| # ----------------- VBULLETIN IS NOT FREE SOFTWARE ----------------- # ||
|| # http://www.vbulletin.com | http://www.vbulletin.com/license.html   # ||
|| ###################################################################### ||
\*========================================================================*/

/**
* Class to handle shutdown
*
* @package	vBulletin
* @version	$Revision: 95346 $
* @author	vBulletin Development Team
* @date		$Date: 2017-09-15 16:29:56 -0700 (Fri, 15 Sep 2017) $
*/
class vB_Shutdown
{
	use vB_Trait_NoSerialize;

	/**
	 * A reference to the singleton instance
	 *
	 * @var vB_Cache_Observer
	 */
	protected static $instance;

	/**
	 * An array of shutdown callbacks to call on shutdown
	 */
	protected $callbacks;

	protected $called = false;
	/**
	 * Constructor protected to enforce singleton use.
	 * @see instance()
	 */
	protected function __construct(){}

	/**
	 * Returns singleton instance of self.
	 *
	 * @return vB_Shutdown
	 */
	public static function instance()
	{
		if (!isset(self::$instance))
		{
			$class = __CLASS__;
			self::$instance = new $class();
		}

		return self::$instance;
	}

	/**
	* Add callback to be executed at shutdown
	*
	* @param array $callback					- Call back to call on shutdown
	*/
	public function add($callback)
	{
		if (!isset($this->callbacks) OR !is_array($this->callbacks))
		{
			$this->callbacks = array();
		}

		$this->callbacks[] = $callback;
	}

	// only called when an object is destroyed, so $this is appropriate
	public function shutdown()
	{
		if ($this->called)
		{
			return; // Already called once.
		}

		$session = vB::getCurrentSession();
		if (is_object($session))
		{
			$session->save();
		}

		if (!empty($this->callbacks))
		{
			foreach ($this->callbacks AS $callback)
			{
				call_user_func($callback);
			}

			unset($this->callbacks);
		}

		$this->setCalled();
	}

	public function __wakeup()
	{
		unset($this->callbacks);
	}

	public function setCalled()
	{
		$this->called = true;
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 14:05, Tue Nov 21st 2017
|| # CVS: $RCSfile$ - $Revision: 95346 $
|| #######################################################################
\*=========================================================================*/
