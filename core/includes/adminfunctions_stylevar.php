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
 * Enter description here...
 *
 * @return unknown
 */
function fetch_stylevars_array()
{
	global $vbulletin;
	static $stylevars = array();

	if (empty($stylevars))
	{
		if ($vbulletin->GPC['dostyleid'] > 0)
		{
			$parentlist = vB_Library::instance('Style')->fetchTemplateParentlist($vbulletin->GPC['dostyleid']);
			$parentlist = explode(',',trim($parentlist));
		}
		else
		{
			$parentlist = array('-1');
		}
		$stylevars_result = vB::getDbAssertor()->assertQuery('fetchStylevarsArray', array('parentlist' => $parentlist));
		foreach ($stylevars_result as $sv)
		{
			$sv['styleid'] = $sv['stylevarstyleid'];
			if (empty($stylevars[$sv['stylevargroup']][$sv['stylevarid']]['currentstyle']))
			{
				// Skip if Stylevar was already found as changed in the current style
				$stylevars[$sv['stylevargroup']][$sv['stylevarid']] = $sv;
				if ($sv['styleid'] == $vbulletin->GPC['dostyleid'])
				{
					// Stylevar was changed in the current style, no need to check for
					// customized stylevars in the parent styles after that.
					$stylevars[$sv['stylevargroup']][$sv['stylevarid']]['currentstyle'] = '1';
				}
			}
		}
	}

	// sort it so its nice and neat
	$to_return = array();
	$groups = array_keys($stylevars);
	natsort($groups);
	foreach($groups AS $group)
	{
		$stylevarids = array_keys($stylevars[$group]);
		natsort($stylevarids);
		foreach ($stylevarids AS $stylevarid)
		{
			// don't need to go any deeper, stylevar.styleid doesn't really matter in display sorting
			$to_return[$group][$stylevarid] = $stylevars[$group][$stylevarid];
		}
	}

	return $to_return;
}

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 14:05, Tue Nov 21st 2017
|| # CVS: $RCSfile$ - $Revision: 93305 $
|| #######################################################################
\*=========================================================================*/
