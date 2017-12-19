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
 * AdminStopMessage Exception
 * Exception thrown when the Admin should not continue.
 * Created to be able to interface with the existing print_stop_message function
 * but to allow other behavior if desired.
 *
 * @package vBulletin
 * @author Kevin Sours, vBulletin Development Team
 * @version $Revision: 83435 $
 * @since $Date: 2014-12-10 10:32:27 -0800 (Wed, 10 Dec 2014) $
 * @copyright vBulletin Solutions Inc.
 */
class vB_Exception_AdminStopMessage extends vB_Exception
{
	public function __construct($params, $code = false, $file = false, $line = false)
	{
		$this->params = $params;
		if (!is_array($this->params))
		{
			$this->params = array($this->params);
		}

		//I can't override getMessage because its final. I don't want to fetch the
		//message prematurely because we might not use it directly.  I don't think vBPhrase
		//accepts parameters as an array and even so the exception may do a string cast
		//on the message which won't defer the lookup anyway. Given that this exception is
		//intended to be caught and dealt with it doesn't bear the level of thought
		//required to fix it.
		parent::__construct("internal error", $code, $file, $line);
	}

	public function getParams()
	{
		return $this->params;
	}

	protected $params = array();
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 14:05, Tue Nov 21st 2017
|| # CVS: $RCSfile$ - $Revision: 83435 $
|| #######################################################################
\*=========================================================================*/
