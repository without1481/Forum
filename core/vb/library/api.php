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
 * @package vBLibrary
 *
 * This class depends on the following
 *
 *
 * It does not and should not depend on the permission objects.  All permissions
 * should be handled outside of the class and passed to to the class in the form
 * of override flags.
 *
 */

/**
 * vB_Library_API
 *
 * @package vBLibrary
 * @access public
 */
class vB_Library_API extends vB_Library
{
	/**
	 * Generates an api key.
	 * @return string
	 */
	public function generateAPIKey()
	{
		$random = new vB_Utility_Random();
		$newapikey = $random->alphanumeric(8);

		$assertor = vB::getDbAssertor();
		$assertor->update('setting',
			array(
				'value' => $newapikey,
			),
			array(
				'varname' => 'apikey',
			)
		);
		$assertor->update('setting',
			array(
				'value' => '1',
			),
			array(
				'varname' => 'enableapi',
			)
		);
		vB::getDatastore()->build_options();
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 14:05, Tue Nov 21st 2017
|| # CVS: $RCSfile$ - $Revision: 86469 $
|| #######################################################################
\*=========================================================================*/
