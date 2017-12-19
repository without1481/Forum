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

class vB5_Frontend_Application extends vB5_ApplicationAbstract
{
	public static function init($configFile)
	{
		parent::init($configFile);

		self::$instance = new vB5_Frontend_Application();
		self::$instance->router = new vB5_Frontend_Routing();
		self::$instance->router->setRoutes();
		$styleid = vB5_Template_Stylevar::instance()->getPreferredStyleId();

		if ($styleid)
		{
			vB::getCurrentSession()->set('styleid', $styleid);
		}

		self::$instance->convertInputArrayCharset();
		self::setHeaders();

		return self::$instance;
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 14:05, Tue Nov 21st 2017
|| # CVS: $RCSfile$ - $Revision: 90906 $
|| #######################################################################
\*=========================================================================*/
