<?php if (!defined('VB_ENTRY')) die('Access denied.');
/*======================================================================*\
|| #################################################################### ||
|| # vBulletin 5.3.4 - Licence Number LF986A3A9C
|| # ---------------------------------------------------------------- # ||
|| # Copyright ?2000-2017 vBulletin Solutions Inc. All Rights Reserved. ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- VBULLETIN IS NOT FREE SOFTWARE ---------------- # ||
|| # http://www.vbulletin.com | http://www.vbulletin.com/license.html # ||
|| #################################################################### ||
\*======================================================================*/

/**
 * vB_Api_FCMessaging
 *
 * @package vBLibrary
 * @access public
 */
class vB_Api_FCMessaging extends vB_Api
{
	/**
	 *	DB Assertor object
	 */
	protected $assertor;

	/**
	 * Instance of vB_Library_Notification
	 */
	protected $library;


	protected function __construct()
	{
		parent::__construct();
		$this->assertor = vB::getDbAssertor();

		$this->library = vB_Library::instance('FCMessaging');
	}

	public function sendFCM($messageHashes)
	{
		if (!is_array($messageHashes))
		{
			$messageHashes = array($messageHashes);
		}

		$strHashes = array();
		foreach ($messageHashes AS $__hash)
		{
			// Let's just not bother with non-strings.
			if (is_string($__hash))
			{
				$strHashes[] = $__hash;
			}
		}

		return $this->library->handleOffloadedTask($strHashes);
	}
}

/*======================================================================*\
|| ####################################################################
|| # Downloaded: 14:05, Tue Nov 21st 2017
|| # SVN: $Revision$
|| ####################################################################
\*======================================================================*/
