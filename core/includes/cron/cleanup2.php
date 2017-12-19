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

// ######################## SET PHP ENVIRONMENT ###########################
error_reporting(E_ALL & ~E_NOTICE);
// if (!is_object($vbulletin->db))
// {
// 	exit;
// }

// ########################################################################
// ######################### START MAIN SCRIPT ############################
// ########################################################################
$timenow = vB::getRequest()->getTimeNow();
vB::getDbAssertor()->delete('session',
	array(
		array('field'=>'lastactivity', 'value' => $timenow - vB::getDatastore()->getOption('cookietimeout'), vB_dB_Query::OPERATOR_KEY => vB_dB_Query::OPERATOR_LT)
	)
);

// expired registration images after 1 hour
vB::getDbAssertor()->delete('humanverify',
	array(
		array('field'=>'dateline', 'value' => $timenow - 3600, vB_dB_Query::OPERATOR_KEY => vB_dB_Query::OPERATOR_LT)
	)
);

// Unused filedata is removed after 12 hours
$results = vB::getDbAssertor()->getRows('filedata', array(
	vB_dB_Query::TYPE_KEY => vB_dB_Query::QUERY_SELECT,
	vB_dB_Query::COLUMNS_KEY => 'filedataid',
	vB_dB_Query::CONDITIONS_KEY => array(
		array('field' => 'refcount', 'value' => 0, vB_dB_Query::OPERATOR_KEY => vB_dB_Query::OPERATOR_EQ),
		array('field' => 'dateline', 'value' => $timenow - 43200, vB_dB_Query::OPERATOR_KEY => vB_dB_Query::OPERATOR_LT)// older than 12 hours
)));

$filedataids = array();
foreach ($results AS $result)
{
	$filedataids[] = $result['filedataid'];
}

if (!empty($filedataids))
{
	vB::getDbAssertor()->delete('vBForum:filedata',
		array(array('field' => 'filedataid', 'value' => $filedataids, vB_dB_Query::OPERATOR_KEY => vB_dB_Query::OPERATOR_EQ)));

	vB::getDbAssertor()->delete('vBForum:filedataresize',
		array(array('field' => 'filedataid', 'value' => $filedataids, vB_dB_Query::OPERATOR_KEY => vB_dB_Query::OPERATOR_EQ)));
}

// Expired externalcache data
vB::getDbAssertor()->delete('externalcache',
	array(
		array('field'=>'dateline', 'value' => $timenow - (vB::getDatastore()->getOption('externalcache') * 60), vB_dB_Query::OPERATOR_KEY => vB_dB_Query::OPERATOR_LT)
	)
);

log_cron_action('', $nextitem, 1);

/*=========================================================================*\
|| #######################################################################
|| # Downloaded: 14:05, Tue Nov 21st 2017
|| # CVS: $RCSfile$ - $Revision: 90926 $
|| #######################################################################
\*=========================================================================*/
