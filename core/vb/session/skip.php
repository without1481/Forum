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
 * This class replaces the use of SKIP_SESSIONCREATE.
 * All it does is overriding the methods that are not supposed to run when the flag is on
 */
class vB_Session_Skip extends vB_Session
{
	public function __construct(&$dBAssertor, &$datastore, &$config, $styleid = 0, $languageid = 0)
	{
		parent::__construct($dBAssertor, $datastore, $config, '', array(), $styleid, $languageid);
	}

	protected function loadExistingSession($sessionhash, $restoreSessionInfo)
	{
		return false;
	}

	public function save()
	{
		return;
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 14:05, Tue Nov 21st 2017
|| # CVS: $RCSfile$ - $Revision: 83435 $
|| #######################################################################
\*=========================================================================*/
