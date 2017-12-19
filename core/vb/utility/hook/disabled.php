<?php
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
 *	@package vBUtility
 */

/**
 *	Dummy class for "hookless mode" avoids having to check the
 *	config within the live hook class.  Should implement the
 *	same public interface as the live class, but do absolutely
 *	nothing.
 *
 *	@package vBUtility
 */
class vB_Utility_Hook_Disabled
{
	public function __construct() {}

	public function invoke($hook_name, $params) {}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 14:05, Tue Nov 21st 2017
|| # CVS: $RCSfile$ - $Revision: 88922 $
|| #######################################################################
\*=========================================================================*/
