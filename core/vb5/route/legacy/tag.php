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

class vB5_Route_Legacy_Tag extends vB5_Route_Legacy
{
	protected $prefix = 'tags.php';
	
	// translate param, if fail just go to search page
	protected function getNewRouteInfo()
	{
		$param = & $this->queryParameters;
		$tag = array();
		if (!empty($param['tag']))
		{
			$tag['searchJSON'] = "{\"tag\":[\"$param[tag]\"]}";
		}
		$param = $tag;
		return 'search';
	}

	public function getRedirect301()
	{
		$data = $this->getNewRouteInfo();
		return $data;
	}
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 14:05, Tue Nov 21st 2017
|| # CVS: $RCSfile$ - $Revision: 83435 $
|| #######################################################################
\*=========================================================================*/
