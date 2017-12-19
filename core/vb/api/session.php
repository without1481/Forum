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
 * vB_Api_Session
 *
 * @package vBApi
 * @access public
 */
class vB_Api_Session extends vB_Api
{
	/**
	 * starts a new lightweight (no shutdown) guest session and returns the session object.
	 *
	 * @return 	array('success' => true);
	 */
	public function startGuestSession()
	{
		$session = vB_Session_Web::getSession(0, '');
		$languageid = vB::getDatastore()->getOption('languageid');
		$session->set('languageid', $languageid);

		vB::skipShutdown(true);
		vB::setCurrentSession($session);
		return array('success' => true);
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 14:05, Tue Nov 21st 2017
|| # CVS: $RCSfile$ - $Revision: 94258 $
|| #######################################################################
\*=========================================================================*/
